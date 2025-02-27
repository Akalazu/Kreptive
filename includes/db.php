<?php

if ($_SERVER['SERVER_NAME'] == 'localhost') {
    $pdo = new PDO('mysql:host=localhost;dbname=mintartes_db', 'root', '');
} else {
    $pdo = new PDO('mysql:host=localhost;dbname=u254739106_niffiti_db', 'u254739106_niffiti', 'i8&tOZQC');
}
try {
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Can't connect: " . $e->getMessage());
}
