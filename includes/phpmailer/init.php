<?php

declare(strict_types=1);
ob_start();
session_start();
// error_reporting(E_ERROR | E_WARNING | E_PARSE);

use Bitly\BitlyClient;

include_once 'db';
include_once 'function';
include_once 'class/User';
include_once 'class/Activity';
require_once 'phpmailer/src/Exception';
require_once 'phpmailer/src/PHPMailer';
require_once 'phpmailer/src/SMTP';



require_once 'bitly/vendor/autoload';



$bitlyClient = new BitlyClient('980c3b904f5aa1ff5fef9075972ed0611e50dbd6');


$userCl = new User($pdo);
$activityCl = new Activity($pdo);
setlocale(LC_MONETARY, 'en_US');
date_default_timezone_set('Africa/Lagos');
