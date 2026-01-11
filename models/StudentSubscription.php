<?php

class StudentSubscription extends Model {
    protected $table = 'student_subscriptions';
    
    /**
     * Создать новую подписку студента
     */
    public function create($data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $stmt = $this->db->prepare("INSERT INTO {$this->table} ($columns) VALUES ($placeholders)");
        $stmt->execute(array_values($data));
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Получить активную подписку студента
     */
    public function findActiveForStudent($studentId) {
        $stmt = $this->db->prepare("
            SELECT ss.*, 
                   sp.name as plan_name,
                   sp.description as plan_description,
                   sp.price as plan_price,
                   sp.duration_days,
                   sp.lesson_count,
                   CONCAT(u.first_name, ' ', u.last_name) as student_name,
                   u.email as student_email
            FROM student_subscriptions ss
            INNER JOIN subscription_plans sp ON ss.plan_id = sp.id
            INNER JOIN users u ON ss.student_id = u.id
            WHERE ss.student_id = ? 
            AND ss.status = 'active'
            AND ss.end_date >= CURDATE()
            ORDER BY ss.start_date DESC
            LIMIT 1
        ");
        $stmt->execute([$studentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Получить все подписки студента
     */
    public function findAllForStudent($studentId) {
        $stmt = $this->db->prepare("
            SELECT ss.*, 
                   sp.name as plan_name,
                   sp.description as plan_description,
                   sp.price as plan_price,
                   CONCAT(u.first_name, ' ', u.last_name) as student_name,
                   u.email as student_email
            FROM student_subscriptions ss
            INNER JOIN subscription_plans sp ON ss.plan_id = sp.id
            INNER JOIN users u ON ss.student_id = u.id
            WHERE ss.student_id = ?
            ORDER BY ss.start_date DESC
        ");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Проверить, есть ли у студента активная подписка
     */
    public function hasActiveSubscription($studentId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) 
            FROM {$this->table} 
            WHERE student_id = ? 
            AND status = 'active'
            AND end_date >= CURDATE()
        ");
        $stmt->execute([$studentId]);
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Найти подписку по ID
     */
    public function findById($id) {
        $stmt = $this->db->prepare("
            SELECT ss.*, 
                   sp.name as plan_name,
                   CONCAT(u.first_name, ' ', u.last_name) as student_name
            FROM student_subscriptions ss
            INNER JOIN subscription_plans sp ON ss.plan_id = sp.id
            INNER JOIN users u ON ss.student_id = u.id
            WHERE ss.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Обновить подписку
     */
    public function update($id, $data) {
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "$key = ?";
        }
        $set = implode(', ', $set);
        
        $stmt = $this->db->prepare("UPDATE {$this->table} SET $set WHERE id = ?");
        $values = array_values($data);
        $values[] = $id;
        
        return $stmt->execute($values);
    }
    
    /**
     * Обновить статус подписки на 'expired'
     */
    public function markAsExpired($id) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET status = 'expired' WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Обновить статус подписки на 'cancelled'
     */
    public function cancel($id) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET status = 'cancelled' WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Уменьшить количество оставшихся занятий
     */
    public function decrementLessonsRemaining($id, $count = 1) {
        $stmt = $this->db->prepare("
            UPDATE {$this->table} 
            SET lessons_remaining = GREATEST(0, lessons_remaining - ?) 
            WHERE id = ?
        ");
        return $stmt->execute([$count, $id]);
    }
}

