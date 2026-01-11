<?php

class Group extends Model {
    protected $table = 'groups';
    
    /**
     * Получить все группы с информацией о курсе и учителе
     */
    public function findAll() {
        $stmt = $this->db->query("
            SELECT g.*, 
                   c.title as course_title,
                   CONCAT(u.first_name, ' ', u.last_name) as teacher_name,
                   u.email as teacher_email
            FROM groups g
            LEFT JOIN courses c ON g.course_id = c.id
            LEFT JOIN users u ON g.teacher_id = u.id
            ORDER BY g.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Найти группу по ID с информацией о курсе и учителе
     */
    public function findById($id) {
        $stmt = $this->db->prepare("
            SELECT g.*, 
                   c.title as course_title,
                   c.description as course_description,
                   CONCAT(u.first_name, ' ', u.last_name) as teacher_name,
                   u.email as teacher_email
            FROM groups g
            LEFT JOIN courses c ON g.course_id = c.id
            LEFT JOIN users u ON g.teacher_id = u.id
            WHERE g.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Создать новую группу
     */
    public function create($data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $stmt = $this->db->prepare("INSERT INTO {$this->table} ($columns) VALUES ($placeholders)");
        $stmt->execute(array_values($data));
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Обновить данные группы
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
     * Удалить группу
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Получить группы по курсу
     */
    public function getByCourse($courseId) {
        $stmt = $this->db->prepare("
            SELECT g.*, 
                   CONCAT(u.first_name, ' ', u.last_name) as teacher_name
            FROM groups g
            LEFT JOIN users u ON g.teacher_id = u.id
            WHERE g.course_id = ?
            ORDER BY g.name
        ");
        $stmt->execute([$courseId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Получить группы по учителю
     */
    public function getByTeacher($teacherId) {
        $stmt = $this->db->prepare("
            SELECT g.*, 
                   c.title as course_title
            FROM groups g
            LEFT JOIN courses c ON g.course_id = c.id
            WHERE g.teacher_id = ?
            ORDER BY g.created_at DESC
        ");
        $stmt->execute([$teacherId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Проверить существование группы с таким именем
     */
    public function nameExists($name, $excludeId = null) {
        if ($excludeId) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE name = ? AND id != ?");
            $stmt->execute([$name, $excludeId]);
        } else {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE name = ?");
            $stmt->execute([$name]);
        }
        return $stmt->fetchColumn() > 0;
    }
}

