<?php 
$title = isset($homework) ? __('teacher.edit_homework') : __('teacher.create_homework'); 
include 'views/layouts/header.php'; 
?>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <h2 class="font-bold m-b-xs"><?php echo isset($homework) ? __('teacher.edit_homework') : __('teacher.create_homework'); ?></h2>
        <a href="/index.php?url=teacher/manageCourse/<?php echo $course['id']; ?>" class="btn btn-sm btn-default m-t-xs">
            <i class="ion-arrow-left-c"></i> <?php echo __('common.back'); ?>
        </a>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom">
            <div class="alert alert-info m-b-sm">
                <p class="m-b-xs"><strong><?php echo __('course.title'); ?>:</strong> <?php echo htmlspecialchars($course['title']); ?></p>
                <p class="m-b-xs"><strong><?php echo __('admin.module'); ?>:</strong> <?php echo htmlspecialchars($module['title']); ?></p>
                <p class="m-b-xs"><strong><?php echo __('lesson.title'); ?>:</strong> <?php echo htmlspecialchars($lesson['title']); ?></p>
            </div>
            
            <form method="POST" action="/index.php?url=teacher/saveHomework" class="form-horizontal">
                <input type="hidden" name="lesson_id" value="<?php echo $lesson['id']; ?>">
                <?php if (isset($homework)): ?>
                    <input type="hidden" name="homework_id" value="<?php echo $homework['id']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('teacher.homework_title'); ?> *</label>
                    <div class="col-sm-10">
                        <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($homework['title'] ?? ''); ?>" required placeholder="<?php echo __('teacher.homework_title_placeholder'); ?>" maxlength="255">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('teacher.homework_description'); ?></label>
                    <div class="col-sm-10">
                        <textarea name="description" class="form-control" rows="6" placeholder="<?php echo __('teacher.homework_description_placeholder'); ?>"><?php echo htmlspecialchars($homework['description'] ?? ''); ?></textarea>
                        <span class="help-block"><?php echo __('teacher.homework_description_help'); ?></span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('teacher.due_date'); ?> *</label>
                    <div class="col-sm-10">
                        <input type="datetime-local" name="due_date" class="form-control" value="<?php echo isset($homework) && $homework['due_date'] ? date('Y-m-d\TH:i', strtotime($homework['due_date'])) : ''; ?>" required>
                        <span class="help-block"><?php echo __('teacher.due_date_help'); ?></span>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-2">
                        <button type="submit" class="btn btn-success">
                            <i class="ion-checkmark"></i> <?php echo __('common.save'); ?>
                        </button>
                        <a href="/index.php?url=teacher/manageCourse/<?php echo $course['id']; ?>" class="btn btn-default">
                            <i class="ion-close"></i> <?php echo __('common.cancel'); ?>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
