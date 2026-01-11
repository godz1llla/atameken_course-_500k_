<?php

class AdminController extends Controller {
    
    public function __construct() {
        Session::requireRole('admin');
    }
    
    public function dashboard() {
        $userModel = $this->model('User');
        $courseModel = $this->model('Course');
        
        $stats = [
            'total_users' => count($userModel->findAll()),
            'total_students' => count($userModel->getAllByRole('student')),
            'total_teachers' => count($userModel->getAllByRole('teacher')),
            'total_courses' => count($courseModel->findAll())
        ];
        
        $this->view('admin/dashboard', ['stats' => $stats]);
    }
    
    public function statistics() {
        // Только администратор может просматривать статистику
        Session::requireRole('admin');
        
        $statisticsModel = $this->model('Statistics');
        
        $studentsStats = $statisticsModel->getAllStudentsStats();
        
        $this->view('admin/statistics', ['students' => $studentsStats]);
    }
    
    public function users() {
        $userModel = $this->model('User');
        $users = $userModel->findAll();
        
        $this->view('admin/users', ['users' => $users]);
    }
    
    public function createUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = $this->model('User');
            $supportsRatePerClass = $userModel->hasRatePerClassColumn();
            
            $data = [
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'role' => $_POST['role']
            ];
            
            // Добавляем ставку только для учителей
            if ($supportsRatePerClass && $_POST['role'] === 'teacher' && isset($_POST['rate_per_class'])) {
                $data['rate_per_class'] = !empty($_POST['rate_per_class']) ? (float)$_POST['rate_per_class'] : null;
            }
            
            $userModel->createUser($data);
            $this->redirect('admin/users');
        } else {
            $this->view('admin/user_form');
        }
    }
    
    public function editUser($id) {
        $userModel = $this->model('User');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $supportsRatePerClass = $userModel->hasRatePerClassColumn();
            $data = [
                'email' => $_POST['email'],
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'role' => $_POST['role']
            ];
            
            // Добавляем ставку только для учителей
            if ($supportsRatePerClass && $_POST['role'] === 'teacher' && isset($_POST['rate_per_class'])) {
                $data['rate_per_class'] = !empty($_POST['rate_per_class']) ? (float)$_POST['rate_per_class'] : null;
            } elseif ($supportsRatePerClass) {
                // Для не-учителей очищаем ставку
                $data['rate_per_class'] = null;
            }
            
            if (!empty($_POST['password'])) {
                $userModel->updatePassword($id, $_POST['password']);
            }
            
            $userModel->update($id, $data);
            $this->redirect('admin/users');
        } else {
            $user = $userModel->findById($id);
            $this->view('admin/user_form', ['user' => $user]);
        }
    }
    
    public function deleteUser($id) {
        $userModel = $this->model('User');
        $userModel->delete($id);
        $this->redirect('admin/users');
    }
    
    public function toggleUserStatus($id) {
        $userModel = $this->model('User');
        $userModel->toggleActive($id);
        $this->redirect('admin/users');
    }
    
    public function courses() {
        $courseModel = $this->model('Course');
        $courses = $courseModel->getWithTeacher();
        
        $this->view('admin/courses', ['courses' => $courses]);
    }
    
    public function createCourse() {
        // Только администратор может создавать курсы
        Session::requireRole('admin');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $courseModel = $this->model('Course');
            
            $imagePath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $imagePath = $this->uploadFile($_FILES['image'], 'course_images');
            }
            
            $data = [
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'teacher_id' => $_POST['teacher_id'],
                'image' => $imagePath,
                'is_published' => isset($_POST['is_published']) ? 1 : 0
            ];
            
            $courseModel->create($data);
            $this->redirect('admin/courses');
        } else {
            $userModel = $this->model('User');
            $teachers = $userModel->getAllByRole('teacher');
            
            $this->view('admin/course_form', ['teachers' => $teachers]);
        }
    }
    
    public function editCourse($id) {
        // Только администратор может редактировать курсы
        Session::requireRole('admin');
        
        $courseModel = $this->model('Course');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'teacher_id' => $_POST['teacher_id'],
                'is_published' => isset($_POST['is_published']) ? 1 : 0
            ];
            
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $data['image'] = $this->uploadFile($_FILES['image'], 'course_images');
            }
            
            $courseModel->update($id, $data);
            $this->redirect('admin/courses');
        } else {
            $userModel = $this->model('User');
            $teachers = $userModel->getAllByRole('teacher');
            $course = $courseModel->findById($id);
            
            $this->view('admin/course_form', ['course' => $course, 'teachers' => $teachers]);
        }
    }
    
    public function deleteCourse($id) {
        // Только администратор может удалять курсы
        Session::requireRole('admin');
        
        $courseModel = $this->model('Course');
        $courseModel->delete($id);
        $this->redirect('admin/courses');
    }
    
    public function manageCourse($id) {
        $courseModel = $this->model('Course');
        $moduleModel = $this->model('Module');
        $weeklyTestModel = $this->model('WeeklyTest');
        $homeworkModel = $this->model('Homework');
        $homeworkSubmissionModel = $this->model('HomeworkSubmission');
        
        $course = $courseModel->findById($id);
        $modules = $moduleModel->getByCourse($id);
        $weeklyTests = $weeklyTestModel->getByCourse($id);
        
        // Получаем домашние задания для всех уроков
        $lessonsWithHomeworks = [];
        foreach ($modules as &$module) {
            $module['lessons'] = $courseModel->getLessons($module['id']);
            
            foreach ($module['lessons'] as $lesson) {
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
        
        $this->view('admin/manage_course', [
            'course' => $course, 
            'modules' => $modules,
            'weekly_tests' => $weeklyTests,
            'lessonsWithHomeworks' => $lessonsWithHomeworks ?? []
        ]);
    }
    
    public function createModule($courseId) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $moduleModel = $this->model('Module');
            
            $data = [
                'course_id' => $courseId,
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'order_num' => $_POST['order_num'] ?? 0
            ];
            
            $moduleModel->create($data);
            $this->redirect('admin/manageCourse/' . $courseId);
        }
    }
    
    public function deleteModule($id) {
        $moduleModel = $this->model('Module');
        $module = $moduleModel->findById($id);
        $courseId = $module['course_id'];
        
        $moduleModel->delete($id);
        $this->redirect('admin/manageCourse/' . $courseId);
    }
    
    public function createLesson($moduleId) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $lessonModel = $this->model('Lesson');
            $moduleModel = $this->model('Module');
            
            $data = [
                'module_id' => $moduleId,
                'title' => $_POST['title'],
                'content' => $_POST['content'],
                'video_url' => $_POST['video_url'] ?? null,
                'order_num' => $_POST['order_num'] ?? 0
            ];
            
            $lessonModel->create($data);
            
            $module = $moduleModel->findById($moduleId);
            $this->redirect('admin/manageCourse/' . $module['course_id']);
        }
    }
    
    public function editLesson($id) {
        $lessonModel = $this->model('Lesson');
        $moduleModel = $this->model('Module');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'],
                'content' => $_POST['content'],
                'video_url' => $_POST['video_url'] ?? null,
                'order_num' => $_POST['order_num'] ?? 0
            ];
            
            $lessonModel->update($id, $data);
            
            $lesson = $lessonModel->findById($id);
            $module = $moduleModel->findById($lesson['module_id']);
            $this->redirect('admin/manageCourse/' . $module['course_id']);
        }
    }
    
    public function deleteLesson($id) {
        $lessonModel = $this->model('Lesson');
        $moduleModel = $this->model('Module');
        
        $lesson = $lessonModel->findById($id);
        $module = $moduleModel->findById($lesson['module_id']);
        
        $lessonModel->delete($id);
        $this->redirect('admin/manageCourse/' . $module['course_id']);
    }
    
    public function achievements() {
        // Только администратор может управлять достижениями
        Session::requireRole('admin');
        
        $achievementModel = $this->model('Achievement');
        $achievements = $achievementModel->findAll();
        
        $this->view('admin/achievements', ['achievements' => $achievements]);
    }
    
    public function createAchievement() {
        // Только администратор может создавать достижения
        Session::requireRole('admin');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $achievementModel = $this->model('Achievement');
            
            $iconPath = null;
            if (isset($_FILES['icon']) && $_FILES['icon']['error'] === 0) {
                $iconPath = $this->uploadFile($_FILES['icon'], 'achievement_icons');
            }
            
            $data = [
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'icon' => $iconPath,
                'condition_type' => $_POST['condition_type'],
                'condition_value' => $_POST['condition_value']
            ];
            
            $achievementModel->create($data);
            $this->redirect('admin/achievements');
        } else {
            $this->view('admin/achievement_form');
        }
    }
    
    public function deleteAchievement($id) {
        // Только администратор может удалять достижения
        Session::requireRole('admin');
        
        $achievementModel = $this->model('Achievement');
        $achievementModel->delete($id);
        $this->redirect('admin/achievements');
    }
    
    public function enrollments() {
        // Только администратор может управлять зачислениями
        Session::requireRole('admin');
        
        $userModel = $this->model('User');
        $courseModel = $this->model('Course');
        
        $students = $userModel->getAllByRole('student');
        $courses = $courseModel->findAll();
        
        $this->view('admin/enrollments', ['students' => $students, 'courses' => $courses]);
    }
    
    public function enrollStudent() {
        // Только администратор может зачислять студентов
        Session::requireRole('admin');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userCourseModel = $this->model('UserCourse');
            
            $userId = $_POST['user_id'];
            $courseId = $_POST['course_id'];
            
            $userCourseModel->enroll($userId, $courseId);
        }
        
        $this->redirect('admin/enrollments');
    }
    
    public function groups() {
        $groupModel = $this->model('Group');
        $groups = $groupModel->findAll();
        
        $this->view('admin/groups', ['groups' => $groups]);
    }
    
    public function createGroup() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $groupModel = $this->model('Group');
            
            $data = [
                'name' => trim($_POST['name']),
                'course_id' => (int)$_POST['course_id'],
                'teacher_id' => !empty($_POST['teacher_id']) ? (int)$_POST['teacher_id'] : null
            ];
            
            if (empty($data['name']) || empty($data['course_id'])) {
                $this->redirect('admin/createGroup');
                return;
            }
            
            $groupModel->create($data);
            $this->redirect('admin/groups');
        } else {
            $courseModel = $this->model('Course');
            $userModel = $this->model('User');
            
            $courses = $courseModel->findAll();
            $teachers = $userModel->getAllByRole('teacher');
            
            $this->view('admin/group_form', ['courses' => $courses, 'teachers' => $teachers]);
        }
    }
    
    public function editGroup($id) {
        $groupModel = $this->model('Group');
        $courseModel = $this->model('Course');
        $userModel = $this->model('User');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => trim($_POST['name']),
                'course_id' => (int)$_POST['course_id'],
                'teacher_id' => !empty($_POST['teacher_id']) ? (int)$_POST['teacher_id'] : null
            ];
            
            if (empty($data['name'])) {
                $this->redirect('admin/groups');
                return;
            }
            
            $groupModel->update($id, $data);
            $this->redirect('admin/groups');
        } else {
            $group = $groupModel->findById($id);
            
            if (!$group) {
                $this->redirect('admin/groups');
                return;
            }
            
            $courses = $courseModel->findAll();
            $teachers = $userModel->getAllByRole('teacher');
            
            $this->view('admin/group_form', [
                'group' => $group, 
                'courses' => $courses, 
                'teachers' => $teachers
            ]);
        }
    }
    
    public function deleteGroup($id) {
        $groupModel = $this->model('Group');
        $groupModel->delete($id);
        $this->redirect('admin/groups');
    }
    
    public function groupDetails($id) {
        $groupModel = $this->model('Group');
        $groupMemberModel = $this->model('GroupMember');
        
        $group = $groupModel->findById($id);
        
        if (!$group) {
            $this->redirect('admin/groups');
            return;
        }
        
        // Обработка POST-запросов
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action'])) {
                if ($_POST['action'] === 'add_member' && !empty($_POST['student_id'])) {
                    $studentId = (int)$_POST['student_id'];
                    $groupMemberModel->addMember($id, $studentId);
                } elseif ($_POST['action'] === 'remove_member' && !empty($_POST['student_id'])) {
                    $studentId = (int)$_POST['student_id'];
                    $groupMemberModel->removeMember($id, $studentId);
                }
            }
            
            // Редирект на эту же страницу для перезагрузки
            $this->redirect('admin/groupDetails/' . $id);
            return;
        }
        
        // Получаем участников и не-участников
        $members = $groupMemberModel->getMembersByGroupId($id);
        $nonMembers = $groupMemberModel->getNonMembers($id);
        
        $this->view('admin/group_details', [
            'group' => $group,
            'members' => $members,
            'nonMembers' => $nonMembers
        ]);
    }
    
    public function schedule() {
        $groupModel = $this->model('Group');
        $groups = $groupModel->findAll();
        
        $this->view('admin/schedule', ['groups' => $groups]);
    }
    
    public function getScheduleEvents() {
        header('Content-Type: application/json');
        
        $scheduleModel = $this->model('Schedule');
        $schedules = $scheduleModel->findAll();
        
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
    
    public function addScheduleEvent() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
            exit;
        }
        
        $scheduleModel = $this->model('Schedule');
        
        $data = [
            'group_id' => (int)$_POST['group_id'],
            'title' => !empty($_POST['title']) ? trim($_POST['title']) : null,
            'start_time' => $_POST['start_time'],
            'end_time' => $_POST['end_time'],
            'location' => !empty($_POST['location']) ? trim($_POST['location']) : null
        ];
        
        // Валидация
        if (empty($data['group_id']) || empty($data['start_time']) || empty($data['end_time'])) {
            echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
            exit;
        }
        
        // Проверка, что время окончания больше времени начала
        if (strtotime($data['end_time']) <= strtotime($data['start_time'])) {
            echo json_encode(['status' => 'error', 'message' => 'End time must be after start time']);
            exit;
        }
        
        $scheduleId = $scheduleModel->create($data);
        $newSchedule = $scheduleModel->findById($scheduleId);
        
        $event = [
            'id' => $newSchedule['id'],
            'title' => ($newSchedule['title'] ? $newSchedule['title'] : $newSchedule['group_name']) . 
                      ($newSchedule['location'] ? ' (' . $newSchedule['location'] . ')' : ''),
            'start' => $newSchedule['start_time'],
            'end' => $newSchedule['end_time'],
            'groupId' => $newSchedule['group_id'],
            'groupName' => $newSchedule['group_name'] ?? '',
            'location' => $newSchedule['location'] ?? '',
            'allDay' => false
        ];
        
        echo json_encode(['status' => 'success', 'event' => $event]);
        exit;
    }
    
    public function subscriptionPlans() {
        // Только администратор может управлять тарифными планами
        Session::requireRole('admin');
        
        $subscriptionPlanModel = $this->model('SubscriptionPlan');
        $plans = $subscriptionPlanModel->findAll();
        
        $this->view('admin/subscription_plans', ['plans' => $plans]);
    }
    
    public function createPlan() {
        // Только администратор может создавать тарифные планы
        Session::requireRole('admin');
        
        $subscriptionPlanModel = $this->model('SubscriptionPlan');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => trim($_POST['name']),
                'description' => !empty($_POST['description']) ? trim($_POST['description']) : null,
                'price' => (float)$_POST['price'],
                'duration_days' => (int)$_POST['duration_days'],
                'lesson_count' => (int)$_POST['lesson_count'],
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];
            
            // Валидация
            if (empty($data['name']) || $data['price'] <= 0 || $data['duration_days'] <= 0 || $data['lesson_count'] < 0) {
                $this->redirect('admin/subscriptionPlans');
                return;
            }
            
            $subscriptionPlanModel->create($data);
            $this->redirect('admin/subscriptionPlans');
            return;
        }
        
        $this->view('admin/subscription_plan_form', ['plan' => null]);
    }
    
    public function editPlan($id) {
        // Только администратор может редактировать тарифные планы
        Session::requireRole('admin');
        
        $subscriptionPlanModel = $this->model('SubscriptionPlan');
        $plan = $subscriptionPlanModel->findById($id);
        
        if (!$plan) {
            $this->redirect('admin/subscriptionPlans');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => trim($_POST['name']),
                'description' => !empty($_POST['description']) ? trim($_POST['description']) : null,
                'price' => (float)$_POST['price'],
                'duration_days' => (int)$_POST['duration_days'],
                'lesson_count' => (int)$_POST['lesson_count'],
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];
            
            // Валидация
            if (empty($data['name']) || $data['price'] <= 0 || $data['duration_days'] <= 0 || $data['lesson_count'] < 0) {
                $this->redirect('admin/subscriptionPlans');
                return;
            }
            
            $subscriptionPlanModel->update($id, $data);
            $this->redirect('admin/subscriptionPlans');
            return;
        }
        
        $this->view('admin/subscription_plan_form', ['plan' => $plan]);
    }
    
    public function activatePlan($id) {
        // Только администратор может активировать тарифные планы
        Session::requireRole('admin');
        
        $subscriptionPlanModel = $this->model('SubscriptionPlan');
        $subscriptionPlanModel->activate($id);
        $this->redirect('admin/subscriptionPlans');
    }
    
    public function deactivatePlan($id) {
        // Только администратор может деактивировать тарифные планы
        Session::requireRole('admin');
        
        $subscriptionPlanModel = $this->model('SubscriptionPlan');
        $subscriptionPlanModel->deactivate($id);
        $this->redirect('admin/subscriptionPlans');
    }
    
    public function studentFinances($studentId) {
        // Только администратор может управлять финансами студентов
        Session::requireRole('admin');
        
        $userModel = $this->model('User');
        $studentSubscriptionModel = $this->model('StudentSubscription');
        $subscriptionPlanModel = $this->model('SubscriptionPlan');
        $paymentModel = $this->model('Payment');
        
        // Получаем данные студента
        $student = $userModel->findById($studentId);
        
        if (!$student || $student['role'] !== 'student') {
            $this->redirect('admin/users');
            return;
        }
        
        // Получаем активную подписку студента
        $activeSubscription = $studentSubscriptionModel->findActiveForStudent($studentId);
        
        // Получаем все активные тарифные планы для формы
        $plans = $subscriptionPlanModel->findAllActive();
        
        // Получаем историю платежей студента
        $payments = $paymentModel->findAllForStudent($studentId);
        
        $this->view('admin/student_finances', [
            'student' => $student,
            'activeSubscription' => $activeSubscription,
            'plans' => $plans,
            'payments' => $payments
        ]);
    }
    
    public function assignSubscription() {
        // Только администратор может назначать абонементы
        Session::requireRole('admin');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/users');
            return;
        }
        
        $studentSubscriptionModel = $this->model('StudentSubscription');
        $subscriptionPlanModel = $this->model('SubscriptionPlan');
        $userModel = $this->model('User');
        
        $studentId = (int)$_POST['student_id'];
        $planId = (int)$_POST['plan_id'];
        $startDate = $_POST['start_date'];
        
        // Валидация
        if (empty($studentId) || empty($planId) || empty($startDate)) {
            $this->redirect('admin/users');
            return;
        }
        
        // Проверяем, что пользователь - студент
        $student = $userModel->findById($studentId);
        if (!$student || $student['role'] !== 'student') {
            $this->redirect('admin/users');
            return;
        }
        
        // Проверяем, что у студента нет активного абонемента
        if ($studentSubscriptionModel->hasActiveSubscription($studentId)) {
            $this->redirect('admin/studentFinances/' . $studentId);
            return;
        }
        
        // Получаем данные плана
        $plan = $subscriptionPlanModel->findById($planId);
        if (!$plan || !$plan['is_active']) {
            $this->redirect('admin/studentFinances/' . $studentId);
            return;
        }
        
        // Рассчитываем дату окончания
        $startDateTime = new DateTime($startDate);
        $endDateTime = clone $startDateTime;
        $endDateTime->modify('+' . $plan['duration_days'] . ' days');
        $endDate = $endDateTime->format('Y-m-d');
        
        // Создаем подписку
        $data = [
            'student_id' => $studentId,
            'plan_id' => $planId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'lessons_remaining' => $plan['lesson_count'],
            'status' => 'active'
        ];
        
        $studentSubscriptionModel->create($data);
        
        $this->redirect('admin/studentFinances/' . $studentId);
    }
    
    public function addPayment() {
        // Только администратор может добавлять платежи
        Session::requireRole('admin');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/users');
            return;
        }
        
        $paymentModel = $this->model('Payment');
        $studentSubscriptionModel = $this->model('StudentSubscription');
        $subscriptionPlanModel = $this->model('SubscriptionPlan');
        $userModel = $this->model('User');
        
        $studentId = (int)$_POST['student_id'];
        $subscriptionId = (int)$_POST['student_subscription_id'];
        $amount = (float)$_POST['amount'];
        $paymentDate = $_POST['payment_date'];
        $paymentMethod = $_POST['payment_method'] ?? 'cash';
        $note = !empty($_POST['note']) ? trim($_POST['note']) : null;
        
        // Валидация
        if (empty($studentId) || empty($subscriptionId) || empty($amount) || $amount <= 0 || empty($paymentDate)) {
            $this->redirect('admin/studentFinances/' . $studentId);
            return;
        }
        
        // Проверяем, что пользователь - студент
        $student = $userModel->findById($studentId);
        if (!$student || $student['role'] !== 'student') {
            $this->redirect('admin/users');
            return;
        }
        
        // Проверяем, что подписка существует и принадлежит студенту
        $subscription = $studentSubscriptionModel->findById($subscriptionId);
        if (!$subscription || $subscription['student_id'] != $studentId) {
            $this->redirect('admin/studentFinances/' . $studentId);
            return;
        }
        
        // Проверяем, что метод оплаты валиден
        if (!in_array($paymentMethod, ['cash', 'card', 'transfer'])) {
            $paymentMethod = 'cash';
        }
        
        // Создаем платеж
        $data = [
            'student_id' => $studentId,
            'student_subscription_id' => $subscriptionId,
            'amount' => $amount,
            'payment_date' => $paymentDate,
            'payment_method' => $paymentMethod,
            'note' => $note
        ];
        
        $paymentModel->create($data);
        
        // Проверяем, оплачен ли абонемент полностью
        $plan = $subscriptionPlanModel->findById($subscription['plan_id']);
        $totalPaid = $paymentModel->getTotalAmountForSubscription($subscriptionId);
        
        if ($plan && $totalPaid >= $plan['price']) {
            // Если оплачено полностью, устанавливаем is_paid = 1
            $studentSubscriptionModel->update($subscriptionId, ['is_paid' => 1]);
        }
        
        $this->redirect('admin/studentFinances/' . $studentId);
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

