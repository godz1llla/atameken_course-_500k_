<?php

class AuthController extends Controller {
    
    public function index() {
        if (Session::isLoggedIn()) {
            $this->redirectByRole();
        } else {
            $this->view('landing/index');
        }
    }
    
    public function login() {
        if (Session::isLoggedIn()) {
            $this->redirectByRole();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $userModel = $this->model('User');
            $user = $userModel->findByEmail($email);
            
            if ($user && password_verify($password, $user['password']) && $user['is_active']) {
                Session::set('user_id', $user['id']);
                Session::set('user_role', $user['role']);
                Session::set('user_name', $user['first_name'] . ' ' . $user['last_name']);
                
                $this->redirectByRole();
            } else {
                $error = 'Invalid credentials';
                $this->view('auth/login', ['error' => $error]);
            }
        } else {
            $this->view('auth/login');
        }
    }
    
    public function register() {
        if (Session::isLoggedIn()) {
            $this->redirectByRole();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $firstName = $_POST['first_name'] ?? '';
            $lastName = $_POST['last_name'] ?? '';
            
            $userModel = $this->model('User');
            
            if ($userModel->findByEmail($email)) {
                $error = 'Email already exists';
                $this->view('auth/register', ['error' => $error]);
                return;
            }
            
            $userId = $userModel->createUser([
                'email' => $email,
                'password' => $password,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'role' => 'student'
            ]);
            
            if ($userId) {
                Session::set('user_id', $userId);
                Session::set('user_role', 'student');
                Session::set('user_name', $firstName . ' ' . $lastName);
                
                $this->redirect('student/dashboard');
            }
        } else {
            $this->view('auth/register');
        }
    }
    
    public function logout() {
        Session::destroy();
        $this->redirect('auth/login');
    }
    
    private function redirectByRole() {
        $role = Session::getUserRole();
        
        switch ($role) {
            case 'admin':
                $this->redirect('admin/dashboard');
                break;
            case 'teacher':
                $this->redirect('teacher/dashboard');
                break;
            case 'student':
                $this->redirect('student/dashboard');
                break;
            default:
                $this->redirect('auth/login');
        }
    }
}

