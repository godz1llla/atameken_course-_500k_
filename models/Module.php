<?php

class Module extends Model {
    protected $table = 'modules';
    
    public function getByCourse($courseId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE course_id = ? ORDER BY order_num");
        $stmt->execute([$courseId]);
        return $stmt->fetchAll();
    }
}

