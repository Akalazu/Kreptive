<?php
require_once '../../includes/init.php';


// mark_messages_as_read.php
if (isset($_POST['conversation_id']) && isset($_POST['user_id'])) {
    $conversationId = $_POST['conversation_id'];
    $userId = $_POST['user_id'];

    $sql = "UPDATE messages SET is_read = 1 WHERE receiver_id = :user_id AND chat_id = :conversation_id AND is_read = 0";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $userId, ':conversation_id' => $conversationId]);

    echo json_encode(["success" => true]);
}
