<?php $title = __('teacher.class_details'); include 'views/layouts/header.php'; ?>

<div class="content-area">
    <div class="page-header">
        <h1>📋 <?php echo __('teacher.class_details'); ?></h1>
        <a href="/index.php?url=teacher/schedule" class="btn btn-outline">← <?php echo __('common.back'); ?></a>
    </div>
    
    <?php if (isset($_GET['success'])): ?>
    <div class="alert" style="background: rgba(16, 185, 129, 0.1); border-left-color: var(--success); color: var(--success); margin-bottom: 30px;">
        ✅ <?php echo __('teacher.attendance_saved'); ?>
    </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])): ?>
    <div class="alert" style="background: rgba(234, 84, 85, 0.1); border-left-color: var(--danger); color: var(--danger); margin-bottom: 30px;">
        ❌ <?php echo __('teacher.attendance_error'); ?>
    </div>
    <?php endif; ?>
    
    <div style="display: grid; gap: 30px;">
        <!-- Первая карточка: Информация о занятии -->
        <div class="card">
            <h2>📅 <?php echo __('teacher.class_info'); ?></h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px;">
                <div>
                    <strong style="color: var(--gray); font-size: 14px;"><?php echo __('admin.group_name'); ?>:</strong>
                    <p style="font-size: 18px; font-weight: 600; margin-top: 5px;"><?php echo htmlspecialchars($schedule['group_name']); ?></p>
                </div>
                <div>
                    <strong style="color: var(--gray); font-size: 14px;"><?php echo __('admin.class_title'); ?>:</strong>
                    <p style="font-size: 18px; font-weight: 600; margin-top: 5px;">
                        <?php echo $schedule['title'] ? htmlspecialchars($schedule['title']) : __('teacher.no_title'); ?>
                    </p>
                </div>
                <div>
                    <strong style="color: var(--gray); font-size: 14px;"><?php echo __('admin.start_time'); ?>:</strong>
                    <p style="font-size: 18px; font-weight: 600; margin-top: 5px;">
                        <?php echo date('d.m.Y H:i', strtotime($schedule['start_time'])); ?>
                    </p>
                </div>
                <div>
                    <strong style="color: var(--gray); font-size: 14px;"><?php echo __('admin.end_time'); ?>:</strong>
                    <p style="font-size: 18px; font-weight: 600; margin-top: 5px;">
                        <?php echo date('d.m.Y H:i', strtotime($schedule['end_time'])); ?>
                    </p>
                </div>
                <div>
                    <strong style="color: var(--gray); font-size: 14px;"><?php echo __('admin.location'); ?>:</strong>
                    <p style="font-size: 18px; font-weight: 600; margin-top: 5px;">
                        <?php echo $schedule['location'] ? htmlspecialchars($schedule['location']) : __('teacher.no_location'); ?>
                    </p>
                </div>
                <?php if (!empty($schedule['course_title'])): ?>
                <div>
                    <strong style="color: var(--gray); font-size: 14px;"><?php echo __('course.title'); ?>:</strong>
                    <p style="font-size: 18px; font-weight: 600; margin-top: 5px;"><?php echo htmlspecialchars($schedule['course_title']); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Вторая карточка: Посещаемость -->
        <div class="card">
            <h2>👥 <?php echo __('teacher.attendance'); ?></h2>
            
            <?php if (!empty($students)): ?>
            <form method="POST" action="/index.php?url=teacher/saveAttendance" style="margin-top: 20px;">
                <input type="hidden" name="schedule_id" value="<?php echo $schedule['id']; ?>">
                
                <table class="data-table" style="margin-top: 20px;">
                    <thead>
                        <tr>
                            <th><?php echo __('auth.first_name'); ?></th>
                            <th><?php echo __('auth.email'); ?></th>
                            <th style="text-align: center; width: 400px;"><?php echo __('teacher.attendance_status'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                        <tr>
                            <td data-label="<?php echo __('auth.first_name'); ?>">
                                <strong><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></strong>
                            </td>
                            <td data-label="<?php echo __('auth.email'); ?>">
                                <?php echo htmlspecialchars($student['email']); ?>
                            </td>
                            <td data-label="<?php echo __('teacher.attendance_status'); ?>" style="text-align: center;">
                                <div style="display: flex; justify-content: center; gap: 10px; flex-wrap: wrap;">
                                    <label style="display: flex; align-items: center; cursor: pointer; padding: 8px 15px; border-radius: var(--radius); background: var(--light-gray); transition: var(--transition);">
                                        <input type="radio" name="attendance[<?php echo $student['user_id']; ?>]" value="present" 
                                               <?php echo (isset($attendanceStatuses[$student['user_id']]) && $attendanceStatuses[$student['user_id']] === 'present') ? 'checked' : ''; ?> 
                                               style="margin-right: 8px;">
                                        <span style="color: var(--success); font-weight: 600;">✅ <?php echo __('teacher.present'); ?></span>
                                    </label>
                                    <label style="display: flex; align-items: center; cursor: pointer; padding: 8px 15px; border-radius: var(--radius); background: var(--light-gray); transition: var(--transition);">
                                        <input type="radio" name="attendance[<?php echo $student['user_id']; ?>]" value="absent" 
                                               <?php echo (!isset($attendanceStatuses[$student['user_id']]) || $attendanceStatuses[$student['user_id']] === 'absent') ? 'checked' : ''; ?> 
                                               style="margin-right: 8px;">
                                        <span style="color: var(--danger); font-weight: 600;">❌ <?php echo __('teacher.absent'); ?></span>
                                    </label>
                                    <label style="display: flex; align-items: center; cursor: pointer; padding: 8px 15px; border-radius: var(--radius); background: var(--light-gray); transition: var(--transition);">
                                        <input type="radio" name="attendance[<?php echo $student['user_id']; ?>]" value="late" 
                                               <?php echo (isset($attendanceStatuses[$student['user_id']]) && $attendanceStatuses[$student['user_id']] === 'late') ? 'checked' : ''; ?> 
                                               style="margin-right: 8px;">
                                        <span style="color: var(--warning); font-weight: 600;">⏰ <?php echo __('teacher.late'); ?></span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <div class="form-actions" style="margin-top: 30px;">
                    <button type="submit" class="btn btn-primary">💾 <?php echo __('teacher.save_attendance'); ?></button>
                    <a href="/index.php?url=teacher/schedule" class="btn btn-outline">❌ <?php echo __('common.cancel'); ?></a>
                </div>
            </form>
            <?php else: ?>
            <div style="text-align: center; padding: 40px 20px; margin-top: 20px;">
                <div style="font-size: 48px; margin-bottom: 15px;">👥</div>
                <p style="color: var(--gray);"><?php echo __('teacher.no_students_in_group'); ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
input[type="radio"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

input[type="radio"]:checked + span {
    font-weight: 700;
}

label:hover {
    background: rgba(115, 103, 240, 0.1) !important;
}

label:has(input[type="radio"]:checked) {
    background: rgba(115, 103, 240, 0.15) !important;
    border: 2px solid var(--primary);
}
</style>

<?php include 'views/layouts/footer.php'; ?>

