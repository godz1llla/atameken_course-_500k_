<?php

class Course extends Model {
    protected $table = 'courses';
    
    public function getWithTeacher() {
        $stmt = $this->db->query("
            SELECT c.*, CONCAT(u.first_name, ' ', u.last_name) as teacher_name 
            FROM courses c 
            LEFT JOIN users u ON c.teacher_id = u.id
        ");
        return $stmt->fetchAll();
    }
    
    public function getPublished() {
        $stmt = $this->db->query("SELECT * FROM {$this->table} WHERE is_published = 1");
        return $stmt->fetchAll();
    }
    
    public function getByTeacher($teacherId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE teacher_id = ?");
        $stmt->execute([$teacherId]);
        return $stmt->fetchAll();
    }
    
    public function togglePublish($id) {
        $course = $this->findById($id);
        $newStatus = $course['is_published'] ? 0 : 1;
        return $this->update($id, ['is_published' => $newStatus]);
    }
    
    public function getModules($courseId) {
        $stmt = $this->db->prepare("SELECT * FROM modules WHERE course_id = ? ORDER BY order_num");
        $stmt->execute([$courseId]);
        return $stmt->fetchAll();
    }
    
    public function getLessons($moduleId) {
        $stmt = $this->db->prepare("SELECT * FROM lessons WHERE module_id = ? ORDER BY order_num");
        $stmt->execute([$moduleId]);
        return $stmt->fetchAll();
    }
}

