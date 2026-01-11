<?php $title = __('nav.statistics'); include 'views/layouts/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>📊 <?php echo __('nav.statistics'); ?></h1>
    </div>
    
    <!-- Overall Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3>📚 <?php echo __('stats.enrolled_courses'); ?></h3>
            <p class="stat-number"><?php echo $stats['total_enrolled']; ?></p>
        </div>
        
        <div class="stat-card">
            <h3>✅ <?php echo __('stats.completed_courses'); ?></h3>
            <p class="stat-number"><?php echo $stats['completed_courses']; ?></p>
        </div>
        
        <div class="stat-card">
            <h3>💯 <?php echo __('stats.avg_score'); ?></h3>
            <p class="stat-number"><?php echo round($stats['avg_test_score']); ?>%</p>
        </div>
        
        <div class="stat-card">
            <h3>🏆 <?php echo __('nav.achievements'); ?></h3>
            <p class="stat-number"><?php echo $stats['total_achievements']; ?></p>
        </div>
        
        <div class="stat-card">
            <h3>🎓 <?php echo __('nav.certificates'); ?></h3>
            <p class="stat-number"><?php echo $stats['total_certificates']; ?></p>
        </div>
    </div>
    
    <!-- Course Progress -->
    <div style="background: rgba(255, 255, 255, 0.98); padding: 40px; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); margin: 30px 0;">
        <h2 style="margin-bottom: 25px; font-weight: 900; font-size: 24px;">📈 <?php echo __('stats.course_progress'); ?></h2>
        
        <?php if (!empty($course_progress)): ?>
            <?php foreach ($course_progress as $course): ?>
            <div style="margin-bottom: 25px; padding: 25px; background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%); border-radius: 12px; border-left: 5px solid var(--primary);">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px; flex-wrap: wrap; gap: 15px;">
                    <div style="flex: 1;">
                        <h3 style="font-weight: 800; margin-bottom: 8px;"><?php echo $course['title']; ?></h3>
                        <p style="color: #6b7280; font-size: 14px;">
                            📅 <?php echo __('teacher.enrolled'); ?>: <?php echo date('M d, Y', strtotime($course['enrolled_at'])); ?>
                            <?php if ($course['completed_at']): ?>
                                | ✅ <?php echo __('course.completed'); ?>: <?php echo date('M d, Y', strtotime($course['completed_at'])); ?>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 32px; font-weight: 900; background: linear-gradient(135deg, var(--primary), var(--secondary)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            <?php echo round($course['progress_percent']); ?>%
                        </div>
                        <p style="font-size: 12px; color: #6b7280;">
                            <?php echo $course['completed_lessons']; ?> / <?php echo $course['total_lessons']; ?> <?php echo __('stats.lessons'); ?>
                        </p>
                    </div>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: <?php echo $course['progress_percent']; ?>%"></div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align: center; color: #6b7280; padding: 40px;">
                📚 <?php echo __('dashboard.no_courses'); ?>
            </p>
        <?php endif; ?>
    </div>
    
    <!-- Test Results -->
    <div style="background: rgba(255, 255, 255, 0.98); padding: 40px; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); margin: 30px 0;">
        <h2 style="margin-bottom: 25px; font-weight: 900; font-size: 24px;">📝 <?php echo __('stats.recent_tests'); ?></h2>
        
        <?php if (!empty($test_results)): ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th><?php echo __('nav.courses'); ?></th>
                        <th><?php echo __('test.title'); ?></th>
                        <th><?php echo __('test.score'); ?></th>
                        <th><?php echo __('admin.status'); ?></th>
                        <th><?php echo __('certificates.issued'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($test_results as $result): ?>
                    <tr>
                        <td data-label="<?php echo __('nav.courses'); ?>"><?php echo $result['course_name']; ?></td>
                        <td data-label="<?php echo __('test.title'); ?>"><?php echo $result['test_name']; ?></td>
                        <td data-label="<?php echo __('test.score'); ?>">
                            <span style="font-weight: 800; font-size: 18px; color: <?php echo $result['score'] >= 70 ? '#10b981' : '#ef4444'; ?>">
                                <?php echo $result['score']; ?>%
                            </span>
                        </td>
                        <td data-label="<?php echo __('admin.status'); ?>">
                            <span class="status <?php echo $result['passed'] ? 'active' : 'inactive'; ?>">
                                <?php echo $result['passed'] ? '✅ ' . __('test.passed') : '❌ ' . __('test.failed'); ?>
                            </span>
                        </td>
                        <td data-label="<?php echo __('certificates.issued'); ?>">
                            <?php echo date('M d, Y H:i', strtotime($result['completed_at'])); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align: center; color: #6b7280; padding: 40px;">
                📝 <?php echo __('stats.no_test_results'); ?>
            </p>
        <?php endif; ?>
    </div>
    
    <!-- Activity Timeline -->
    <?php if (!empty($activity)): ?>
    <div style="background: rgba(255, 255, 255, 0.98); padding: 40px; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
        <h2 style="margin-bottom: 25px; font-weight: 900; font-size: 24px;">📅 <?php echo __('stats.activity_timeline'); ?></h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); gap: 10px;">
            <?php foreach (array_reverse($activity) as $day): ?>
            <div style="text-align: center; padding: 15px; background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-radius: 10px;">
                <div style="font-size: 24px; font-weight: 900; color: var(--primary);">
                    <?php echo $day['lessons_completed']; ?>
                </div>
                <div style="font-size: 11px; color: #6b7280; margin-top: 5px;">
                    <?php echo date('M d', strtotime($day['date'])); ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include 'views/layouts/footer.php'; ?>

