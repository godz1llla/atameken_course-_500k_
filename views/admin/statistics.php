<?php $title = __('nav.statistics'); include 'views/layouts/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>📊 <?php echo __('nav.statistics'); ?> - <?php echo __('dashboard.students'); ?></h1>
    </div>
    
    <?php if (!empty($students)): ?>
    <div style="background: rgba(255, 255, 255, 0.98); padding: 40px; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
        <h2 style="margin-bottom: 25px; font-weight: 900; font-size: 24px;">🏆 <?php echo __('stats.top_students'); ?></h2>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th><?php echo __('auth.first_name'); ?></th>
                    <th><?php echo __('auth.email'); ?></th>
                    <th><?php echo __('stats.enrolled'); ?></th>
                    <th><?php echo __('course.completed'); ?></th>
                    <th><?php echo __('stats.avg_score'); ?></th>
                    <th><?php echo __('nav.achievements'); ?></th>
                    <th><?php echo __('nav.certificates'); ?></th>
                    <th><?php echo __('stats.last_activity'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $index => $student): ?>
                <tr>
                    <td data-label="#">
                        <?php if ($index < 3): ?>
                            <span style="font-size: 24px;">
                                <?php echo $index === 0 ? '🥇' : ($index === 1 ? '🥈' : '🥉'); ?>
                            </span>
                        <?php else: ?>
                            <?php echo $index + 1; ?>
                        <?php endif; ?>
                    </td>
                    <td data-label="<?php echo __('auth.first_name'); ?>">
                        <strong><?php echo $student['name']; ?></strong>
                    </td>
                    <td data-label="<?php echo __('auth.email'); ?>"><?php echo $student['email']; ?></td>
                    <td data-label="<?php echo __('stats.enrolled'); ?>">
                        <span style="font-weight: 700; color: var(--primary);"><?php echo $student['enrolled_courses']; ?></span>
                    </td>
                    <td data-label="<?php echo __('course.completed'); ?>">
                        <span style="font-weight: 700; color: var(--success);"><?php echo $student['completed_courses']; ?></span>
                    </td>
                    <td data-label="<?php echo __('stats.avg_score'); ?>">
                        <div style="display: inline-flex; align-items: center; gap: 8px;">
                            <div class="progress-bar" style="width: 60px; height: 8px; display: inline-block;">
                                <div class="progress-fill" style="width: <?php echo round($student['avg_score']); ?>%"></div>
                            </div>
                            <strong style="color: <?php echo $student['avg_score'] >= 70 ? '#10b981' : '#ef4444'; ?>">
                                <?php echo round($student['avg_score']); ?>%
                            </strong>
                        </div>
                    </td>
                    <td data-label="<?php echo __('nav.achievements'); ?>">
                        <span class="badge">🏆 <?php echo $student['achievements']; ?></span>
                    </td>
                    <td data-label="<?php echo __('nav.certificates'); ?>">
                        <span class="badge">🎓 <?php echo $student['certificates']; ?></span>
                    </td>
                    <td data-label="<?php echo __('stats.last_activity'); ?>">
                        <?php if ($student['last_activity']): ?>
                            <small><?php echo date('M d, Y', strtotime($student['last_activity'])); ?></small>
                        <?php else: ?>
                            <small style="color: #9ca3af;">-</small>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
        <div style="background: rgba(255, 255, 255, 0.98); padding: 60px; text-align: center; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
            <div style="font-size: 80px; margin-bottom: 20px;">📊</div>
            <h3 style="color: #1f2937;"><?php echo __('stats.no_data'); ?></h3>
        </div>
    <?php endif; ?>
</div>

<?php include 'views/layouts/footer.php'; ?>

