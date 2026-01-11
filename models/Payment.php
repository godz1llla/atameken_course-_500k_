<?php

class Payment extends Model {
    protected $table = 'payments';
    
    /**
     * Создать новый платеж
     */
    public function create($data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $stmt = $this->db->prepare("INSERT INTO {$this->table} ($columns) VALUES ($placeholders)");
        $stmt->execute(array_values($data));
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Получить все платежи студента
     */
    public function findAllForStudent($studentId) {
        $stmt = $this->db->prepare("
            SELECT p.*, 
                   ss.id as subscription_id,
                   sp.name as plan_name,
                   CONCAT(u.first_name, ' ', u.last_name) as student_name
            FROM payments p
            INNER JOIN student_subscriptions ss ON p.student_subscription_id = ss.id
            INNER JOIN subscription_plans sp ON ss.plan_id = sp.id
            INNER JOIN users u ON p.student_id = u.id
            WHERE p.student_id = ?
            ORDER BY p.payment_date DESC, p.created_at DESC
        ");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Получить все платежи для конкретной подписки
     */
    public function findAllForSubscription($subscriptionId) {
        $stmt = $this->db->prepare("
            SELECT p.*, 
                   sp.name as plan_name
            FROM payments p
            INNER JOIN student_subscriptions ss ON p.student_subscription_id = ss.id
            INNER JOIN subscription_plans sp ON ss.plan_id = sp.id
            WHERE p.student_subscription_id = ?
            ORDER BY p.payment_date DESC, p.created_at DESC
        ");
        $stmt->execute([$subscriptionId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Получить общую сумму платежей для подписки
     */
    public function getTotalAmountForSubscription($subscriptionId) {
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(amount), 0) as total
            FROM {$this->table}
            WHERE student_subscription_id = ?
        ");
        $stmt->execute([$subscriptionId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (float)$result['total'];
    }
    
    /**
     * Найти платеж по ID
     */
    public function findById($id) {
        $stmt = $this->db->prepare("
            SELECT p.*, 
                   ss.id as subscription_id,
                   sp.name as plan_name,
                   CONCAT(u.first_name, ' ', u.last_name) as student_name
            FROM payments p
            INNER JOIN student_subscriptions ss ON p.student_subscription_id = ss.id
            INNER JOIN subscription_plans sp ON ss.plan_id = sp.id
            INNER JOIN users u ON p.student_id = u.id
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Получить общую сумму всех платежей студента
     */
    public function getTotalAmountForStudent($studentId) {
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(amount), 0) as total
            FROM {$this->table}
            WHERE student_id = ?
        ");
        $stmt->execute([$studentId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (float)$result['total'];
    }
}

