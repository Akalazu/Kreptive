<?php

require_once '../../includes/init.php';

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
    } else {
        include_once 'header.php';
    }
} else {
    header('Location: ../sign-in');
}

define('SESSION_EXPIRATION_TIME', 900); // 1 hour

if (!isset($_SESSION['last_activity']) || time() > $_SESSION['last_activity'] + SESSION_EXPIRATION_TIME) {
    header('Location: ../lock');
    exit();
} else {
    $_SESSION['last_activity'] = time();
}



// error_reporting(E_ALL);
// ini_set('display_errors', 1);

function checkActiveState($page)
{
    return str_contains($_SERVER['PHP_SELF'], $page) ? 'active' : '';
}
