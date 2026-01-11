<?php

class TeacherController extends Controller {
    
    public function __construct() {
        Session::requireRole('teacher');
    }
    
    public function dashboard() {
        $courseModel = $this->model('Course');
        $messageModel = $this->model('Message');
        
        $userId = Session::getUserId();
        $courses = $courseModel->getByTeacher($userId);
        $unreadMessages = $messageModel->getUnreadCount($userId);
        
        $this->view('teacher/dashboard', [
            'courses' => $courses,
            'unread_messages' => $unreadMessages
        ]);
    }
    
    public function messages() {
        $messageModel = $this->model('Message');
        $userId = Session::getUserId();
        
        $conversations = $messageModel->getConversationsList($userId);
        
        $this->view('teacher/messages', ['conversations' => $conversations]);
    }
    
    public function conversation($studentId) {
        $messageModel = $this->model('Message');
        $userModel = $this->model('User');
        
        $userId = Session::getUserId();
        $student = $userModel->findById($studentId);
        $messages = $messageModel->getConversation($userId, $studentId);
        
        $messageModel->markAsRead($userId, $studentId);
        
        $this->view('teacher/conversation', [
            'student' => $student,
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

            $this->redirect('teacher/conversation/' . (int)$receiverId);
        }
        
        $this->redirect('teacher/messages');
    }
    
    public function courseStudents($courseId) {
        $courseModel = $this->model('Course');
        $userCourseModel = $this->model('UserCourse');
        $userId = Session::getUserId();
        
        $course = $courseModel->findById($courseId);
        
        if (!$course || $course['teacher_id'] != $userId) {
            $this->redirect('teacher/dashboard');
            return;
        }
        
        $students = $userCourseModel->getStudentsForCourse($courseId);
        
        foreach ($students as &$student) {
            $student['progress'] = $userCourseModel->getCourseProgress($student['id'], $courseId);
        }
        
        $this->view('teacher/course_students', [
            'course' => $course,
            'students' => $students
        ]);
    }
    
    public function manageCourse($id) {
        $courseModel = $this->model('Course');
        $moduleModel = $this->model('Module');
        $weeklyTestModel = $this->model('WeeklyTest');
        $homeworkModel = $this->model('Homework');
        $homeworkSubmissionModel = $this->model('HomeworkSubmission');
        
        $userId = Session::getUserId();
        $course = $courseModel->findById($id);
        
        if (!$course || $course['teacher_id'] != $userId) {
            $this->redirect('teacher/dashboard');
            return;
        }
        
        $modules = $moduleModel->getByCourse($id);
        $weeklyTests = $weeklyTestModel->getByCourse($id);
        
        $lessonsWithHomeworks = [];
        foreach ($modules as &$module) {
            $module['lessons'] = $courseModel->getLessons($module['id']);
            
            foreach ($module['lessons'] as &$lesson) {
                $homeworks = $homeworkModel->findByLessonId($lesson['id']);
                if (!empty($homeworks)) {
                    foreach ($homeworks as &$hw) {
                        $submissions = $homeworkSubmissionModel->findAllForHomework($hw['id']);
                        $hw['submissions_count'] = count($submissions);
                    }
                    $lessonsWithHomeworks[$lesson['id']] = $homeworks;
                }
            }
        }
        
        if (!isset($lessonsWithHomeworks)) {
            $lessonsWithHomeworks = [];
        }
        
        $this->view('admin/manage_course', [
            'course' => $course, 
            'modules' => $modules,
            'weekly_tests' => $weeklyTests,
            'lessonsWithHomeworks' => $lessonsWithHomeworks
        ]);
    }
    
    public function classDetails($scheduleId) {
        $scheduleModel = $this->model('Schedule');
        $groupMemberModel = $this->model('GroupMember');
        $attendanceModel = $this->model('Attendance');
        
        $userId = Session::getUserId();
        
        // Получаем информацию о занятии
        $schedule = $scheduleModel->findById($scheduleId);
        
        if (!$schedule) {
            $this->redirect('teacher/dashboard');
            return;
        }
        
        // Безопасность: проверяем, что учитель назначен на группу этого занятия
        if ($schedule['teacher_id'] != $userId) {
            $this->redirect('teacher/dashboard');
            return;
        }
        
        // Получаем список студентов группы
        $students = $groupMemberModel->getMembersByGroupId($schedule['group_id']);
        
        // Получаем уже сохраненную посещаемость
        $attendanceRecords = $attendanceModel->getByScheduleId($scheduleId);
        
        // Создаем массив для удобного доступа к статусам (используем user_id из students)
        $attendanceStatuses = [];
        foreach ($attendanceRecords as $record) {
            $attendanceStatuses[$record['student_id']] = $record['status'];
        }
        
        $this->view('teacher/class_details', [
            'schedule' => $schedule,
            'students' => $students,
            'attendanceStatuses' => $attendanceStatuses
        ]);
    }
    
    public function saveAttendance() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('teacher/dashboard');
            return;
        }
        
        $scheduleModel = $this->model('Schedule');
        $attendanceModel = $this->model('Attendance');
        
        $userId = Session::getUserId();
        $scheduleId = (int)$_POST['schedule_id'];
        
        // Получаем информацию о занятии для проверки доступа
        $schedule = $scheduleModel->findById($scheduleId);
        
        if (!$schedule) {
            $this->redirect('teacher/dashboard');
            return;
        }
        
        // Безопасность: проверяем, что учитель назначен на группу этого занятия
        if ($schedule['teacher_id'] != $userId) {
            $this->redirect('teacher/dashboard');
            return;
        }
        
        // Получаем данные о посещаемости из POST
        $attendanceData = [];
        if (isset($_POST['attendance']) && is_array($_POST['attendance'])) {
            foreach ($_POST['attendance'] as $studentId => $status) {
                $studentId = (int)$studentId;
                $status = in_array($status, ['present', 'absent', 'late']) ? $status : 'absent';
                $attendanceData[$studentId] = $status;
            }
        }
        
        // Сохраняем посещаемость через транзакцию
        if ($attendanceModel->saveBulkAttendance($scheduleId, $attendanceData)) {
            $this->redirect('teacher/classDetails/' . $scheduleId . '?success=1');
        } else {
            $this->redirect('teacher/classDetails/' . $scheduleId . '?error=1');
        }
    }
    
    public function schedule() {
        $scheduleModel = $this->model('Schedule');
        $userId = Session::getUserId();
        
        // Получаем занятия учителя через его группы
        $schedules = $scheduleModel->findAllForTeacher($userId);
        
        $this->view('teacher/schedule', ['schedules' => $schedules]);
    }
    
    public function getScheduleEvents() {
        header('Content-Type: application/json');
        
        $scheduleModel = $this->model('Schedule');
        $userId = Session::getUserId();
        
        // Получаем занятия учителя через его группы
        $schedules = $scheduleModel->findAllForTeacher($userId);
        
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
                'location' => $schedule['location'] ?? '',
                'allDay' => false
            ];
        }
        
        echo json_encode($events);
        exit;
    }
    
    /**
     * Проверить, что учитель имеет доступ к уроку (через курс)
     */
    private function checkLessonAccess($lessonId) {
        $lessonModel = $this->model('Lesson');
        $moduleModel = $this->model('Module');
        $courseModel = $this->model('Course');
        
        $userId = Session::getUserId();
        
        $lesson = $lessonModel->findById($lessonId);
        if (!$lesson) {
            return false;
        }
        
        $module = $moduleModel->findById($lesson['module_id']);
        if (!$module) {
            return false;
        }
        
        $course = $courseModel->findById($module['course_id']);
        if (!$course) {
            return false;
        }
        
        // Проверяем, что учитель назначен на этот курс
        return $course['teacher_id'] == $userId;
    }
    
    /**
     * Проверить, что учитель имеет доступ к домашнему заданию
     */
    private function checkHomeworkAccess($homeworkId) {
        $homeworkModel = $this->model('Homework');
        $homework = $homeworkModel->findById($homeworkId);
        
        if (!$homework) {
            return false;
        }
        
        return $this->checkLessonAccess($homework['lesson_id']);
    }
    
    /**
     * Проверить, что учитель имеет доступ к сдаче домашнего задания
     */
    private function checkSubmissionAccess($submissionId) {
        $submissionModel = $this->model('HomeworkSubmission');
        $submission = $submissionModel->findById($submissionId);
        
        if (!$submission) {
            return false;
        }
        
        return $this->checkHomeworkAccess($submission['homework_id']);
    }
    
    /**
     * Создать новое домашнее задание (форма)
     */
    public function createHomework($lessonId) {
        $lessonModel = $this->model('Lesson');
        $moduleModel = $this->model('Module');
        $courseModel = $this->model('Course');
        
        // Проверка доступа к уроку
        if (!$this->checkLessonAccess($lessonId)) {
            $this->redirect('teacher/dashboard');
            return;
        }
        
        $lesson = $lessonModel->findById($lessonId);
        $module = $moduleModel->findById($lesson['module_id']);
        $course = $courseModel->findById($module['course_id']);
        
        $this->view('teacher/homework_form', [
            'lesson' => $lesson,
            'module' => $module,
            'course' => $course,
            'homework' => null
        ]);
    }
    
    /**
     * Сохранить домашнее задание
     */
    public function saveHomework() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('teacher/dashboard');
            return;
        }
        
        $homeworkModel = $this->model('Homework');
        $lessonModel = $this->model('Lesson');
        $moduleModel = $this->model('Module');
        $courseModel = $this->model('Course');
        
        $lessonId = (int)$_POST['lesson_id'];
        
        // Проверка доступа к уроку
        if (!$this->checkLessonAccess($lessonId)) {
            $this->redirect('teacher/dashboard');
            return;
        }
        
        $data = [
            'lesson_id' => $lessonId,
            'title' => trim($_POST['title']),
            'description' => !empty($_POST['description']) ? trim($_POST['description']) : null,
            'due_date' => $_POST['due_date'] . ':00' // Добавляем секунды для DATETIME
        ];
        
        // Валидация
        if (empty($data['title']) || empty($data['due_date'])) {
            $this->redirect('teacher/createHomework/' . $lessonId);
            return;
        }
        
        if (isset($_POST['homework_id']) && !empty($_POST['homework_id'])) {
            // Редактирование существующего задания
            $homeworkId = (int)$_POST['homework_id'];
            
            if (!$this->checkHomeworkAccess($homeworkId)) {
                $this->redirect('teacher/dashboard');
                return;
            }
            
            $homeworkModel->update($homeworkId, $data);
        } else {
            // Создание нового задания
            $homeworkModel->create($data);
        }
        
        $lesson = $lessonModel->findById($lessonId);
        $module = $moduleModel->findById($lesson['module_id']);
        
        $this->redirect('teacher/manageCourse/' . $module['course_id']);
    }
    
    /**
     * Просмотр всех сдач по домашнему заданию
     */
    public function viewSubmissions($homeworkId) {
        $homeworkModel = $this->model('Homework');
        $submissionModel = $this->model('HomeworkSubmission');
        
        // Проверка доступа к домашнему заданию
        if (!$this->checkHomeworkAccess($homeworkId)) {
            $this->redirect('teacher/dashboard');
            return;
        }
        
        $homework = $homeworkModel->findById($homeworkId);
        $submissions = $submissionModel->findAllForHomework($homeworkId);
        
        $this->view('teacher/homework_submissions', [
            'homework' => $homework,
            'submissions' => $submissions
        ]);
    }
    
    /**
     * Страница оценки работы студента
     */
    public function gradeSubmission($submissionId) {
        $submissionModel = $this->model('HomeworkSubmission');
        $homeworkModel = $this->model('Homework');
        
        // Проверка доступа к сдаче
        if (!$this->checkSubmissionAccess($submissionId)) {
            $this->redirect('teacher/dashboard');
            return;
        }
        
        $submission = $submissionModel->findById($submissionId);
        $homework = $homeworkModel->findById($submission['homework_id']);
        
        $this->view('teacher/grade_submission', [
            'submission' => $submission,
            'homework' => $homework
        ]);
    }
    
    /**
     * Сохранить оценку работы студента
     */
    public function saveGrade() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('teacher/dashboard');
            return;
        }
        
        $submissionModel = $this->model('HomeworkSubmission');
        
        $submissionId = (int)$_POST['submission_id'];
        
        // Проверка доступа к сдаче
        if (!$this->checkSubmissionAccess($submissionId)) {
            $this->redirect('teacher/dashboard');
            return;
        }
        
        $grade = (int)$_POST['grade'];
        $comment = !empty($_POST['comment']) ? trim($_POST['comment']) : null;
        
        // Валидация оценки (например, от 0 до 100)
        if ($grade < 0 || $grade > 100) {
            $this->redirect('teacher/gradeSubmission/' . $submissionId);
            return;
        }
        
        $submissionModel->grade($submissionId, $grade, $comment);
        
        $submission = $submissionModel->findById($submissionId);
        
        $this->redirect('teacher/viewSubmissions/' . $submission['homework_id']);
    }
    
    /**
     * Безопасная отдача файла студента
     */
    public function downloadFile($submissionId) {
        $submissionModel = $this->model('HomeworkSubmission');
        
        // Проверка доступа к сдаче
        if (!$this->checkSubmissionAccess($submissionId)) {
            http_response_code(403);
            die('Access denied');
        }
        
        $submission = $submissionModel->findById($submissionId);
        
        if (empty($submission['file_path'])) {
            http_response_code(404);
            die('File not found');
        }
        
        // Формируем полный путь к файлу
        // file_path в БД хранится как 'homework_submissions/filename.ext'
        $filePath = UPLOAD_DIR . $submission['file_path'];
        
        if (!file_exists($filePath)) {
            http_response_code(404);
            die('File not found');
        }
        
        // Определяем MIME-тип
        $mimeType = mime_content_type($filePath);
        if (!$mimeType) {
            $mimeType = 'application/octet-stream';
        }
        
        // Отдаем файл
        header('Content-Type: ' . $mimeType);
        header('Content-Disposition: attachment; filename="' . basename($submission['file_path']) . '"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    }
    
    /**
     * Отчеты учителя
     */
    public function reports() {
        $scheduleModel = $this->model('Schedule');
        $userModel = $this->model('User');
        
        $userId = Session::getUserId();
        
        // Получаем ставку учителя
        $teacher = $userModel->findById($userId);
        $ratePerClass = $teacher['rate_per_class'] ?? 0;
        
        // Обработка фильтра дат
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['start_date']) && isset($_POST['end_date'])) {
            $startDate = $_POST['start_date'] . ' 00:00:00';
            $endDate = $_POST['end_date'] . ' 23:59:59';
        } else {
            // По умолчанию: текущий месяц
            $startDate = date('Y-m-01') . ' 00:00:00';
            $endDate = date('Y-m-t') . ' 23:59:59';
        }
        
        // Получаем данные отчетов
        $reportData = $scheduleModel->getTeacherReportData($userId, $startDate, $endDate);
        
        // Рассчитываем зарплату
        $salary = $reportData['total_classes'] * $ratePerClass;
        
        $this->view('teacher/reports', [
            'reportData' => $reportData,
            'ratePerClass' => $ratePerClass,
            'salary' => $salary,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'teacher' => $teacher
        ]);
    }
}

