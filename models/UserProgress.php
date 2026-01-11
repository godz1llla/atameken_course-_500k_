<?php

class UserProgress extends Model {
    protected $table = 'user_progress';
    
    public function markComplete($userId, $lessonId) {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table} (user_id, lesson_id, is_completed, completed_at) 
            VALUES (?, ?, 1, NOW())
            ON DUPLICATE KEY UPDATE is_completed = 1, completed_at = NOW()
        ");
        return $stmt->execute([$userId, $lessonId]);
    }
    
    public function isCompleted($userId, $lessonId) {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE user_id = ? AND lesson_id = ? AND is_completed = 1
        ");
        $stmt->execute([$userId, $lessonId]);
        return $stmt->fetch() !== false;
    }
}

