-- Добавление поля rate_per_class (ставка за занятие) в таблицу users
ALTER TABLE users 
ADD COLUMN rate_per_class DECIMAL(10, 2) DEFAULT NULL AFTER updated_at;

