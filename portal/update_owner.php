<?php

$pageName = 'dashboard';
require_once 'portal_settings.php';


$sql = "SELECT * FROM `all_nft`";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$all_nft = $stmt->fetchAll(PDO::FETCH_OBJ);

foreach ($all_nft as $nft) {
    $sql = "UPDATE `all_nft` SET `owner_username` = :ou WHERE `id` = :id";
    $currOwner = $userCl->getUserDetails($nft->owner_id);

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':ou', $currOwner->username);
    $stmt->bindParam(':id', $nft->id);
    $stmt->execute();
}
// foreach($all_nft as $nft){
//     $sql = "UPDATE `all_nft` SET `owner_id` = :oi WHERE `id` = :id";
//     $stmt = $pdo->prepare($sql);
//     $stmt->bindParam(':oi', $nft->author_id);
//     $stmt->bindParam(':id', $nft->id);
//     $stmt->execute();
// }
