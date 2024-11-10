<?php


try {
    // $pdo = new PDO('mysql:host=localhost;dbname=u254739106_kreptive_db', 'u254739106_kreptive', '6F/j#+y3WqIf');
    $pdo = new PDO('mysql:host=localhost;dbname=mintartes_db', 'root', '');


    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Can't connect: " . $e->getMessage());
}
