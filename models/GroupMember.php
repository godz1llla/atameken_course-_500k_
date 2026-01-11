<?php

class GroupMember extends Model {
    protected $table = 'group_members';
    
    /**
     * Получить список студентов в группе с информацией о студентах
     */
    public function getMembersByGroupId($groupId) {
        $stmt = $this->db->prepare("
            SELECT gm.*, 
                   u.id as user_id,
                   u.first_name,
                   u.last_name,
                   u.email,
                   u.role
            FROM group_members gm
            INNER JOIN users u ON gm.student_id = u.id
            WHERE gm.group_id = ?
            ORDER BY gm.joined_at DESC
        ");
        $stmt->execute([$groupId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Получить список студентов, которые НЕ состоят в данной группе
     */
    public function getNonMembers($groupId) {
        $stmt = $this->db->prepare("
            SELECT u.*
            FROM users u
            WHERE u.role = 'student'
            AND u.id NOT IN (
                SELECT gm.student_id 
                FROM group_members gm 
                WHERE gm.group_id = ?
            )
            ORDER BY u.first_name, u.last_name
        ");
        $stmt->execute([$groupId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Добавить студента в группу
     */
    public function addMember($groupId, $studentId) {
        // Проверяем, не добавлен ли уже студент
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE group_id = ? AND student_id = ?");
        $stmt->execute([$groupId, $studentId]);
        
        if ($stmt->fetchColumn() > 0) {
            return false; // Уже добавлен
        }
        
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (group_id, student_id) VALUES (?, ?)");
        return $stmt->execute([$groupId, $studentId]);
    }
    
    /**
     * Удалить студента из группы
     */
    public function removeMember($groupId, $studentId) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE group_id = ? AND student_id = ?");
        return $stmt->execute([$groupId, $studentId]);
    }
    
    /**
     * Проверить, является ли студент участником группы
     */
    public function isMember($groupId, $studentId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE group_id = ? AND student_id = ?");
        $stmt->execute([$groupId, $studentId]);
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Получить количество участников в группе
     */
    public function getMemberCount($groupId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE group_id = ?");
        $stmt->execute([$groupId]);
        return $stmt->fetchColumn();
    }
}

