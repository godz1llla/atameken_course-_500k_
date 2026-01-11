<?php

class Homework extends Model {
    protected $table = 'homeworks';
    
    /**
     * Получить все домашние задания для конкретного урока
     */
    public function findByLessonId($lessonId) {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE lesson_id = ? 
            ORDER BY due_date ASC, created_at DESC
        ");
        $stmt->execute([$lessonId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Получить домашнее задание по ID с информацией об уроке
     */
    public function findById($id) {
        $stmt = $this->db->prepare("
            SELECT h.*,
                   l.title as lesson_title,
                   l.module_id,
                   m.title as module_title,
                   m.course_id,
                   c.title as course_title
            FROM {$this->table} h
            INNER JOIN lessons l ON h.lesson_id = l.id
            INNER JOIN modules m ON l.module_id = m.id
            INNER JOIN courses c ON m.course_id = c.id
            WHERE h.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Получить все домашние задания для курса (через уроки)
     */
    public function findByCourseId($courseId) {
        $stmt = $this->db->prepare("
            SELECT h.*,
                   l.title as lesson_title,
                   l.module_id,
                   m.title as module_title
            FROM {$this->table} h
            INNER JOIN lessons l ON h.lesson_id = l.id
            INNER JOIN modules m ON l.module_id = m.id
            WHERE m.course_id = ?
            ORDER BY h.due_date ASC, h.created_at DESC
        ");
        $stmt->execute([$courseId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Получить все домашние задания для студента (только те, к которым он имеет доступ через курс)
     */
    public function findForStudent($studentId) {
        $stmt = $this->db->prepare("
            SELECT h.*,
                   l.title as lesson_title,
                   m.title as module_title,
                   c.title as course_title
            FROM {$this->table} h
            INNER JOIN lessons l ON h.lesson_id = l.id
            INNER JOIN modules m ON l.module_id = m.id
            INNER JOIN courses c ON m.course_id = c.id
            INNER JOIN user_courses uc ON c.id = uc.course_id
            WHERE uc.user_id = ?
            ORDER BY h.due_date ASC, h.created_at DESC
        ");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Создать новое домашнее задание
     */
    public function create($data) {
        return parent::create($data);
    }
    
    /**
     * Обновить домашнее задание
     */
    public function update($id, $data) {
        return parent::update($id, $data);
    }
    
    /**
     * Удалить домашнее задание
     */
    public function delete($id) {
        return parent::delete($id);
    }
    
    /**
     * Получить все домашние задания с информацией об уроке
     */
    public function findAll() {
        $stmt = $this->db->query("
            SELECT h.*,
                   l.title as lesson_title,
                   l.module_id,
                   m.title as module_title,
                   m.course_id,
                   c.title as course_title
            FROM {$this->table} h
            INNER JOIN lessons l ON h.lesson_id = l.id
            INNER JOIN modules m ON l.module_id = m.id
            INNER JOIN courses c ON m.course_id = c.id
            ORDER BY h.due_date ASC, h.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

