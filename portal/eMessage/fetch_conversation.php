<?php

require_once '../../includes/init.php';


if (isset($_GET['conversation_id'])) {

    $conversation_id = $_GET['conversation_id'];

    // Sample user ID (replace with actual logged-in user ID in your session management)

    $my_id = $_GET['userId']; // Current user ID (receiver)

    // Query to get the receiver's details based on conversation_id
    $queryReceiver = "SELECT r.first_name, r.last_name, r.image 
                  FROM reg_details AS r 
                  JOIN chat AS c ON c.receiver_id = r.id
                  WHERE c.chat_id = :conversation_id";
    $stmtReceiver = $pdo->prepare($queryReceiver);
    $stmtReceiver->execute(['conversation_id' => $conversation_id]);
    $receiver = $stmtReceiver->fetch(PDO::FETCH_ASSOC);

    // Query to get messages in the conversation
    $queryMessages = "SELECT sender_id, content, sent_at as time FROM messages WHERE chat_id = :chat_id ORDER BY sent_at ASC";

    $stmtMessages = $pdo->prepare($queryMessages);




    // Mark messages as read
    $updateSql = "UPDATE messages 
                      SET is_read = 1 
                      WHERE receiver_id = :user_id 
                      AND chat_id = :conversation_id 
                      AND is_read = 0";
    $updateStmt = $pdo->prepare($updateSql);

    if ($stmtMessages->execute(['chat_id' => $conversation_id]) && $updateStmt->execute([':user_id' => $my_id, ':conversation_id' => $conversation_id])) {

        $messages = $stmtMessages->fetchAll(PDO::FETCH_ASSOC);

        // Output JSON data
        echo json_encode([
            'success' => true,
            'my_id' => $my_id,
            'receiver' => $receiver,
            'messages' => $messages
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to fetch messages'
        ]);
    }
}
