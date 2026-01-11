<?php

class Message extends Model {
    protected $table = 'messages';
    
    public function sendMessage($senderId, $receiverId, $courseId, $subject, $message) {
        return $this->create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'course_id' => $courseId,
            'subject' => $subject,
            'message' => $message
        ]);
    }
    
    public function getConversation($userId1, $userId2, $courseId = null) {
        if ($courseId) {
            $stmt = $this->db->prepare("
                SELECT m.*, 
                       CONCAT(u.first_name, ' ', u.last_name) as sender_name,
                       u.avatar as sender_avatar
                FROM messages m 
                INNER JOIN users u ON m.sender_id = u.id 
                WHERE ((m.sender_id = ? AND m.receiver_id = ?) 
                   OR (m.sender_id = ? AND m.receiver_id = ?))
                   AND m.course_id = ?
                ORDER BY m.created_at ASC
            ");
            $stmt->execute([$userId1, $userId2, $userId2, $userId1, $courseId]);
        } else {
            $stmt = $this->db->prepare("
                SELECT m.*, 
                       CONCAT(u.first_name, ' ', u.last_name) as sender_name,
                       u.avatar as sender_avatar
                FROM messages m 
                INNER JOIN users u ON m.sender_id = u.id 
                WHERE (m.sender_id = ? AND m.receiver_id = ?) 
                   OR (m.sender_id = ? AND m.receiver_id = ?)
                ORDER BY m.created_at ASC
            ");
            $stmt->execute([$userId1, $userId2, $userId2, $userId1]);
        }
        
        return $stmt->fetchAll();
    }
    
    public function getConversationsList($userId) {
        $stmt = $this->db->prepare("
            SELECT DISTINCT 
                CASE 
                    WHEN m.sender_id = ? THEN m.receiver_id 
                    ELSE m.sender_id 
                END as other_user_id,
                CONCAT(u.first_name, ' ', u.last_name) as other_user_name,
                u.avatar as other_user_avatar,
                c.title as course_title,
                (SELECT message FROM messages m2 
                 WHERE (m2.sender_id = ? AND m2.receiver_id = other_user_id) 
                    OR (m2.sender_id = other_user_id AND m2.receiver_id = ?)
                 ORDER BY created_at DESC LIMIT 1) as last_message,
                (SELECT created_at FROM messages m2 
                 WHERE (m2.sender_id = ? AND m2.receiver_id = other_user_id) 
                    OR (m2.sender_id = other_user_id AND m2.receiver_id = ?)
                 ORDER BY created_at DESC LIMIT 1) as last_message_time,
                (SELECT COUNT(*) FROM messages m2 
                 WHERE m2.sender_id = other_user_id AND m2.receiver_id = ? 
                    AND m2.is_read = 0) as unread_count
            FROM messages m
            INNER JOIN users u ON u.id = CASE 
                WHEN m.sender_id = ? THEN m.receiver_id 
                ELSE m.sender_id 
            END
            LEFT JOIN courses c ON m.course_id = c.id
            WHERE m.sender_id = ? OR m.receiver_id = ?
            ORDER BY last_message_time DESC
        ");
        $stmt->execute([$userId, $userId, $userId, $userId, $userId, $userId, $userId, $userId, $userId]);
        return $stmt->fetchAll();
    }
    
    public function markAsRead($userId, $senderId) {
        $stmt = $this->db->prepare("
            UPDATE messages 
            SET is_read = 1 
            WHERE receiver_id = ? AND sender_id = ?
        ");
        return $stmt->execute([$userId, $senderId]);
    }
    
    public function getUnreadCount($userId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM messages 
            WHERE receiver_id = ? AND is_read = 0
        ");
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return $result['count'];
    }
}

