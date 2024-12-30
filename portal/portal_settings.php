<?php
include_once "../includes/init.php";

/**
 *  On logging in, a session starts, and it holds your 
 * userid, tid, useragent[browswer], role, $_SERVER['HTTP_USER_AGENT'], $_SERVER['REMOTE_ADDR']
 * if any of these details don't exist then it should log the user out and in the case of user agent and remote addr, destroy the session and die() with return message Unauthorized User
 * 
 * 
 */
//  error_reporting(E_ALL);
if (isset($_SESSION['currid'])) {
    $idd = $_SESSION['currid'];
    $sql = "SELECT * FROM `reg_details` WHERE id = :idd";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':idd', $idd);
    $statement->execute();
    $currUser = $statement->fetch(PDO::FETCH_OBJ);
    $fullname = $currUser->first_name . ' ' . $currUser->last_name;

    // var_dump($currUser->verified);
    if ($currUser->verified != 1) {
        header('Location: logout');
    }
} else {
    header('Location: ../sign-in');
}

define('SESSION_EXPIRATION_TIME', 900); // 1 hour

if (!isset($_SESSION['last_activity']) || time() > $_SESSION['last_activity'] + SESSION_EXPIRATION_TIME) {
    header('Location: ./lock');
    exit();
} else {
    $_SESSION['last_activity'] = time();
}


$url = $_SERVER['REQUEST_URI'];
if (!str_contains($url, 'get_product_data.php') && !str_contains($url, 'getdataa.php')) {
    include_once 'portal_header.php';
}


// // API to convert ETH to the current dollarRate
// $coinGeckoApiUrl = "https://api.coingecko.com/api/v3/simple/price?ids=ethereum&vs_currencies=usd";
// $response = file_get_contents($coinGeckoApiUrl);
// $data = json_decode($response, true);

// if (isset($data['ethereum']['usd'])) {
//     $ethereumToUsdRate = $data['ethereum']['usd'];
// }
// 198754

// error_reporting(E_ALL);
// ini_set('display_errors', 1);
