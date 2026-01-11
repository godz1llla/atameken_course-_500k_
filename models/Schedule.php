<?php

class Schedule extends Model {
    protected $table = 'schedules';
    
    /**
     * Получить все занятия с дополнительной информацией
     */
    public function findAll() {
        $stmt = $this->db->query("
            SELECT s.*, 
                   g.name as group_name,
                   g.id as group_id,
                   c.title as course_title,
                   CONCAT(u.first_name, ' ', u.last_name) as teacher_name
            FROM schedules s
            INNER JOIN groups g ON s.group_id = g.id
            LEFT JOIN courses c ON g.course_id = c.id
            LEFT JOIN users u ON g.teacher_id = u.id
            ORDER BY s.start_time ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Создать новое занятие в расписании
     */
    public function create($data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $stmt = $this->db->prepare("INSERT INTO {$this->table} ($columns) VALUES ($placeholders)");
        $stmt->execute(array_values($data));
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Получить все занятия для конкретной группы
     */
    public function findAllForGroup($groupId) {
        $stmt = $this->db->prepare("
            SELECT s.*, 
                   g.name as group_name,
                   c.title as course_title
            FROM schedules s
            INNER JOIN groups g ON s.group_id = g.id
            LEFT JOIN courses c ON g.course_id = c.id
            WHERE s.group_id = ?
            ORDER BY s.start_time ASC
        ");
        $stmt->execute([$groupId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Получить все занятия для конкретного студента (через JOIN таблиц group_members и groups)
     */
    public function findAllForStudent($studentId) {
        $stmt = $this->db->prepare("
            SELECT s.*, 
                   g.name as group_name,
                   g.id as group_id,
                   c.title as course_title,
                   CONCAT(u.first_name, ' ', u.last_name) as teacher_name
            FROM schedules s
            INNER JOIN groups g ON s.group_id = g.id
            INNER JOIN group_members gm ON g.id = gm.group_id
            LEFT JOIN courses c ON g.course_id = c.id
            LEFT JOIN users u ON g.teacher_id = u.id
            WHERE gm.student_id = ?
            ORDER BY s.start_time ASC
        ");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Найти занятие по ID с дополнительной информацией
     */
    public function findById($id) {
        $stmt = $this->db->prepare("
            SELECT s.*, 
                   g.name as group_name,
                   g.id as group_id,
                   g.teacher_id,
                   c.title as course_title,
                   CONCAT(u.first_name, ' ', u.last_name) as teacher_name,
                   u.email as teacher_email
            FROM schedules s
            INNER JOIN groups g ON s.group_id = g.id
            LEFT JOIN courses c ON g.course_id = c.id
            LEFT JOIN users u ON g.teacher_id = u.id
            WHERE s.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Обновить данные о занятии
     */
    public function update($id, $data) {
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "$key = ?";
        }
        $set = implode(', ', $set);
        
        $stmt = $this->db->prepare("UPDATE {$this->table} SET $set WHERE id = ?");
        $values = array_values($data);
        $values[] = $id;
        
        return $stmt->execute($values);
    }
    
    /**
     * Удалить занятие (также удаляет все связанные записи о посещаемости)
     */
    public function delete($id) {
        // Сначала удаляем все записи о посещаемости
        $stmt = $this->db->prepare("DELETE FROM attendance WHERE schedule_id = ?");
        $stmt->execute([$id]);
        
        // Затем удаляем само занятие
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Получить занятия для группы в определенном диапазоне дат
     */
    public function findByGroupAndDateRange($groupId, $startDate, $endDate) {
        $stmt = $this->db->prepare("
            SELECT s.*, 
                   g.name as group_name,
                   c.title as course_title
            FROM schedules s
            INNER JOIN groups g ON s.group_id = g.id
            LEFT JOIN courses c ON g.course_id = c.id
            WHERE s.group_id = ? 
            AND s.start_time >= ? 
            AND s.start_time <= ?
            ORDER BY s.start_time ASC
        ");
        $stmt->execute([$groupId, $startDate, $endDate]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Получить занятия для учителя (через группы)
     */
    public function findAllForTeacher($teacherId) {
        $stmt = $this->db->prepare("
            SELECT s.*, 
                   g.name as group_name,
                   g.id as group_id,
                   c.title as course_title
            FROM schedules s
            INNER JOIN groups g ON s.group_id = g.id
            LEFT JOIN courses c ON g.course_id = c.id
            WHERE g.teacher_id = ?
            ORDER BY s.start_time ASC
        ");
        $stmt->execute([$teacherId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Получить предстоящие занятия для студента
     */
    public function getUpcomingForStudent($studentId, $limit = 10) {
        $stmt = $this->db->prepare("
            SELECT s.*, 
                   g.name as group_name,
                   c.title as course_title
            FROM schedules s
            INNER JOIN groups g ON s.group_id = g.id
            INNER JOIN group_members gm ON g.id = gm.group_id
            LEFT JOIN courses c ON g.course_id = c.id
            WHERE gm.student_id = ?
            AND s.start_time >= NOW()
            ORDER BY s.start_time ASC
            LIMIT ?
        ");
        $stmt->execute([$studentId, $limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Получить данные отчетов для учителя за указанный период
     * Возвращает статистику: количество занятий, общее количество часов, посещаемость
     */
    public function getTeacherReportData($teacherId, $startDate, $endDate) {
        // Получаем все занятия учителя за период
        $stmt = $this->db->prepare("
            SELECT s.*,
                   g.name as group_name,
                   g.id as group_id,
                   c.title as course_title,
                   TIMESTAMPDIFF(MINUTE, s.start_time, s.end_time) as duration_minutes,
                   TIMESTAMPDIFF(HOUR, s.start_time, s.end_time) as duration_hours
            FROM schedules s
            INNER JOIN groups g ON s.group_id = g.id
            LEFT JOIN courses c ON g.course_id = c.id
            WHERE g.teacher_id = ?
            AND s.start_time >= ?
            AND s.start_time <= ?
            AND s.start_time <= NOW()
            ORDER BY s.start_time ASC
        ");
        $stmt->execute([$teacherId, $startDate, $endDate]);
        $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Для каждого занятия получаем статистику посещаемости
        require_once __DIR__ . '/Attendance.php';
        require_once __DIR__ . '/GroupMember.php';
        $attendanceModel = new Attendance();
        $groupMemberModel = new GroupMember();
        
        $totalClasses = count($schedules);
        $totalHours = 0;
        $totalAttendancePercents = 0;
        $classesWithAttendance = 0;
        
        $detailedData = [];
        
        foreach ($schedules as $schedule) {
            // Получаем количество студентов в группе
            $students = $groupMemberModel->getMembersByGroupId($schedule['group_id']);
            $studentCount = count($students);
            
            // Получаем посещаемость для этого занятия
            $attendanceRecords = $attendanceModel->getByScheduleId($schedule['id']);
            $presentCount = 0;
            
            foreach ($attendanceRecords as $record) {
                if ($record['status'] === 'present') {
                    $presentCount++;
                }
            }
            
            // Рассчитываем процент посещаемости для этого занятия
            $attendancePercent = $studentCount > 0 ? ($presentCount / $studentCount) * 100 : 0;
            
            // Накапливаем общую статистику
            $totalHours += $schedule['duration_hours'] ?? ($schedule['duration_minutes'] / 60);
            
            // Добавляем процент посещаемости для расчета среднего
            if ($studentCount > 0) {
                $totalAttendancePercents += $attendancePercent;
                $classesWithAttendance++;
            }
            
            // Добавляем детальную информацию
            $detailedData[] = [
                'id' => $schedule['id'],
                'date' => $schedule['start_time'],
                'group_name' => $schedule['group_name'],
                'title' => $schedule['title'] ?? $schedule['group_name'],
                'course_title' => $schedule['course_title'],
                'student_count' => $studentCount,
                'present_count' => $presentCount,
                'attendance_percent' => round($attendancePercent, 2),
                'duration_hours' => $schedule['duration_hours'] ?? round($schedule['duration_minutes'] / 60, 2)
            ];
        }
        
        // Рассчитываем среднюю посещаемость (среднее арифметическое процентов по всем занятиям)
        $averageAttendance = $classesWithAttendance > 0 ? ($totalAttendancePercents / $classesWithAttendance) : 0;
        
        return [
            'total_classes' => $totalClasses,
            'total_hours' => round($totalHours, 2),
            'average_attendance_percent' => round($averageAttendance, 2),
            'detailed_data' => $detailedData
        ];
    }
}

