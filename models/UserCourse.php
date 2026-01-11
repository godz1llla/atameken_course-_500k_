<?php

class UserCourse extends Model {
    protected $table = 'user_courses';
    
    public function enroll($userId, $courseId) {
        try {
            return $this->create([
                'user_id' => $userId,
                'course_id' => $courseId
            ]);
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function getUserCourses($userId) {
        $stmt = $this->db->prepare("
            SELECT c.*, uc.enrolled_at, uc.completed_at 
            FROM courses c 
            INNER JOIN user_courses uc ON c.id = uc.course_id 
            WHERE uc.user_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    public function isEnrolled($userId, $courseId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE user_id = ? AND course_id = ?");
        $stmt->execute([$userId, $courseId]);
        return $stmt->fetch() !== false;
    }
    
    public function completeCourse($userId, $courseId) {
        $stmt = $this->db->prepare("
            UPDATE {$this->table} 
            SET completed_at = NOW() 
            WHERE user_id = ? AND course_id = ?
        ");
        return $stmt->execute([$userId, $courseId]);
    }
    
    public function getCourseProgress($userId, $courseId) {
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(DISTINCT l.id) as total_lessons,
                COUNT(DISTINCT up.lesson_id) as completed_lessons
            FROM courses c
            INNER JOIN modules m ON m.course_id = c.id
            INNER JOIN lessons l ON l.module_id = m.id
            LEFT JOIN user_progress up ON up.lesson_id = l.id AND up.user_id = ? AND up.is_completed = 1
            WHERE c.id = ?
        ");
        $stmt->execute([$userId, $courseId]);
        $result = $stmt->fetch();
        
        if ($result['total_lessons'] == 0) return 0;
        return round(($result['completed_lessons'] / $result['total_lessons']) * 100);
    }
    
    public function getTeachersForStudent($studentId) {
        $stmt = $this->db->prepare("
            SELECT DISTINCT 
                u.id AS teacher_id,
                CONCAT(u.first_name, ' ', u.last_name) AS teacher_name,
                u.email AS teacher_email,
                c.title AS course_title
            FROM {$this->table} uc
            INNER JOIN courses c ON uc.course_id = c.id
            INNER JOIN users u ON c.teacher_id = u.id
            WHERE uc.user_id = ? AND u.id IS NOT NULL
        ");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll();
    }
    
    public function getStudentsForCourse($courseId) {
        $stmt = $this->db->prepare("
            SELECT u.*, uc.enrolled_at, uc.completed_at
            FROM users u
            INNER JOIN {$this->table} uc ON u.id = uc.user_id
            WHERE uc.course_id = ?
        ");
        $stmt->execute([$courseId]);
        return $stmt->fetchAll();
    }
}

