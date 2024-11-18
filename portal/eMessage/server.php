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

    $sender_details = $userCl->getUserDetails($senderId);
    $receiver_details = $userCl->getUserDetails($receiverId);

    // Prepare SQL statement
    $sql = "INSERT INTO `chat` (`chat_id`, `user_id`, `receiver_id`, `created_at`) VALUES (:cid, :uidd, :rid, NOW())";
    $stmt = $pdo->prepare($sql);

    // Bind parameters
    $stmt->bindParam(":cid", $chatID);
    $stmt->bindParam(":uidd", $senderId);
    $stmt->bindParam(":rid", $receiverId);


    if ($stmt->execute()) {
        $query = "INSERT INTO `messages`(`chat_id`, `sender_id`, `receiver_id`, `content`) VALUES (:cid, :sidd, :ridd, :ctt)";

        $statement = $pdo->prepare($query);

        $statement->bindParam(':cid', $chatID);
        $statement->bindParam(':sidd', $senderId);
        $statement->bindParam(':ridd', $receiverId);
        $statement->bindParam(':ctt', $message);

        if ($statement->execute() && $userCl->sendChatRecipientMail($receiver_details->first_name, $receiver_details->email, $message, $sender_details->username)) {
            echo "Message sent successfully!";
        } else {
            echo "Failed to insert message record.";
        }
    } else {
        echo "Failed to insert chat record.";
    }

    // $statement = $pdo->prepare($sql);
}

if (isset($_POST['receiverId'])) {
    $receiverId = sanitizeText($_POST['receiverId']);

    echo $userCl->checkUserVerificationStatus($receiverId) ? true : false;
}
