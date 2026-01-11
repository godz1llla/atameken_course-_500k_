-- Добавление недельных тестов

CREATE TABLE IF NOT EXISTS weekly_tests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    week_number INT NOT NULL,
    passing_score INT DEFAULT 70,
    time_limit INT DEFAULT NULL,
    is_mandatory TINYINT(1) DEFAULT 1,
    deadline TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS weekly_test_questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    weekly_test_id INT NOT NULL,
    question TEXT NOT NULL,
    question_type ENUM('multiple_choice', 'single_choice', 'text') DEFAULT 'single_choice',
    order_num INT DEFAULT 0,
    FOREIGN KEY (weekly_test_id) REFERENCES weekly_tests(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS weekly_test_answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT NOT NULL,
    answer_text TEXT NOT NULL,
    is_correct TINYINT(1) DEFAULT 0,
    FOREIGN KEY (question_id) REFERENCES weekly_test_questions(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS user_weekly_test_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    weekly_test_id INT NOT NULL,
    score INT NOT NULL,
    passed TINYINT(1) DEFAULT 0,
    attempt_number INT DEFAULT 1,
    answers JSON,
    completed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (weekly_test_id) REFERENCES weekly_tests(id) ON DELETE CASCADE
);

