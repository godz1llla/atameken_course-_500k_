-- Добавление роли 'manager' в таблицу users
ALTER TABLE users 
MODIFY COLUMN role ENUM('admin', 'teacher', 'student', 'manager') DEFAULT 'student';

