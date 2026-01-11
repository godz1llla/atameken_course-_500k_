<?php

class TestController extends Controller {
    
    public function createLessonTest($lessonId) {
        Session::requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $testModel = $this->model('Test');
            
            $testId = $testModel->create([
                'lesson_id' => $lessonId,
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'passing_score' => $_POST['passing_score'] ?? 70,
                'time_limit' => $_POST['time_limit'] ?? null,
                'max_attempts' => $_POST['max_attempts'] ?? null
            ]);
            
            // Добавление вопросов
            if (isset($_POST['questions'])) {
                foreach ($_POST['questions'] as $index => $question) {
                    $questionId = $this->createQuestion($testModel, $testId, $question, $index);
                    
                    if (isset($question['answers'])) {
                        foreach ($question['answers'] as $answer) {
                            $this->createAnswer($testModel, $questionId, $answer);
                        }
                    }
                }
            }
            
            $this->json(['success' => true, 'test_id' => $testId]);
        }
    }
    
    public function createWeeklyTest($courseId) {
        Session::requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $weeklyTestModel = $this->model('WeeklyTest');
            
            $testId = $weeklyTestModel->create([
                'course_id' => $courseId,
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'week_number' => $_POST['week_number'],
                'passing_score' => $_POST['passing_score'] ?? 70,
                'time_limit' => $_POST['time_limit'] ?? null,
                'is_mandatory' => isset($_POST['is_mandatory']) ? 1 : 0,
                'deadline' => $_POST['deadline'] ?? null
            ]);
            
            // Добавление вопросов
            if (isset($_POST['questions'])) {
                foreach ($_POST['questions'] as $index => $question) {
                    $questionId = $weeklyTestModel->createQuestion(
                        $testId,
                        $question['text'],
                        $question['type'] ?? 'single_choice',
                        $index
                    );
                    
                    if (isset($question['answers'])) {
                        foreach ($question['answers'] as $answer) {
                            $weeklyTestModel->createAnswer(
                                $questionId,
                                $answer['text'],
                                isset($answer['is_correct']) ? 1 : 0
                            );
                        }
                    }
                }
            }
            
            $this->redirect('admin/manageCourse/' . $courseId);
        } else {
            $courseModel = $this->model('Course');
            $course = $courseModel->findById($courseId);
            
            $this->view('admin/weekly_test_form', ['course' => $course]);
        }
    }
    
    private function createQuestion($testModel, $testId, $questionData, $orderNum) {
        $stmt = $testModel->db->prepare("
            INSERT INTO test_questions (test_id, question, question_type, order_num) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([
            $testId,
            $questionData['text'],
            $questionData['type'] ?? 'single_choice',
            $orderNum
        ]);
        return $testModel->db->lastInsertId();
    }
    
    private function createAnswer($testModel, $questionId, $answerData) {
        $stmt = $testModel->db->prepare("
            INSERT INTO test_answers (question_id, answer_text, is_correct) 
            VALUES (?, ?, ?)
        ");
        return $stmt->execute([
            $questionId,
            $answerData['text'],
            isset($answerData['is_correct']) ? 1 : 0
        ]);
    }
}

