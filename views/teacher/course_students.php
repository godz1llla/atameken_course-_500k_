<?php $title = __('teacher.course_students'); include 'views/layouts/header.php'; ?>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <h2 class="font-bold m-b-xs">
            <i class="ion-person-stalker"></i> <?php echo __('teacher.students'); ?> - <?php echo htmlspecialchars($course['title']); ?>
        </h2>
        <a href="/index.php?url=teacher/dashboard" class="btn btn-sm btn-default m-t-xs">
            <i class="ion-arrow-left-c"></i> <?php echo __('common.back'); ?>
        </a>
    </div>
</div>

<?php if (count($students) > 0): ?>
<div class="row">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th><?php echo __('auth.first_name'); ?></th>
                            <th><?php echo __('auth.email'); ?></th>
                            <th><?php echo __('teacher.enrolled'); ?></th>
                            <th><?php echo __('course.progress'); ?></th>
                            <th><?php echo __('admin.status'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($student['email']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($student['enrolled_at'])); ?></td>
                            <td>
                                <div class="progress" style="height: 20px; width: 100px; display: inline-block; margin-right: 10px;">
                                    <div class="progress-bar" role="progressbar" style="width: <?php echo $student['progress']; ?>%; background: #1ab394;" aria-valuenow="<?php echo $student['progress']; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <?php echo $student['progress']; ?>%
                            </td>
                            <td>
                                <?php if ($student['completed_at']): ?>
                                    <span class="label label-success"><i class="ion-checkmark"></i> <?php echo __('course.completed'); ?></span>
                                <?php else: ?>
                                    <span class="label label-default"><i class="ion-clock"></i> <?php echo __('teacher.in_progress'); ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
<div class="row">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom text-center" style="padding: 60px 20px;">
            <i class="ion-person-stalker" style="font-size: 64px; color: #ddd; margin-bottom: 20px;"></i>
            <h3 class="m-b-xs"><?php echo __('teacher.no_students'); ?></h3>
            <p class="text-muted"><?php echo __('teacher.no_students_enrolled'); ?></p>
        </div>
    </div>
</div>
<?php endif; ?>

<?php include 'views/layouts/footer.php'; ?>
