<?php

require_once '../../includes/init.php';

// initiate a new chat record between the current user and the receiver
if (isset($_POST['conversationId'])) {
    try {
        // Collect POST data
        $senderId = $_POST['sender_id'];
        $receiverId = $_POST['receiver_id'];
        $chatId = $_POST['conversationId'];

        if (!$chatCl->doesChatExistBetweenUsers($senderId, $receiverId)) {

            // Prepare SQL statement
            $sql = "INSERT INTO `chat` (`chat_id`, `user_id`, `receiver_id`, `created_at`) VALUES (:cid, :uidd, :rid, NOW())";
            $stmt = $pdo->prepare($sql);

            // Bind parameters
            $stmt->bindParam(":cid", $chatId);
            $stmt->bindParam(":uidd", $senderId);
            $stmt->bindParam(":rid", $receiverId);

            // Execute statement and prepare response
            $response = [];
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['chat_id'] = $chatId;
            } else {
                $response['success'] = false;
                $response['error'] = 'Failed to insert chat record.';
            }

            // Output JSON response
            echo json_encode($response);
        } else {
            echo json_encode([
                'success' => "falsee",
                'error' => 'Chat already exists.'
            ]);
        }
    } catch (Throwable $th) {
        // Catch and display any errors
        echo json_encode([
            'success' => false,
            'error' => "Error: " . $th->getMessage()
        ]);
    }
}
