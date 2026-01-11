<?php $title = __('teacher.homework_submissions'); include 'views/layouts/header.php'; ?>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <h2 class="font-bold m-b-xs"><i class="ion-clipboard"></i> <?php echo __('teacher.homework_submissions'); ?></h2>
        <p class="text-muted m-b-xs">
            <strong><?php echo __('teacher.homework_title'); ?>:</strong> <?php echo htmlspecialchars($homework['title']); ?><br>
            <strong><?php echo __('lesson.title'); ?>:</strong> <?php echo htmlspecialchars($homework['lesson_title']); ?><br>
            <strong><?php echo __('teacher.due_date'); ?>:</strong> <?php echo date('d.m.Y H:i', strtotime($homework['due_date'])); ?>
        </p>
        <a href="/index.php?url=teacher/manageCourse/<?php echo $homework['course_id']; ?>" class="btn btn-sm btn-default m-t-xs">
            <i class="ion-arrow-left-c"></i> <?php echo __('common.back'); ?>
        </a>
    </div>
</div>

<?php if (!empty($submissions)): ?>
<div class="row">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th><?php echo __('auth.first_name'); ?></th>
                            <th><?php echo __('auth.email'); ?></th>
                            <th><?php echo __('teacher.submitted_at'); ?></th>
                            <th><?php echo __('teacher.status'); ?></th>
                            <th><?php echo __('teacher.grade'); ?></th>
                            <th><?php echo __('admin.actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($submissions as $submission): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($submission['student_name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($submission['student_email']); ?></td>
                            <td>
                                <?php echo date('d.m.Y H:i', strtotime($submission['submitted_at'])); ?>
                                <?php if ($submission['status'] === 'late'): ?>
                                    <br><small class="text-danger"><i class="ion-alert-circled"></i> <?php echo __('teacher.late_submission'); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                $statusBadges = [
                                    'submitted' => ['label-default', __('teacher.status_submitted')],
                                    'late' => ['label-warning', __('teacher.status_late')],
                                    'graded' => ['label-success', __('teacher.status_graded')],
                                    'resubmit' => ['label-danger', __('teacher.status_resubmit')]
                                ];
                                $status = $submission['status'];
                                $badgeClass = $statusBadges[$status][0] ?? 'label-default';
                                $badgeText = $statusBadges[$status][1] ?? $status;
                                ?>
                                <span class="label <?php echo $badgeClass; ?>"><?php echo $badgeText; ?></span>
                            </td>
                            <td>
                                <?php if ($submission['grade'] !== null): ?>
                                    <strong style="color: #1ab394; font-size: 18px;"><?php echo $submission['grade']; ?></strong>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="/index.php?url=teacher/gradeSubmission/<?php echo $submission['id']; ?>" class="btn btn-xs btn-primary">
                                    <i class="ion-edit"></i> <?php echo __('teacher.grade'); ?>
                                </a>
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
            <i class="ion-clipboard" style="font-size: 64px; color: #ddd; margin-bottom: 20px;"></i>
            <h3 class="m-b-xs"><?php echo __('teacher.no_submissions'); ?></h3>
            <p class="text-muted"><?php echo __('teacher.no_submissions_description'); ?></p>
        </div>
    </div>
</div>
<?php endif; ?>

<?php include 'views/layouts/footer.php'; ?>
