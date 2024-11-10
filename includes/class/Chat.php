<?php


class Chat
{
    // BindValue vs BindParameter In PHP
    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getLastMessage($chatId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM messages WHERE chat_id = :cid ORDER BY id DESC LIMIT 1");
        $stmt->bindParam(':cid', $chatId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getConversationId()
    {

        do {
            $chatId = genArtLink();

            $stmt = $this->pdo->prepare("SELECT * FROM chat WHERE chat_id = :id");
            $stmt->bindParam(':id', $chatId, PDO::PARAM_STR);
            $stmt->execute();

            $convoId = $stmt->fetch(PDO::FETCH_OBJ);
        } while ($convoId);


        return $chatId; // Return unique chat ID

    }

    public function doesChatExistBetweenUsers($senderId, $receiverId)
    {
        try {
            // SQL query to check for an existing chat between two users
            $sql = "SELECT COUNT(*) FROM chat 
                WHERE (user_id = $senderId  AND receiver_id = $receiverId) 
                   OR (user_id = $receiverId AND receiver_id =$senderId )";

            $stmt = $this->pdo->prepare($sql);

            // Execute the query
            $stmt->execute();

            // Fetch the count result
            $count = $stmt->fetchColumn();

            // If count is greater than zero, a chat exists
            return $count > 0;
        } catch (PDOException $e) {
            // Handle exceptions, such as connection issues
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }
}
