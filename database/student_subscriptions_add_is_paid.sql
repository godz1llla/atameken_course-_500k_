-- Добавление поля is_paid в таблицу student_subscriptions
ALTER TABLE student_subscriptions 
ADD COLUMN is_paid TINYINT(1) DEFAULT 0 AFTER status;

