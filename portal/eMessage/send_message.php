<?php

require_once '../../includes/init.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $chat_id = $_POST['chat_id'];
    $sender_id = $_POST['sender_id'];
    $receiver_id = $_POST['receiver_id'];
    $content = $_POST['content'];


    $query = "INSERT INTO messages (chat_id, sender_id, receiver_id, content) VALUES (:chat_id, :sender_id, :receiver_id, :content)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':chat_id', $chat_id);
    $stmt->bindParam(':sender_id', $sender_id);
    $stmt->bindParam(':receiver_id', $receiver_id);
    $stmt->bindParam(':content', $content);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => $content]);
    } else {
        echo json_encode(['status' => 'error']);
    }
}
