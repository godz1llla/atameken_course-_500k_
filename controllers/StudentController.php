<?php

class StudentController extends Controller {
    
    public function __construct() {
        Session::requireRole('student');
    }
    
    public function dashboard() {
        $userCourseModel = $this->model('UserCourse');
        $achievementModel = $this->model('Achievement');
        $messageModel = $this->model('Message');
        
        $userId = Session::getUserId();
        $courses = $userCourseModel->getUserCourses($userId);
        
        foreach ($courses as &$course) {
            $course['progress'] = $userCourseModel->getCourseProgress($userId, $course['id']);
        }
        
        $achievements = $achievementModel->getUserAchievements($userId);
        $unreadMessages = $messageModel->getUnreadCount($userId);
        
        $data = [
            'courses' => $courses,
            'achievements' => $achievements,
            'unread_messages' => $unreadMessages
        ];
        
        $this->view('student/dashboard', $data);
    }
    
    public function course($id) {
        $courseModel = $this->model('Course');
        $moduleModel = $this->model('Module');
        $userCourseModel = $this->model('UserCourse');
        $userProgressModel = $this->model('UserProgress');
        $weeklyTestModel = $this->model('WeeklyTest');
        
        $userId = Session::getUserId();
        
        if (!$userCourseModel->isEnrolled($userId, $id)) {
            $this->redirect('student/dashboard');
            return;
        }
        
        $course = $courseModel->findById($id);
        $modules = $moduleModel->getByCourse($id);
        $weeklyTests = $weeklyTestModel->getByCourse($id);
        $mandatoryNotPassed = $weeklyTestModel->getMandatoryNotPassed($userId, $id);
        
        foreach ($modules as &$module) {
            $lessons = $courseModel->getLessons($module['id']);
            foreach ($lessons as &$lesson) {
                $lesson['is_completed'] = $userProgressModel->isCompleted($userId, $lesson['id']);
            }
            $module['lessons'] = $lessons;
        }
        
        foreach ($weeklyTests as &$test) {
            $test['is_passed'] = $weeklyTestModel->hasPassed($userId, $test['id']);
        }
        
        $progress = $userCourseModel->getCourseProgress($userId, $id);
        
        $data = [
            'course' => $course,
            'modules' => $modules,
            'progress' => $progress,
            'weekly_tests' => $weeklyTests,
            'mandatory_not_passed' => $mandatoryNotPassed
        ];
        
        $this->view('student/course', $data);
    }
    
    public function lesson($id) {
        $lessonModel = $this->model('Lesson');
        $moduleModel = $this->model('Module');
        $courseModel = $this->model('Course');
        $userCourseModel = $this->model('UserCourse');
        $userProgressModel = $this->model('UserProgress');
        $testModel = $this->model('Test');
        
        $userId = Session::getUserId();
        $lesson = $lessonModel->findById($id);
        $module = $moduleModel->findById($lesson['module_id']);
        $course = $courseModel->findById($module['course_id']);
        
        if (!$userCourseModel->isEnrolled($userId, $course['id'])) {
            $this->redirect('student/dashboard');
            return;
        }
        
        $youtubeEmbedUrl = null;
        if ($lesson['video_url']) {
            $youtubeEmbedUrl = $lessonModel->getYoutubeEmbedUrl($lesson['video_url']);
        }
        
        $test = $testModel->getByLesson($id);
        
        $allLessons = $lessonModel->getByModule($module['id']);
        $currentIndex = array_search($id, array_column($allLessons, 'id'));
        
        $prevLesson = $currentIndex > 0 ? $allLessons[$currentIndex - 1] : null;
        $nextLesson = $currentIndex < count($allLessons) - 1 ? $allLessons[$currentIndex + 1] : null;
        
        $data = [
            'lesson' => $lesson,
            'course' => $course,
            'module' => $module,
            'youtube_url' => $youtubeEmbedUrl,
            'test' => $test,
            'prev_lesson' => $prevLesson,
            'next_lesson' => $nextLesson,
            'is_completed' => $userProgressModel->isCompleted($userId, $id)
        ];
        
        $this->view('student/lesson', $data);
    }
    
    public function completeLesson($id) {
        $userProgressModel = $this->model('UserProgress');
        $lessonModel = $this->model('Lesson');
        $moduleModel = $this->model('Module');
        $courseModel = $this->model('Course');
        $userCourseModel = $this->model('UserCourse');
        $certificateModel = $this->model('Certificate');
        $achievementModel = $this->model('Achievement');
        
        $userId = Session::getUserId();
        $userProgressModel->markComplete($userId, $id);
        
        $lesson = $lessonModel->findById($id);
        $module = $moduleModel->findById($lesson['module_id']);
        $courseId = $module['course_id'];
        
        $progress = $userCourseModel->getCourseProgress($userId, $courseId);
        
        if ($progress == 100) {
            $userCourseModel->completeCourse($userId, $courseId);
            $certificateModel->issueCertificate($userId, $courseId);
            $achievementModel->checkAndAwardAchievements($userId);
        }
        
        $this->redirect('student/lesson/' . $id);
    }
    
    public function test($id) {
        $testModel = $this->model('Test');
        $test = $testModel->findById($id);
        
        $questions = $testModel->getQuestions($id);
        
        foreach ($questions as &$question) {
            $question['answers'] = $testModel->getAnswers($question['id']);
        }
        
        $userId = Session::getUserId();
        $results = $testModel->getUserResults($userId, $id);
        
        $data = [
            'test' => $test,
            'questions' => $questions,
            'results' => $results
        ];
        
        $this->view('student/test', $data);
    }
    
    public function submitTest($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('student/test/' . $id);
            return;
        }
        
        $testModel = $this->model('Test');
        $achievementModel = $this->model('Achievement');
        
        $test = $testModel->findById($id);
        $questions = $testModel->getQuestions($id);
        
        $totalQuestions = count($questions);
        $correctAnswers = 0;
        $userAnswers = [];
        
        foreach ($questions as $question) {
            $answers = $testModel->getAnswers($question['id']);
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
        $results = $testModel->getUserResults($userId, $id);
        $attemptNumber = count($results) + 1;
        
        $testModel->saveResult($userId, $id, $score, $passed, $userAnswers, $attemptNumber);
        
        $achievementModel->checkAndAwardAchievements($userId);
        
        $this->redirect('student/testResult/' . $id);
    }
    
    public function testResult($id) {
        $testModel = $this->model('Test');
        $userId = Session::getUserId();
        
        $test = $testModel->findById($id);
        $results = $testModel->getUserResults($userId, $id);
        
        if (empty($results)) {
            $this->redirect('student/test/' . $id);
            return;
        }
        
        $latestResult = $results[0];
        
        $data = [
            'test' => $test,
            'result' => $latestResult,
            'all_results' => $results
        ];
        
        $this->view('student/test_result', $data);
    }
    
    public function achievements() {
        $achievementModel = $this->model('Achievement');
        $userId = Session::getUserId();
        
        $allAchievements = $achievementModel->findAll();
        $userAchievements = $achievementModel->getUserAchievements($userId);
        
        $userAchievementIds = array_column($userAchievements, 'id');
        
        foreach ($allAchievements as &$achievement) {
            $achievement['earned'] = in_array($achievement['id'], $userAchievementIds);
        }
        
        $this->view('student/achievements', ['achievements' => $allAchievements]);
    }
    
    public function certificates() {
        $certificateModel = $this->model('Certificate');
        $userId = Session::getUserId();
        
        $certificates = $certificateModel->getUserCertificates($userId);
        
        $this->view('student/certificates', ['certificates' => $certificates]);
    }
    
    public function certificate($number) {
        $certificateModel = $this->model('Certificate');
        $certificate = $certificateModel->getCertificateByNumber($number);
        
        if (!$certificate) {
            $this->redirect('student/certificates');
            return;
        }
        
        $this->view('student/certificate_view', ['certificate' => $certificate]);
    }
    
    public function messages() {
        $messageModel = $this->model('Message');
        $userId = Session::getUserId();
        
        $conversations = $messageModel->getConversationsList($userId);
        
        $this->view('student/messages', ['conversations' => $conversations]);
    }
    
    public function myTeachers() {
        $userId = Session::getUserId();
        
        $userCourseModel = $this->model('UserCourse');
        $teachers = $userCourseModel->getTeachersForStudent($userId);
        
        $this->view('student/my_teachers', ['teachers' => $teachers]);
    }
    
    public function conversation($teacherId) {
        $messageModel = $this->model('Message');
        $userModel = $this->model('User');
        
        $userId = Session::getUserId();
        $teacher = $userModel->findById($teacherId);
        $messages = $messageModel->getConversation($userId, $teacherId);
        
        $messageModel->markAsRead($userId, $teacherId);
        
        $this->view('student/conversation', [
            'teacher' => $teacher,
            'messages' => $messages
        ]);
    }
    
    public function sendMessage() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $messageModel = $this->model('Message');
            
            $userId = Session::getUserId();
            $receiverId = $_POST['receiver_id'];
            $courseId = $_POST['course_id'] ?? null;
            $subject = $_POST['subject'] ?? '';
            $message = $_POST['message'];
            
            $messageModel->sendMessage($userId, $receiverId, $courseId, $subject, $message);
            
            $this->redirect('student/conversation/' . (int)$receiverId);
        }
        
        $this->redirect('student/messages');
    }
    
    public function statistics() {
        $statisticsModel = $this->model('Statistics');
        $userId = Session::getUserId();
        
        $stats = $statisticsModel->getStudentStats($userId);
        $courseProgress = $statisticsModel->getStudentCourseProgress($userId);
        $testResults = $statisticsModel->getStudentTestResults($userId);
        $activity = $statisticsModel->getStudentActivity($userId);
        
        $data = [
            'stats' => $stats,
            'course_progress' => $courseProgress,
            'test_results' => $testResults,
            'activity' => $activity
        ];
        
        $this->view('student/statistics', $data);
    }
    
    public function profile() {
        $userModel = $this->model('User');
        $userId = Session::getUserId();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'email' => $_POST['email']
            ];
            
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
                $data['avatar'] = $this->uploadFile($_FILES['avatar'], 'avatars');
            }
            
            if (!empty($_POST['new_password'])) {
                $userModel->updatePassword($userId, $_POST['new_password']);
            }
            
            $userModel->update($userId, $data);
            Session::set('user_name', $data['first_name'] . ' ' . $data['last_name']);
            
            $this->redirect('student/profile');
        } else {
            $user = $userModel->findById($userId);
            $this->view('student/profile', ['user' => $user]);
        }
    }
    
    public function schedule() {
        $this->view('student/schedule');
    }
    
    public function getMyScheduleEvents() {
        header('Content-Type: application/json');
        
        $scheduleModel = $this->model('Schedule');
        $userId = Session::getUserId();
        
        // Безопасность: используем ID студента исключительно из сессии
        $schedules = $scheduleModel->findAllForStudent($userId);
        
        $events = [];
        foreach ($schedules as $schedule) {
            $events[] = [
                'id' => $schedule['id'],
                'title' => ($schedule['title'] ? $schedule['title'] : $schedule['group_name']) . 
                          ($schedule['location'] ? ' (' . $schedule['location'] . ')' : ''),
                'start' => $schedule['start_time'],
                'end' => $schedule['end_time'],
                'groupId' => $schedule['group_id'],
                'groupName' => $schedule['group_name'] ?? '',
                'courseTitle' => $schedule['course_title'] ?? '',
                'teacherName' => $schedule['teacher_name'] ?? '',
                'location' => $schedule['location'] ?? '',
                'scheduleTitle' => $schedule['title'] ?? '',
                'allDay' => false
            ];
        }
        
        echo json_encode($events);
        exit;
    }
    
    public function getAttendanceStatus($scheduleId) {
        header('Content-Type: application/json');
        
        $attendanceModel = $this->model('Attendance');
        $userId = Session::getUserId();
        
        // Безопасность: используем ID студента исключительно из сессии
        $status = $attendanceModel->getStatus($scheduleId, $userId);
        
        echo json_encode([
            'status' => $status ?: null,
            'hasStatus' => $status !== null
        ]);
        exit;
    }
    
    private function uploadFile($file, $folder) {
        $uploadDir = UPLOAD_DIR . $folder . '/';
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $filepath = $uploadDir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return $folder . '/' . $filename;
        }
        
        return null;
    }
}

