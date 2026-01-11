<?php

class SubscriptionPlan extends Model {
    protected $table = 'subscription_plans';
    
    /**
     * Создать новый тарифный план
     */
    public function create($data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $stmt = $this->db->prepare("INSERT INTO {$this->table} ($columns) VALUES ($placeholders)");
        $stmt->execute(array_values($data));
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Получить все активные тарифные планы (для продажи)
     */
    public function findAllActive() {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY price ASC, created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Получить все тарифные планы (включая неактивные)
     */
    public function findAll() {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY is_active DESC, price ASC, created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Найти тарифный план по ID
     */
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Обновить тарифный план
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
     * Деактивировать тарифный план (установить is_active = FALSE)
     */
    public function deactivate($id) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET is_active = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Активировать тарифный план (установить is_active = TRUE)
     */
    public function activate($id) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET is_active = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

