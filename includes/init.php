<?php

declare(strict_types=1);
//exit();

ob_start();
session_start();
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

include_once 'db.php';
include_once 'function.php';
include_once 'class/User.php';
include_once 'class/Activity.php';
include_once 'class/Chat.php';
require_once 'phpmailer/src/Exception.php';
require_once 'phpmailer/src/PHPMailer.php';
require_once 'phpmailer/src/SMTP.php';



$userCl = new User($pdo);
$activityCl = new Activity($pdo);
$chatCl = new Chat($pdo);
setlocale(LC_MONETARY, 'en_US');
date_default_timezone_set('Africa/Lagos');

$admin_mails = $userCl->getAllAdminMails();


// current exchange rate in DB
$rate = $userCl->getCurrExchangeRate();
// echo $rate->time;
// echo number_format(strtotime($rate->time) + 1200);
// echo '----------------------------------------------------------------';
// echo (strtotime($rate->time) + 1200) > time() ? 'true' : 'false';
// echo '----------------------------------------------------------------';
// echo number_format(time());
// die();

if ((strtotime($rate->time) + 1200) < time()) {
    // API to convert ETH to the current dollar rate
    $coinGeckoApiUrl = "https://api.coingecko.com/api/v3/simple/price?ids=ethereum&vs_currencies=usd";

    // Error handling for API call
    $response = @file_get_contents($coinGeckoApiUrl); // Suppress errors with @

    if ($response !== false) {
        $data = json_decode($response, true);

        // Check if the API returned the ethereum to USD rate
        if (isset($data['ethereum']['usd'])) {
            $ethereumToUsdRate = $data['ethereum']['usd'];
        } else {
            // If the rate is not returned, fallback to the current rate
            $ethereumToUsdRate = $rate->rate;
        }
    } else {
        // If the API request fails, fallback to the current rate
        $ethereumToUsdRate = $rate->rate;
    }

    // Update only if the API returned a different rate
    if ($ethereumToUsdRate != $rate->rate) {
        // Get current time
        $time = date('h:ia d-m-Y', time());

        // Update the exchange rate in the database
        $sql = "UPDATE `exchange_rate` SET `rate`= :rt, `time`= :tt WHERE `id` = 1";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':rt', $ethereumToUsdRate);
        $statement->bindParam(':tt', $time);
        $statement->execute();
    }
} else {
    // No API call needed, use the stored rate
    $ethereumToUsdRate = $rate->rate;
}
