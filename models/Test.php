<?php

class Test extends Model {
    protected $table = 'tests';
    
    public function getByLesson($lessonId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE lesson_id = ?");
        $stmt->execute([$lessonId]);
        return $stmt->fetch();
    }
    
    public function getQuestions($testId) {
        $stmt = $this->db->prepare("SELECT * FROM test_questions WHERE test_id = ? ORDER BY order_num");
        $stmt->execute([$testId]);
        return $stmt->fetchAll();
    }
    
    public function getAnswers($questionId) {
        $stmt = $this->db->prepare("SELECT * FROM test_answers WHERE question_id = ?");
        $stmt->execute([$questionId]);
        return $stmt->fetchAll();
    }
    
    public function saveResult($userId, $testId, $score, $passed, $answers, $attemptNumber) {
        $stmt = $this->db->prepare("
            INSERT INTO user_test_results (user_id, test_id, score, passed, answers, attempt_number) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$userId, $testId, $score, $passed, json_encode($answers), $attemptNumber]);
    }
    
    public function getUserResults($userId, $testId) {
        $stmt = $this->db->prepare("
            SELECT * FROM user_test_results 
            WHERE user_id = ? AND test_id = ? 
            ORDER BY completed_at DESC
        ");
        $stmt->execute([$userId, $testId]);
        return $stmt->fetchAll();
    }
}

