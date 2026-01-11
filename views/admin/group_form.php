<?php 
$isEdit = isset($group);
$title = $isEdit ? __('admin.edit_group') : __('admin.create_group'); 
include 'views/layouts/header.php'; 
?>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <h2 class="font-bold m-b-xs"><?php echo $isEdit ? __('admin.edit_group') : __('admin.create_group'); ?></h2>
        <?php 
        $baseUrl = (isset($is_manager) && $is_manager) ? 'manager' : 'admin';
        ?>
        <a href="/index.php?url=<?php echo $baseUrl; ?>/groups" class="btn btn-sm btn-default m-t-xs">
            <i class="ion-arrow-left-c"></i> <?php echo __('common.back'); ?>
        </a>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom">
            <form method="POST" class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('admin.group_name'); ?></label>
                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($group['name'] ?? ''); ?>" required placeholder="<?php echo __('admin.group_name'); ?>" maxlength="255">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('course.title'); ?></label>
                    <div class="col-sm-10">
                        <select name="course_id" class="form-control" required>
                            <option value=""><?php echo __('admin.select_course'); ?></option>
                            <?php foreach ($courses as $course): ?>
                                <option value="<?php echo $course['id']; ?>" <?php echo (isset($group) && $group['course_id'] == $course['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($course['title']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('course.teacher'); ?></label>
                    <div class="col-sm-10">
                        <select name="teacher_id" class="form-control">
                            <option value=""><?php echo __('admin.select_teacher'); ?></option>
                            <?php if (!empty($teachers)): ?>
                                <?php foreach ($teachers as $teacher): ?>
                                    <option value="<?php echo $teacher['id']; ?>" <?php echo (isset($group) && $group['teacher_id'] == $teacher['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($teacher['first_name'] . ' ' . $teacher['last_name']); ?> (<?php echo htmlspecialchars($teacher['email']); ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="" disabled><?php echo __('admin.no_teacher'); ?></option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-2">
                        <button type="submit" class="btn btn-success">
                            <i class="ion-checkmark"></i> <?php echo __('common.save'); ?>
                        </button>
                        <a href="/index.php?url=<?php echo $baseUrl; ?>/groups" class="btn btn-default">
                            <i class="ion-close"></i> <?php echo __('common.cancel'); ?>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
