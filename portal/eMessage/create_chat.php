<?php

require_once '../../includes/init.php';

// initiate a new chat record between the current user and the receiver
if (isset($_POST['conversationId'])) {
    try {
        // Collect POST data
        $senderId = $_POST['sender_id'];
        $receiverId = $_POST['receiver_id'];

        if (!$chatCl->doesChatExistBetweenUsers($senderId, $receiverId)) {

            // Execute statement and prepare response
            $response = [];
            $response['success'] = true;
            $response['chat_id'] = $chatId;

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
