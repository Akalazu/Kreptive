<?php

require_once '../../includes/init.php';


if (isset($_GET['userId'])) {
    $userId = $_GET['userId'];

    $userRole = $userCl->getUserDetails($userId);

    if ($userRole->role == 'admin') {
        $sql = "SELECT * FROM `chat`";
        $statement = $pdo->prepare($sql);
        $statement->execute();
        $chats = $statement->fetchAll(PDO::FETCH_OBJ);

        $output = '';
        if (count($chats) > 0) {
            foreach ($chats as $chat) {
                $sender = $userCl->getUserDetails($chat->user_id);
                $receiver = $userCl->getUserDetails($chat->receiver_id);


                // Get the last message for this chat (You can optimize this with a subquery or join)
                $lastMessage = $chatCl->getLastMessage($chat->chat_id);

                // Handle case when there is no last message
                if (!$lastMessage) {
                    $lastMessage = (object) ['content' => 'No message yet'];
                }

                // Get the unread count for this conversation
                $unreadCountQuery = "
                SELECT COUNT(*) 
                FROM messages 
                WHERE (chat_id = :cid AND sender_id = :chat_user AND receiver_id = :user_id) 
                AND is_read = 0";

                $unreadStmt = $pdo->prepare($unreadCountQuery);
                $unreadStmt->bindParam(':chat_user', $chat->receiver_id, PDO::PARAM_INT);
                $unreadStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $unreadStmt->bindParam(':cid', $chat->chat_id, PDO::PARAM_INT);
                $unreadStmt->execute();

                // Fetch unread count
                $unreadCount = $unreadStmt->fetchColumn();

                // Generate HTML output
                $output .= '
                <li class="user-chat-li">
                    <a class="user-chat" data-conversation="' . htmlspecialchars($chat->chat_id) . '" data-receiver="' . htmlspecialchars($chat->user_id) . '" data-sender="' . $chat->receiver_id . '" >
                        <img class="content-message-image" src="' . htmlspecialchars($sender->image ?? '') . '" alt="' . htmlspecialchars($sender->first_name . ' ' . $sender->last_name) . '">
                        <span class="content-message-info">
                            <span class="content-message-name">' . htmlspecialchars($sender->first_name . ' ' . $sender->last_name) . '</span>
                            <span class="content-message-text">' . htmlspecialchars($lastMessage->content) . '</span>
                        </span>';
                // Only display the unread count if it's greater than 0
                if ($unreadCount > 0) {
                    $output .= '
                    <span class="content-message-more">
                        <span class="content-message-unread">' . htmlspecialchars($unreadCount) . '</span>
                        <span class="content-message-time">' . formatDateToTime($lastMessage->sent_at) . '</span>
                    </span>';
                } else {
                    $output .= '<span class="content-message-more">
                                        <span class="content-message-time">' . formatDateToTime($lastMessage->sent_at) . '</span>
                                    </span>';
                }

                $output .= '
                    </a>
                </li>
            ';
            }

            echo $output;
        }
    } else {
        try {

            // SQL query to get distinct chats and corresponding chat user
            $sql = " SELECT 
                CASE WHEN sender_id = $userId THEN receiver_id ELSE sender_id END AS chat_user,
                MAX(m.chat_id) AS chat_id
                    FROM messages m
                    WHERE sender_id = $userId OR receiver_id = $userId
                    GROUP BY chat_user ORDER BY MAX(m.id) DESC";

            // Prepare and execute SQL statement
            $stmt = $pdo->prepare($sql);

            $stmt->execute();

            // Fetch all chats
            $chats = $stmt->fetchAll(PDO::FETCH_OBJ);

            $output = '';

            if (count($chats) > 0) {
                foreach ($chats as $chat) {
                    // Get user details for the chat
                    $receiver = $userCl->getUserDetails($chat->chat_user); // receiver_id

                    // Ensure receiver details are available
                    if (!$receiver) {
                        continue; // Skip if no receiver found
                    }

                    // Get the last message for this chat (You can optimize this with a subquery or join)
                    $lastMessage = $chatCl->getLastMessage($chat->chat_id);

                    // Handle case when there is no last message
                    if (!$lastMessage) {
                        $lastMessage = (object) ['content' => 'No message yet'];
                    }

                    // Get the unread count for this conversation
                    $unreadCountQuery = "
                SELECT COUNT(*) 
                FROM messages 
                WHERE (chat_id = :cid AND sender_id = :chat_user AND receiver_id = :user_id) 
                AND is_read = 0";

                    $unreadStmt = $pdo->prepare($unreadCountQuery);
                    $unreadStmt->bindParam(':chat_user', $chat->chat_user, PDO::PARAM_INT);
                    $unreadStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                    $unreadStmt->bindParam(':cid', $chat->chat_id, PDO::PARAM_INT);
                    $unreadStmt->execute();

                    // Fetch unread count
                    $unreadCount = $unreadStmt->fetchColumn();

                    // Generate HTML output
                    $output .= '
                <li class="user-chat-li">
                    <a class="user-chat" data-conversation="' . htmlspecialchars($chat->chat_id) . '" data-receiver="' . htmlspecialchars($chat->chat_user) . '" data-sender="' . $userId . '" >
                        <img class="content-message-image" src="' . htmlspecialchars($receiver->image ?? '') . '" alt="' . htmlspecialchars($receiver->first_name . ' ' . $receiver->last_name) . '">
                        <span class="content-message-info">
                            <span class="content-message-name">' . htmlspecialchars($receiver->first_name . ' ' . $receiver->last_name) . '</span>
                            <span class="content-message-text">' . htmlspecialchars($lastMessage->content) . '</span>
                        </span>';
                    // Only display the unread count if it's greater than 0
                    if ($unreadCount > 0) {
                        $output .= '
                    <span class="content-message-more">
                        <span class="content-message-unread">' . htmlspecialchars($unreadCount) . '</span>
                        <span class="content-message-time">' . formatDateToTime($lastMessage->sent_at) . '</span>
                    </span>';
                    } else {
                        $output .= '<span class="content-message-more">
                                        <span class="content-message-time">' . formatDateToTime($lastMessage->sent_at) . '</span>
                                    </span>';
                    }

                    $output .= '
                    </a>
                </li>
            ';
                }
            } else {
                $output .= '<p style="text-align: center">No chats found.</p>';
            }



            // Return the output
            echo $output;
        } catch (PDOException $e) {
            echo json_encode(["perror" => $e->getMessage()]);
        } catch (Exception $e) {
            echo json_encode(["eerror" => $e->getMessage()]);
        }
    }
}
