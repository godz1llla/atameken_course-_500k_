<?php

class Session {
    
    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }
    
    public static function get($key, $default = null) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }
    
    public static function has($key) {
        return isset($_SESSION[$key]);
    }
    
    public static function remove($key) {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    
    public static function destroy() {
        session_destroy();
    }
    
    public static function isLoggedIn() {
        return self::has('user_id');
    }
    
    public static function getUserId() {
        return self::get('user_id');
    }
    
    public static function getUserRole() {
        return self::get('user_role');
    }
    
    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header('Location: /index.php?url=auth/login');
            exit();
        }
    }
    
    public static function requireRole($role) {
        self::requireLogin();
        
        // Поддержка массива ролей для общих страниц
        if (is_array($role)) {
            if (!in_array(self::getUserRole(), $role)) {
                header('Location: /index.php?url=auth/login');
                exit();
            }
        } else {
            // Обратная совместимость: одна роль
            if (self::getUserRole() !== $role) {
                header('Location: /index.php?url=auth/login');
                exit();
            }
        }
    }
}

