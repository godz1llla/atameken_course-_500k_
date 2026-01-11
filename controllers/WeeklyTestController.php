<?php

class WeeklyTestController extends Controller {
    
    public function take($id) {
        Session::requireLogin();
        
        $weeklyTestModel = $this->model('WeeklyTest');
        $test = $weeklyTestModel->findById($id);
        
        $questions = $weeklyTestModel->getQuestions($id);
        
        foreach ($questions as &$question) {
            $question['answers'] = $weeklyTestModel->getAnswers($question['id']);
        }
        
        $userId = Session::getUserId();
        $results = $weeklyTestModel->getUserResults($userId, $id);
        
        $data = [
            'test' => $test,
            'questions' => $questions,
            'results' => $results
        ];
        
        $this->view('student/weekly_test', $data);
    }
    
    public function submit($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('weeklyTest/take/' . $id);
            return;
        }
        
        $weeklyTestModel = $this->model('WeeklyTest');
        $achievementModel = $this->model('Achievement');
        
        $test = $weeklyTestModel->findById($id);
        $questions = $weeklyTestModel->getQuestions($id);
        
        $totalQuestions = count($questions);
        $correctAnswers = 0;
        $userAnswers = [];
        
        foreach ($questions as $question) {
            $answers = $weeklyTestModel->getAnswers($question['id']);
            $userAnswer = $_POST['question_' . $question['id']] ?? null;
            
            $userAnswers[$question['id']] = $userAnswer;
            
            foreach ($answers as $answer) {
                if ($answer['is_correct'] && $answer['id'] == $userAnswer) {
                    $correctAnswers++;
                }
            }
        }
        
        $score = round(($correctAnswers / $totalQuestions) * 100);
        $passed = $score >= $test['passing_score'];
        
        $userId = Session::getUserId();
        $results = $weeklyTestModel->getUserResults($userId, $id);
        $attemptNumber = count($results) + 1;
        
        $weeklyTestModel->saveResult($userId, $id, $score, $passed, $userAnswers, $attemptNumber);
        
        $achievementModel->checkAndAwardAchievements($userId);
        
        $this->redirect('weeklyTest/result/' . $id);
    }
    
    public function result($id) {
        $weeklyTestModel = $this->model('WeeklyTest');
        $userId = Session::getUserId();
        
        $test = $weeklyTestModel->findById($id);
        $results = $weeklyTestModel->getUserResults($userId, $id);
        
        if (empty($results)) {
            $this->redirect('weeklyTest/take/' . $id);
            return;
        }
        
        $latestResult = $results[0];
        
        $data = [
            'test' => $test,
            'result' => $latestResult,
            'all_results' => $results
        ];
        
        $this->view('student/weekly_test_result', $data);
    }
}

