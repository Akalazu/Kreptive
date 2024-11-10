<?php

function sanitizeName($text)
{
    $input = trim($text);
    $input = strip_tags($input);
    $input = htmlspecialchars($input);
    $input = strtolower($input);
    $input = ucwords($input);
    return $input;
}

function sanitizeText($text)
{
    $input = trim($text);
    $input = strip_tags($input);
    $input = htmlspecialchars($input);
    return $input;
}

function sanitizeOption($text)
{
    $input = trim($text);
    $input = htmlspecialchars($input);
    return $input;
}

function sanitizeMail($mail)
{
    $result = strtolower($mail);
    $result = strip_tags($result);
    $result = str_replace(' ', '', $result);
    if (filter_var($result, FILTER_SANITIZE_EMAIL)) {
        $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i';
        if (preg_match($regex, $result)) {
            return $result;
        } else {
            $errors[] = 'Invalid email address';
            return $errors;
        }
    }
}

function containsNumbers($inputValue)
{
    // Regular expression pattern to match any digit
    $pattern = '/\d/';

    // Use preg_match to check if the pattern is found in the input value
    // Returns 1 if pattern is found, 0 if not found, or false on error
    return preg_match($pattern, $inputValue) === 1;
}

/* Generates user id that increaments */

function genid($pdo)
{
    $statement = $pdo->prepare("SELECT MAX(code) AS `maxid` FROM `reg_details`");
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    $maxid = $result["maxid"];
    return ++$maxid;
}
function genAdminid($pdo)
{
    $statement = $pdo->prepare("SELECT MAX(code) AS maxid FROM `tbl_login`");
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    $maxid = $result["maxid"];
    return ++$maxid;
}


function genPassword(string $sname)
{
    $pass = ucfirst(trim(strtolower($sname)));
    return $pass;
}

/* Remembers input values of form */

function getInputValue($input)
{
    if (isset($_POST[$input])) {
        return htmlentities($_POST[$input]);
    } else {
        return '';
    }
}

function generateFourRandomNumbers()
{
    $numbers = array();
    for ($i = 0; $i < 4; $i++) {
        $numbers[] = rand(0, 9); // Generates a random number between 1 and 100 (inclusive)
    }
    return $numbers;
}

function genRefId()
{

    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '#';
    for ($i = 0; $i < 15; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }
    return $randomString;
}
function genArtLink()
{

    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < 15; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }
    return $randomString;
}

function generateFakeAddress()
{
    // Start with '0x' for an Ethereum-style address
    $address = '0x';

    // Generate 40 random hexadecimal characters
    for ($i = 0; $i < 40; $i++) {
        $address .= dechex(mt_rand(0, 15));
    }

    return $address;
}


//**************To add line break when enter is pressed
function addLineBreak($inputText)
{
    $inputText = str_replace('\r\n', '\n', $inputText);
    $inputText = nl2br($inputText); //replace spaces with nothing as space isnt required
    return $inputText;
} //end

/************ Generates random number from 1 - 20 and stores them in an array */
function uniqueNum($min, $max, $quan)
{
    $numbers = range($min, $max);
    shuffle($numbers);
    return array_slice($numbers, 0, $quan);
}

// clearSessions for quiz
function clearSessions()
{
    unset(
        $_SESSION["currquizscore"],
        $_SESSION["currcourse"],
        $_SESSION["currtable"],
        $_SESSION["currsession"],
        $_SESSION["questions"],
        $_SESSION["questionNo"],
        $_SESSION["index"],
        $_SESSION["currquesno"],
        $_SESSION['duration'],
        $_SESSION['start_time'],
        $_SESSION['end_time'],
        $_SESSION['remaining_time']
    );
}

//The argument $time_ago is in timestamp (Y-m-d H:i:s)format.

function timeAgo($time_ago)
{
    $time_ago = strtotime($time_ago);
    $cur_time   = time();
    $time_elapsed   = $cur_time - $time_ago; //4weeks
    $seconds    = $time_elapsed;
    $minutes    = intval($time_elapsed / 60);
    $hours      = intval($time_elapsed / 3600);
    $days       = intval($time_elapsed / 86400);
    $weeks      = intval($time_elapsed / 604800);
    $months     = round($time_elapsed / 2600640);
    $years      = intval($time_elapsed / 31207680);
    // Seconds
    if ($seconds < 60) {
        return "Just now";
    }
    //Minutes
    else if ($minutes < 60) {
        if ($minutes == 1) {
            return "1 minute ago";
        } else {
            return "$minutes minutes ago";
        }
    }
    //Hours
    else if ($hours < 24) {
        if ($hours == 1) {
            return "1 hour ago";
        } else {
            return "$hours hrs ago";
        }
    }
    //Days
    else if ($days < 7) {
        if ($days == 1) {
            return "Yesterday";
        } else {
            return "$days days ago";
        }
    }
    //Weeks
    else if ($weeks < 4) {
        if ($weeks == 1) {
            return "1 week ago";
        } else {
            return "$weeks weeks ago";
        }
    }
    //Months
    else if ($months < 12) {
        if ($months == 1) {
            return "1 month ago";
        } else {
            return "$months months ago";
        }
    }
    //Years
    else {
        if ($years == 1) {
            return "1 year ago";
        } else {
            return "$years years ago";
        }
    }
}

function doesEmailExist($pdo, $email)
{
    $sql = "SELECT * FROM `reg_details` WHERE `email` = :mail";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':mail', $email);
    $stmt->execute();


    if ($stmt->rowCount() > 0) {
        $detOnEmail = $stmt->fetch(PDO::FETCH_OBJ);
        $_SESSION['nameOnEmail'] = $detOnEmail->first_name . ' ' . $detOnEmail->last_name;
        return true;
    }
}


function madeRecoverRequest($pdo, $email)
{
    $sql = "UPDATE `reg_details` SET `recover_request`= 1 WHERE `email` = :mail";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':mail', $email);

    if ($stmt->execute()) {
        return true;
    }
}
function completedRecoverRequest($pdo, $email)
{
    $sql = "UPDATE `reg_details` SET `recover_request`= 0 WHERE `email` = :mail";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':mail', $email);

    if ($stmt->execute()) {
        return true;
    }
}

function get_current_total_withdrawal($pdo, $user_id)
{
    // replace this with your actual database query to get the user's current total withdrawal amount for the day
    $sql = "SELECT * FROM `reg_details` WHERE `id` = :idd";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':idd', $user_id);
    $statement->execute();
    $user_details = $statement->fetch(PDO::FETCH_OBJ);
    $total_withdrawal = $user_details->total_withdrawal;
    return $total_withdrawal; // for example purposes, assume the user has already withdrawn $2 today
}

function processWithdrawal($max_limit, $user_idd, $amount, $pdo)
{
    $user_id = $user_idd; // replace with the user's actual ID
    $current_total_withdrawal = get_current_total_withdrawal($pdo, $user_id);

    // Check if the user has already reached the $5 limit for the day
    if ($current_total_withdrawal >= $max_limit) {
        echo '
           <script>
         swal({
               title: "Error",
               text: "Sorry, you have reached your maximum daily withdrawal limit of ' . $max_limit . 'ETH",
               icon: "warning",
               button: "Ok",
             });
         </script>
     ';
    } else {
        // Calculate the maximum amount the user can withdraw, given their current total withdrawal amount for the day
        $max_withdrawal = $max_limit - $current_total_withdrawal;

        // Check if the requested withdrawal amount exceeds the user's maximum withdrawal amount for the day
        $requested_withdrawal = $amount; // replace with the actual withdrawal amount requested by the user
        if ($requested_withdrawal > $max_withdrawal) {
            echo '
           <script>
         swal({
               title: "Error",
               text: "Sorry, you can only withdraw up to ' . $max_withdrawal . 'ETH today",
               icon: "warning",
               button: "Ok",
             });
         </script>
     ';
        } else {
            // Process the withdrawal request
            // process_withdrawal_request($user_id, $requested_withdrawal);
            // echo "Your withdrawal request of $requested_withdrawal has been processed.";
            return true;
        }
    }
}
function getUserNameById($pdo, $id)
{
    $sql = "SELECT `username` FROM reg_details WHERE `id` = :idd";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':idd', $id);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_OBJ);
    return $result->username;
}


/** mask email function */

function maskAddress($email)
{
    $length = strlen($email);
    $username = substr($email, 0, 6) . str_repeat('.', 3) . substr($email, $length - 6, $length);
    return $username;
}


function formatDateToTime($datetime)
{
    // Convert the string to a DateTime object
    $date = new DateTime($datetime);
    // Format the DateTime object to the desired format
    return $date->format('h:ia');
}
