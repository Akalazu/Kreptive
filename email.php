<?php

require_once "includes/init.php";

$name = 'Élise';

$email = 'emilise.b@orange.fr';

$userCl->sendVATMail($name, $email);
