<?php $title = __('admin.manage_enrollments'); include 'views/layouts/header.php'; ?>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <h2 class="font-bold m-b-xs"><?php echo __('admin.manage_enrollments'); ?></h2>
    </div>
</div>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom">
            <h3 class="m-b-sm"><i class="ion-plus"></i> <?php echo __('admin.enroll_student'); ?></h3>
            <form method="POST" action="/index.php?url=admin/enrollStudent" class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('dashboard.students'); ?></label>
                    <div class="col-sm-4">
                        <select name="user_id" class="form-control" required>
                            <option value=""><?php echo __('common.select'); ?> <?php echo __('dashboard.students'); ?></option>
                            <?php foreach ($students as $student): ?>
                            <option value="<?php echo $student['id']; ?>">
                                <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?> (<?php echo htmlspecialchars($student['email']); ?>)
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('nav.courses'); ?></label>
                    <div class="col-sm-4">
                        <select name="course_id" class="form-control" required>
                            <option value=""><?php echo __('common.select'); ?> <?php echo __('nav.courses'); ?></option>
                            <?php foreach ($courses as $course): ?>
                            <option value="<?php echo $course['id']; ?>">
                                <?php echo htmlspecialchars($course['title']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ion-checkmark"></i> <?php echo __('admin.enroll'); ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
