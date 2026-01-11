<?php

class WeeklyTest extends Model {
    protected $table = 'weekly_tests';
    
    public function getByCourse($courseId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE course_id = ? ORDER BY week_number ASC");
        $stmt->execute([$courseId]);
        return $stmt->fetchAll();
    }
    
    public function getQuestions($weeklyTestId) {
        $stmt = $this->db->prepare("SELECT * FROM weekly_test_questions WHERE weekly_test_id = ? ORDER BY order_num");
        $stmt->execute([$weeklyTestId]);
        return $stmt->fetchAll();
    }
    
    public function getAnswers($questionId) {
        $stmt = $this->db->prepare("SELECT * FROM weekly_test_answers WHERE question_id = ?");
        $stmt->execute([$questionId]);
        return $stmt->fetchAll();
    }
    
    public function saveResult($userId, $weeklyTestId, $score, $passed, $answers, $attemptNumber) {
        $stmt = $this->db->prepare("
            INSERT INTO user_weekly_test_results (user_id, weekly_test_id, score, passed, answers, attempt_number) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$userId, $weeklyTestId, $score, $passed, json_encode($answers), $attemptNumber]);
    }
    
    public function getUserResults($userId, $weeklyTestId) {
        $stmt = $this->db->prepare("
            SELECT * FROM user_weekly_test_results 
            WHERE user_id = ? AND weekly_test_id = ? 
            ORDER BY completed_at DESC
        ");
        $stmt->execute([$userId, $weeklyTestId]);
        return $stmt->fetchAll();
    }
    
    public function hasPassed($userId, $weeklyTestId) {
        $stmt = $this->db->prepare("
            SELECT * FROM user_weekly_test_results 
            WHERE user_id = ? AND weekly_test_id = ? AND passed = 1
        ");
        $stmt->execute([$userId, $weeklyTestId]);
        return $stmt->fetch() !== false;
    }
    
    public function getMandatoryNotPassed($userId, $courseId) {
        $stmt = $this->db->prepare("
            SELECT wt.* 
            FROM weekly_tests wt
            WHERE wt.course_id = ? 
            AND wt.is_mandatory = 1
            AND wt.id NOT IN (
                SELECT weekly_test_id 
                FROM user_weekly_test_results 
                WHERE user_id = ? AND passed = 1
            )
            ORDER BY wt.week_number ASC
        ");
        $stmt->execute([$courseId, $userId]);
        return $stmt->fetchAll();
    }
    
    public function createQuestion($weeklyTestId, $question, $questionType, $orderNum) {
        $stmt = $this->db->prepare("
            INSERT INTO weekly_test_questions (weekly_test_id, question, question_type, order_num) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$weeklyTestId, $question, $questionType, $orderNum]);
        return $this->db->lastInsertId();
    }
    
    public function createAnswer($questionId, $answerText, $isCorrect) {
        $stmt = $this->db->prepare("
            INSERT INTO weekly_test_answers (question_id, answer_text, is_correct) 
            VALUES (?, ?, ?)
        ");
        return $stmt->execute([$questionId, $answerText, $isCorrect]);
    }
}

