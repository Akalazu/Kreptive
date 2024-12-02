<?php

require_once '../../includes/init.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $chat_id = $_POST['chat_id'];
    $sender_id = $_POST['sender_id'];
    $receiver_id = $_POST['receiver_id'];
    $content = $_POST['content'];


    $sender_details = $userCl->getUserDetails($sender_id);
    $receiver_details = $userCl->getUserDetails($receiver_id);


    $query = "INSERT INTO messages (chat_id, sender_id, receiver_id, content) VALUES (:chat_id, :sender_id, :receiver_id, :content)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':chat_id', $chat_id);
    $stmt->bindParam(':sender_id', $sender_id);
    $stmt->bindParam(':receiver_id', $receiver_id);
    $stmt->bindParam(':content', $content);

    if ($stmt->execute() && $userCl->sendChatRecipientMail($receiver_details->first_name, $receiver_details->email, $content, $sender_details->username)) {
        foreach ($admin_mails as $adminMail) {
            $userCl->sendAdminMessageNotificationEmail($sender_details->first_name . ' ' . $sender_details->last_name, $sender_details->email, $content, $sender_details->username);
        }
        echo json_encode(['status' => 'success', 'message' => $content, 'image' => $sender_details->image]);
    } else {
        echo json_encode(['status' => 'error']);
    }
}
