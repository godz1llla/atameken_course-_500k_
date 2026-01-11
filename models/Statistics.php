<?php

class Statistics extends Model {
    
    public function getStudentStats($userId) {
        // Общая статистика студента
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(DISTINCT uc.course_id) as total_enrolled,
                COUNT(DISTINCT CASE WHEN uc.completed_at IS NOT NULL THEN uc.course_id END) as completed_courses,
                COALESCE(AVG(utr.score), 0) as avg_test_score,
                COUNT(DISTINCT ua.achievement_id) as total_achievements,
                COUNT(DISTINCT ucert.id) as total_certificates
            FROM users u
            LEFT JOIN user_courses uc ON u.id = uc.user_id
            LEFT JOIN user_test_results utr ON u.id = utr.user_id
            LEFT JOIN user_achievements ua ON u.id = ua.user_id
            LEFT JOIN user_certificates ucert ON u.id = ucert.user_id
            WHERE u.id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }
    
    public function getStudentCourseProgress($userId) {
        $stmt = $this->db->prepare("
            SELECT 
                c.id,
                c.title,
                COUNT(DISTINCT l.id) as total_lessons,
                COUNT(DISTINCT up.lesson_id) as completed_lessons,
                ROUND((COUNT(DISTINCT up.lesson_id) / COUNT(DISTINCT l.id)) * 100, 2) as progress_percent,
                uc.enrolled_at,
                uc.completed_at
            FROM user_courses uc
            INNER JOIN courses c ON uc.course_id = c.id
            LEFT JOIN modules m ON c.id = m.course_id
            LEFT JOIN lessons l ON m.id = l.module_id
            LEFT JOIN user_progress up ON l.id = up.lesson_id AND up.user_id = uc.user_id AND up.is_completed = 1
            WHERE uc.user_id = ?
            GROUP BY c.id, c.title, uc.enrolled_at, uc.completed_at
            ORDER BY uc.enrolled_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    public function getStudentTestResults($userId) {
        $stmt = $this->db->prepare("
            SELECT 
                t.title as test_name,
                l.title as lesson_name,
                c.title as course_name,
                utr.score,
                utr.passed,
                utr.completed_at
            FROM user_test_results utr
            INNER JOIN tests t ON utr.test_id = t.id
            INNER JOIN lessons l ON t.lesson_id = l.id
            INNER JOIN modules m ON l.module_id = m.id
            INNER JOIN courses c ON m.course_id = c.id
            WHERE utr.user_id = ?
            ORDER BY utr.completed_at DESC
            LIMIT 10
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    public function getStudentActivity($userId) {
        $stmt = $this->db->prepare("
            SELECT 
                DATE(up.completed_at) as date,
                COUNT(*) as lessons_completed
            FROM user_progress up
            WHERE up.user_id = ? AND up.is_completed = 1
            GROUP BY DATE(up.completed_at)
            ORDER BY date DESC
            LIMIT 30
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    public function getAllStudentsStats() {
        $stmt = $this->db->query("
            SELECT 
                u.id,
                CONCAT(u.first_name, ' ', u.last_name) as name,
                u.email,
                COUNT(DISTINCT uc.course_id) as enrolled_courses,
                COUNT(DISTINCT CASE WHEN uc.completed_at IS NOT NULL THEN uc.course_id END) as completed_courses,
                COALESCE(AVG(utr.score), 0) as avg_score,
                COUNT(DISTINCT ua.achievement_id) as achievements,
                COUNT(DISTINCT ucert.id) as certificates,
                MAX(up.completed_at) as last_activity
            FROM users u
            LEFT JOIN user_courses uc ON u.id = uc.user_id
            LEFT JOIN user_test_results utr ON u.id = utr.user_id
            LEFT JOIN user_achievements ua ON u.id = ua.user_id
            LEFT JOIN user_certificates ucert ON u.id = ucert.user_id
            LEFT JOIN user_progress up ON u.id = up.user_id
            WHERE u.role = 'student'
            GROUP BY u.id, u.first_name, u.last_name, u.email
            ORDER BY avg_score DESC
        ");
        return $stmt->fetchAll();
    }
    
    public function getCourseStats($courseId) {
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(DISTINCT uc.user_id) as total_students,
                COUNT(DISTINCT CASE WHEN uc.completed_at IS NOT NULL THEN uc.user_id END) as completed_students,
                ROUND(AVG(
                    (SELECT COUNT(*) FROM user_progress up2 
                     INNER JOIN lessons l2 ON up2.lesson_id = l2.id
                     INNER JOIN modules m2 ON l2.module_id = m2.id
                     WHERE m2.course_id = ? AND up2.user_id = uc.user_id AND up2.is_completed = 1)
                ), 2) as avg_lessons_completed
            FROM user_courses uc
            WHERE uc.course_id = ?
        ");
        $stmt->execute([$courseId, $courseId]);
        return $stmt->fetch();
    }
}

