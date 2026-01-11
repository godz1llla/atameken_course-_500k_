<?php

class Attendance extends Model {
    protected $table = 'attendance';
    
    /**
     * Получить посещаемость для конкретного занятия
     */
    public function getByScheduleId($scheduleId) {
        $stmt = $this->db->prepare("
            SELECT a.*, 
                   u.first_name,
                   u.last_name,
                   u.email
            FROM attendance a
            INNER JOIN users u ON a.student_id = u.id
            WHERE a.schedule_id = ?
            ORDER BY u.last_name, u.first_name
        ");
        $stmt->execute([$scheduleId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Получить статус посещаемости для студента на конкретном занятии
     */
    public function getStatus($scheduleId, $studentId) {
        $stmt = $this->db->prepare("SELECT status FROM {$this->table} WHERE schedule_id = ? AND student_id = ?");
        $stmt->execute([$scheduleId, $studentId]);
        $result = $stmt->fetch();
        return $result ? $result['status'] : null;
    }
    
    /**
     * Сохранить или обновить посещаемость
     */
    public function saveAttendance($scheduleId, $studentId, $status) {
        // Используем INSERT ... ON DUPLICATE KEY UPDATE для атомарной операции
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table} (schedule_id, student_id, status, marked_at)
            VALUES (?, ?, ?, NOW())
            ON DUPLICATE KEY UPDATE
            status = VALUES(status),
            marked_at = NOW()
        ");
        return $stmt->execute([$scheduleId, $studentId, $status]);
    }
    
    /**
     * Массовое сохранение посещаемости для занятия
     */
    public function saveBulkAttendance($scheduleId, $attendanceData) {
        // Начинаем транзакцию
        $this->db->beginTransaction();
        
        try {
            // Удаляем старые записи для этого занятия
            $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE schedule_id = ?");
            $stmt->execute([$scheduleId]);
            
            // Вставляем новые записи
            $stmt = $this->db->prepare("
                INSERT INTO {$this->table} (schedule_id, student_id, status, marked_at)
                VALUES (?, ?, ?, NOW())
            ");
            
            foreach ($attendanceData as $studentId => $status) {
                $stmt->execute([$scheduleId, $studentId, $status]);
            }
            
            // Подтверждаем транзакцию
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            // Откатываем транзакцию в случае ошибки
            $this->db->rollBack();
            return false;
        }
    }
    
    /**
     * Получить посещаемость для студента по всем занятиям
     */
    public function getByStudent($studentId) {
        $stmt = $this->db->prepare("
            SELECT a.*, 
                   s.title as schedule_title,
                   s.start_time,
                   s.end_time,
                   s.location,
                   g.name as group_name
            FROM attendance a
            INNER JOIN schedules s ON a.schedule_id = s.id
            INNER JOIN groups g ON s.group_id = g.id
            WHERE a.student_id = ?
            ORDER BY s.start_time DESC
        ");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Получить статистику посещаемости для группы
     */
    public function getStatisticsByGroup($groupId, $startDate = null, $endDate = null) {
        $query = "
            SELECT 
                a.student_id,
                u.first_name,
                u.last_name,
                COUNT(CASE WHEN a.status = 'present' THEN 1 END) as present_count,
                COUNT(CASE WHEN a.status = 'absent' THEN 1 END) as absent_count,
                COUNT(CASE WHEN a.status = 'late' THEN 1 END) as late_count,
                COUNT(*) as total_count
            FROM attendance a
            INNER JOIN schedules s ON a.schedule_id = s.id
            INNER JOIN users u ON a.student_id = u.id
            WHERE s.group_id = ?
        ";
        
        $params = [$groupId];
        
        if ($startDate) {
            $query .= " AND s.start_time >= ?";
            $params[] = $startDate;
        }
        
        if ($endDate) {
            $query .= " AND s.start_time <= ?";
            $params[] = $endDate;
        }
        
        $query .= " GROUP BY a.student_id, u.first_name, u.last_name";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

