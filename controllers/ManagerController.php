<?php

class ManagerController extends Controller {
    
    public function __construct() {
        Session::requireLogin();
        Session::requireRole('manager'); // Доступ только для менеджеров
    }
    
    public function dashboard() {
        $userModel = $this->model('User');
        $groupModel = $this->model('Group');
        
        $stats = [
            'total_students' => count($userModel->getAllByRole('student')),
            'total_groups' => count($groupModel->findAll())
        ];
        
        $this->view('manager/dashboard', ['stats' => $stats]);
    }
    
    /**
     * Список студентов (только студенты, не все пользователи)
     */
    public function users() {
        $userModel = $this->model('User');
        // Менеджер видит только студентов
        $users = $userModel->getAllByRole('student');
        
        $this->view('admin/users', ['users' => $users]);
    }
    
    /**
     * Создать нового студента
     */
    public function createUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = $this->model('User');
            
            $data = [
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'role' => 'student' // Менеджер может создавать только студентов
            ];
            
            $userModel->createUser($data);
            $this->redirect('manager/users');
        } else {
            $this->view('admin/user_form', ['user' => null, 'is_manager' => true]);
        }
    }
    
    /**
     * Редактировать студента
     */
    public function editUser($id) {
        $userModel = $this->model('User');
        $user = $userModel->findById($id);
        
        // Менеджер может редактировать только студентов
        if (!$user || $user['role'] !== 'student') {
            $this->redirect('manager/users');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'email' => $_POST['email'],
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'role' => 'student' // Менеджер не может менять роль
            ];
            
            if (!empty($_POST['password'])) {
                $userModel->updatePassword($id, $_POST['password']);
            }
            
            $userModel->update($id, $data);
            $this->redirect('manager/users');
        } else {
            $this->view('admin/user_form', ['user' => $user, 'is_manager' => true]);
        }
    }
    
    /**
     * Удалить студента
     */
    public function deleteUser($id) {
        $userModel = $this->model('User');
        $user = $userModel->findById($id);
        
        // Менеджер может удалять только студентов
        if (!$user || $user['role'] !== 'student') {
            $this->redirect('manager/users');
            return;
        }
        
        $userModel->delete($id);
        $this->redirect('manager/users');
    }
    
    /**
     * Переключить статус студента (активен/неактивен)
     */
    public function toggleUserStatus($id) {
        $userModel = $this->model('User');
        $user = $userModel->findById($id);
        
        // Менеджер может менять статус только студентов
        if (!$user || $user['role'] !== 'student') {
            $this->redirect('manager/users');
            return;
        }
        
        $userModel->toggleActive($id);
        $this->redirect('manager/users');
    }
    
    /**
     * Список групп
     */
    public function groups() {
        $groupModel = $this->model('Group');
        $groups = $groupModel->findAll();
        
        $this->view('admin/groups', ['groups' => $groups, 'is_manager' => true]);
    }
    
    /**
     * Создать новую группу
     */
    public function createGroup() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $groupModel = $this->model('Group');
            
            $data = [
                'name' => trim($_POST['name']),
                'course_id' => (int)$_POST['course_id'],
                'teacher_id' => !empty($_POST['teacher_id']) ? (int)$_POST['teacher_id'] : null
            ];
            
            if (empty($data['name']) || empty($data['course_id'])) {
                $this->redirect('manager/createGroup');
                return;
            }
            
            $groupModel->create($data);
            $this->redirect('manager/groups');
        } else {
            $courseModel = $this->model('Course');
            $userModel = $this->model('User');
            
            $courses = $courseModel->findAll();
            $teachers = $userModel->getAllByRole('teacher');
            
            $this->view('admin/group_form', ['courses' => $courses, 'teachers' => $teachers, 'is_manager' => true]);
        }
    }
    
    /**
     * Редактировать группу
     */
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
            
            if (empty($data['name']) || empty($data['course_id'])) {
                $this->redirect('manager/editGroup/' . $id);
                return;
            }
            
            $groupModel->update($id, $data);
            $this->redirect('manager/groups');
        } else {
            $group = $groupModel->findById($id);
            
            if (!$group) {
                $this->redirect('manager/groups');
                return;
            }
            
            $courses = $courseModel->findAll();
            $teachers = $userModel->getAllByRole('teacher');
            
            $this->view('admin/group_form', ['group' => $group, 'courses' => $courses, 'teachers' => $teachers, 'is_manager' => true]);
        }
    }
    
    /**
     * Удалить группу
     */
    public function deleteGroup($id) {
        $groupModel = $this->model('Group');
        $groupModel->delete($id);
        $this->redirect('manager/groups');
    }
    
    /**
     * Детали группы (управление участниками)
     */
    public function groupDetails($id) {
        $groupModel = $this->model('Group');
        $groupMemberModel = $this->model('GroupMember');
        
        $group = $groupModel->findById($id);
        
        if (!$group) {
            $this->redirect('manager/groups');
            return;
        }
        
        // Обработка POST-запросов
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action']) && $_POST['action'] === 'add_member') {
                $studentId = (int)$_POST['student_id'];
                $groupMemberModel->addMember($id, $studentId);
                $this->redirect('manager/groupDetails/' . $id);
                return;
            } elseif (isset($_POST['action']) && $_POST['action'] === 'remove_member') {
                $studentId = (int)$_POST['student_id'];
                $groupMemberModel->removeMember($id, $studentId);
                $this->redirect('manager/groupDetails/' . $id);
                return;
            }
        }
        
        $members = $groupMemberModel->getMembersByGroupId($id);
        $nonMembers = $groupMemberModel->getNonMembers($id);
        
        $this->view('admin/group_details', [
            'group' => $group,
            'members' => $members,
            'nonMembers' => $nonMembers,
            'is_manager' => true
        ]);
    }
}

