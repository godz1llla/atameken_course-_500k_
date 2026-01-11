<?php $title = __('teacher.grade_submission'); include 'views/layouts/header.php'; ?>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <h2 class="font-bold m-b-xs"><i class="ion-edit"></i> <?php echo __('teacher.grade_submission'); ?></h2>
        <a href="/index.php?url=teacher/viewSubmissions/<?php echo $homework['id']; ?>" class="btn btn-sm btn-default m-t-xs">
            <i class="ion-arrow-left-c"></i> <?php echo __('common.back'); ?>
        </a>
    </div>
</div>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom">
            <h3 class="m-b-sm"><i class="ion-person"></i> <?php echo __('teacher.student_submission'); ?></h3>
            
            <dl class="dl-horizontal m-b-sm">
                <dt><?php echo __('auth.first_name'); ?>:</dt>
                <dd><?php echo htmlspecialchars($submission['student_name']); ?></dd>
                <dt><?php echo __('auth.email'); ?>:</dt>
                <dd><?php echo htmlspecialchars($submission['student_email']); ?></dd>
                <dt><?php echo __('teacher.homework_title'); ?>:</dt>
                <dd><?php echo htmlspecialchars($submission['homework_title']); ?></dd>
                <dt><?php echo __('teacher.submitted_at'); ?>:</dt>
                <dd><?php echo date('d.m.Y H:i', strtotime($submission['submitted_at'])); ?></dd>
            </dl>
            
            <?php if ($submission['status'] === 'late'): ?>
                <div class="alert alert-warning m-b-sm">
                    <i class="ion-alert-circled"></i> <?php echo __('teacher.late_submission'); ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($submission['submission_text'])): ?>
            <div class="m-t-sm" style="padding: 20px; background: #f5f5f5; border-radius: 5px;">
                <h4 class="m-b-sm"><?php echo __('teacher.submission_text'); ?></h4>
                <div style="white-space: pre-wrap; line-height: 1.8;"><?php echo nl2br(htmlspecialchars($submission['submission_text'])); ?></div>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($submission['file_path'])): ?>
            <div class="m-t-sm">
                <h4 class="m-b-sm"><?php echo __('teacher.attached_file'); ?></h4>
                <a href="/index.php?url=teacher/downloadFile/<?php echo $submission['id']; ?>" class="btn btn-info" target="_blank">
                    <i class="ion-paperclip"></i> <?php echo __('teacher.download_file'); ?>: <?php echo htmlspecialchars(basename($submission['file_path'])); ?>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom">
            <h3 class="m-b-sm"><i class="ion-checkmark"></i> <?php echo __('teacher.grade_work'); ?></h3>
            
            <form method="POST" action="/index.php?url=teacher/saveGrade" class="form-horizontal">
                <input type="hidden" name="submission_id" value="<?php echo $submission['id']; ?>">
                
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('teacher.grade'); ?> *</label>
                    <div class="col-sm-10">
                        <input type="number" name="grade" class="form-control" value="<?php echo $submission['grade'] ?? ''; ?>" required min="0" max="100" placeholder="0-100">
                        <span class="help-block"><?php echo __('teacher.grade_help'); ?></span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('teacher.comment'); ?></label>
                    <div class="col-sm-10">
                        <textarea name="comment" class="form-control" rows="5" placeholder="<?php echo __('teacher.comment_placeholder'); ?>"><?php echo htmlspecialchars($submission['teacher_comment'] ?? ''); ?></textarea>
                        <span class="help-block"><?php echo __('teacher.comment_help'); ?></span>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-2">
                        <button type="submit" class="btn btn-success">
                            <i class="ion-checkmark"></i> <?php echo __('teacher.save_grade'); ?>
                        </button>
                        <a href="/index.php?url=teacher/viewSubmissions/<?php echo $homework['id']; ?>" class="btn btn-default">
                            <i class="ion-close"></i> <?php echo __('common.cancel'); ?>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
