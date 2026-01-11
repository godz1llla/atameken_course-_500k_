<?php

class HomeworkSubmission extends Model {
    protected $table = 'homework_submissions';
    
    /**
     * Создать новую сдачу домашнего задания (или обновить существующую)
     */
    public function submit($data) {
        // Проверяем, существует ли уже сдача от этого студента
        $existing = $this->findByHomeworkAndStudent($data['homework_id'], $data['student_id']);
        
        if ($existing) {
            // Обновляем существующую сдачу
            $updateData = [
                'submission_text' => $data['submission_text'] ?? null,
                'file_path' => $data['file_path'] ?? null,
                'status' => 'submitted', // Сбрасываем статус при повторной сдаче
                'grade' => null, // Сбрасываем оценку
                'teacher_comment' => null // Сбрасываем комментарий учителя
            ];
            
            // Определяем статус: если просрочено, ставим 'late'
            if (isset($data['due_date'])) {
                $now = new DateTime();
                $dueDate = new DateTime($data['due_date']);
                if ($now > $dueDate) {
                    $updateData['status'] = 'late';
                }
            }
            
            return $this->update($existing['id'], $updateData);
        } else {
            // Создаем новую сдачу
            $insertData = [
                'homework_id' => $data['homework_id'],
                'student_id' => $data['student_id'],
                'submission_text' => $data['submission_text'] ?? null,
                'file_path' => $data['file_path'] ?? null,
                'status' => 'submitted'
            ];
            
            // Определяем статус: если просрочено, ставим 'late'
            if (isset($data['due_date'])) {
                $now = new DateTime();
                $dueDate = new DateTime($data['due_date']);
                if ($now > $dueDate) {
                    $insertData['status'] = 'late';
                }
            }
            
            return $this->create($insertData);
        }
    }
    
    /**
     * Найти сдачу домашнего задания конкретного студента по конкретному ДЗ
     */
    public function findByHomeworkAndStudent($homeworkId, $studentId) {
        $stmt = $this->db->prepare("
            SELECT hs.*,
                   CONCAT(u.first_name, ' ', u.last_name) as student_name,
                   u.email as student_email,
                   h.title as homework_title,
                   h.due_date as homework_due_date
            FROM {$this->table} hs
            INNER JOIN users u ON hs.student_id = u.id
            INNER JOIN homeworks h ON hs.homework_id = h.id
            WHERE hs.homework_id = ? AND hs.student_id = ?
        ");
        $stmt->execute([$homeworkId, $studentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Получить все сдачи для конкретного домашнего задания (для учителя)
     */
    public function findAllForHomework($homeworkId) {
        $stmt = $this->db->prepare("
            SELECT hs.*,
                   CONCAT(u.first_name, ' ', u.last_name) as student_name,
                   u.email as student_email,
                   u.id as student_id,
                   h.title as homework_title,
                   h.due_date as homework_due_date
            FROM {$this->table} hs
            INNER JOIN users u ON hs.student_id = u.id
            INNER JOIN homeworks h ON hs.homework_id = h.id
            WHERE hs.homework_id = ?
            ORDER BY hs.submitted_at DESC
        ");
        $stmt->execute([$homeworkId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Получить все сдачи конкретного студента
     */
    public function findAllForStudent($studentId) {
        $stmt = $this->db->prepare("
            SELECT hs.*,
                   h.title as homework_title,
                   h.description as homework_description,
                   h.due_date as homework_due_date,
                   l.title as lesson_title,
                   m.title as module_title,
                   c.title as course_title
            FROM {$this->table} hs
            INNER JOIN homeworks h ON hs.homework_id = h.id
            INNER JOIN lessons l ON h.lesson_id = l.id
            INNER JOIN modules m ON l.module_id = m.id
            INNER JOIN courses c ON m.course_id = c.id
            WHERE hs.student_id = ?
            ORDER BY h.due_date DESC, hs.submitted_at DESC
        ");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Выставить оценку и комментарий учителем
     */
    public function grade($submissionId, $grade, $comment = null) {
        $updateData = [
            'grade' => (int)$grade,
            'teacher_comment' => $comment,
            'status' => 'graded'
        ];
        
        return $this->update($submissionId, $updateData);
    }
    
    /**
     * Запросить повторную сдачу
     */
    public function requestResubmit($submissionId, $comment = null) {
        $updateData = [
            'status' => 'resubmit',
            'teacher_comment' => $comment,
            'grade' => null // Сбрасываем оценку
        ];
        
        return $this->update($submissionId, $updateData);
    }
    
    /**
     * Получить сдачу по ID с полной информацией
     */
    public function findById($id) {
        $stmt = $this->db->prepare("
            SELECT hs.*,
                   CONCAT(u.first_name, ' ', u.last_name) as student_name,
                   u.email as student_email,
                   h.title as homework_title,
                   h.description as homework_description,
                   h.due_date as homework_due_date,
                   l.title as lesson_title,
                   m.title as module_title,
                   c.title as course_title
            FROM {$this->table} hs
            INNER JOIN users u ON hs.student_id = u.id
            INNER JOIN homeworks h ON hs.homework_id = h.id
            INNER JOIN lessons l ON h.lesson_id = l.id
            INNER JOIN modules m ON l.module_id = m.id
            INNER JOIN courses c ON m.course_id = c.id
            WHERE hs.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Получить статистику по сдачам для конкретного ДЗ
     */
    public function getStatisticsForHomework($homeworkId) {
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(*) as total_submissions,
                SUM(CASE WHEN status = 'submitted' THEN 1 ELSE 0 END) as submitted_count,
                SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as late_count,
                SUM(CASE WHEN status = 'graded' THEN 1 ELSE 0 END) as graded_count,
                SUM(CASE WHEN status = 'resubmit' THEN 1 ELSE 0 END) as resubmit_count,
                AVG(grade) as average_grade
            FROM {$this->table}
            WHERE homework_id = ?
        ");
        $stmt->execute([$homeworkId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

