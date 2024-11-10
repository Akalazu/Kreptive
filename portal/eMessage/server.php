<?php

require_once '../../includes/init.php';

if (isset($_GET['action']) && $_GET['action'] == 'initiate_chat') {
    $chatId = $chatCl->getConversationId();

    echo $chatId;
}

if (isset($_POST['action']) && $_POST['action'] == 'send_message') {

    $message = $_POST['message'];
    $senderId = $_POST['sender_id'];
    $receiverId = $_POST['receiver_id'];
    $chatID = $_POST['chatId'];

    $sql = "INSERT INTO `messages`(`chat_id`, `sender_id`, `receiver_id`, `content`) VALUES (:cid, :sidd, :ridd, :ctt)";

    $statement = $pdo->prepare($sql);

    $statement->bindParam(':cid', $chatID);
    $statement->bindParam(':sidd', $senderId);
    $statement->bindParam(':ridd', $receiverId);
    $statement->bindParam(':ctt', $message);

    $statement->execute();

    echo "Message sent successfully!";
    $statement = $pdo->prepare($sql);
}

if (isset($_POST['receiverId'])) {
    $receiverId = sanitizeText($_POST['receiverId']);

    echo $userCl->checkUserVerificationStatus($receiverId) ? true : false;
}
