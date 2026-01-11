<?php

class Achievement extends Model {
    protected $table = 'achievements';
    
    public function getUserAchievements($userId) {
        $stmt = $this->db->prepare("
            SELECT a.*, ua.earned_at 
            FROM achievements a 
            INNER JOIN user_achievements ua ON a.id = ua.achievement_id 
            WHERE ua.user_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    public function awardAchievement($userId, $achievementId) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO user_achievements (user_id, achievement_id) 
                VALUES (?, ?)
            ");
            return $stmt->execute([$userId, $achievementId]);
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function checkAndAwardAchievements($userId) {
        $achievements = $this->findAll();
        
        foreach ($achievements as $achievement) {
            if ($this->hasAchievement($userId, $achievement['id'])) {
                continue;
            }
            
            if ($this->checkCondition($userId, $achievement)) {
                $this->awardAchievement($userId, $achievement['id']);
            }
        }
    }
    
    public function hasAchievement($userId, $achievementId) {
        $stmt = $this->db->prepare("
            SELECT * FROM user_achievements 
            WHERE user_id = ? AND achievement_id = ?
        ");
        $stmt->execute([$userId, $achievementId]);
        return $stmt->fetch() !== false;
    }
    
    private function checkCondition($userId, $achievement) {
        switch ($achievement['condition_type']) {
            case 'course_complete':
                $stmt = $this->db->prepare("
                    SELECT COUNT(*) as count 
                    FROM user_courses 
                    WHERE user_id = ? AND course_id = ? AND completed_at IS NOT NULL
                ");
                $stmt->execute([$userId, $achievement['condition_value']]);
                $result = $stmt->fetch();
                return $result['count'] > 0;
                
            case 'courses_count':
                $stmt = $this->db->prepare("
                    SELECT COUNT(*) as count 
                    FROM user_courses 
                    WHERE user_id = ? AND completed_at IS NOT NULL
                ");
                $stmt->execute([$userId]);
                $result = $stmt->fetch();
                return $result['count'] >= intval($achievement['condition_value']);
                
            case 'test_score':
                $stmt = $this->db->prepare("
                    SELECT MAX(score) as max_score 
                    FROM user_test_results 
                    WHERE user_id = ?
                ");
                $stmt->execute([$userId]);
                $result = $stmt->fetch();
                return $result['max_score'] >= intval($achievement['condition_value']);
                
            case 'perfect_test':
                $stmt = $this->db->prepare("
                    SELECT COUNT(*) as count 
                    FROM user_test_results 
                    WHERE user_id = ? AND score = 100
                ");
                $stmt->execute([$userId]);
                $result = $stmt->fetch();
                return $result['count'] > 0;
        }
        
        return false;
    }
}

