<?php $title = isset($course) ? __('common.edit') . ' ' . __('nav.courses') : __('admin.create_course'); include 'views/layouts/header.php'; ?>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <h2 class="font-bold m-b-xs"><?php echo isset($course) ? __('common.edit') . ' ' . __('nav.courses') : __('admin.create_course'); ?></h2>
        <a href="/index.php?url=admin/courses" class="btn btn-sm btn-default m-t-xs">
            <i class="ion-arrow-left-c"></i> <?php echo __('common.back'); ?>
        </a>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom">
            <form method="POST" enctype="multipart/form-data" class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('course.title'); ?></label>
                    <div class="col-sm-10">
                        <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($course['title'] ?? ''); ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('course.description'); ?></label>
                    <div class="col-sm-10">
                        <textarea name="description" class="form-control" rows="5" required><?php echo htmlspecialchars($course['description'] ?? ''); ?></textarea>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('course.teacher'); ?></label>
                    <div class="col-sm-10">
                        <select name="teacher_id" class="form-control" required>
                            <option value=""><?php echo __('common.select'); ?> <?php echo __('course.teacher'); ?></option>
                            <?php foreach ($teachers as $teacher): ?>
                            <option value="<?php echo $teacher['id']; ?>" <?php echo (isset($course) && $course['teacher_id'] == $teacher['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($teacher['first_name'] . ' ' . $teacher['last_name']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('course.image'); ?></label>
                    <div class="col-sm-10">
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <?php if (isset($course) && $course['image']): ?>
                            <img src="/public/uploads/<?php echo $course['image']; ?>" alt="Course Image" style="max-width: 200px; margin-top: 10px; border-radius: 5px; border: 1px solid #ddd;">
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-2">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="is_published" <?php echo (isset($course) && $course['is_published']) ? 'checked' : ''; ?>>
                                <?php echo __('course.published'); ?>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-2">
                        <button type="submit" class="btn btn-success">
                            <i class="ion-checkmark"></i> <?php echo __('common.save'); ?>
                        </button>
                        <a href="/index.php?url=admin/courses" class="btn btn-default">
                            <i class="ion-close"></i> <?php echo __('common.cancel'); ?>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
