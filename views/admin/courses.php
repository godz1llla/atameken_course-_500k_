<?php $title = __('nav.courses'); include 'views/layouts/header.php'; ?>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <h2 class="font-bold m-b-xs"><?php echo __('nav.courses'); ?></h2>
        <a href="/index.php?url=admin/createCourse" class="btn btn-sm btn-success m-t-xs">
            <i class="ion-plus"></i> <?php echo __('admin.create_course'); ?>
        </a>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th><?php echo __('course.title'); ?></th>
                            <th><?php echo __('course.teacher'); ?></th>
                            <th><?php echo __('admin.status'); ?></th>
                            <th><?php echo __('admin.actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courses as $course): ?>
                        <tr>
                            <td><?php echo $course['id']; ?></td>
                            <td><?php echo htmlspecialchars($course['title']); ?></td>
                            <td><?php echo htmlspecialchars($course['teacher_name'] ?? 'N/A'); ?></td>
                            <td>
                                <span class="label <?php echo $course['is_published'] ? 'label-success' : 'label-default'; ?>">
                                    <?php echo $course['is_published'] ? __('course.published') : __('course.draft'); ?>
                                </span>
                            </td>
                            <td>
                                <a href="/index.php?url=admin/manageCourse/<?php echo $course['id']; ?>" class="btn btn-xs btn-primary m-r-xs">
                                    <i class="ion-gear-a"></i> <?php echo __('course.manage'); ?>
                                </a>
                                <a href="/index.php?url=admin/editCourse/<?php echo $course['id']; ?>" class="btn btn-xs btn-default m-r-xs">
                                    <i class="ion-edit"></i> <?php echo __('common.edit'); ?>
                                </a>
                                <a href="/index.php?url=admin/deleteCourse/<?php echo $course['id']; ?>" class="btn btn-xs btn-danger" onclick="return confirm('<?php echo __('common.confirm_delete'); ?>')">
                                    <i class="ion-trash-a"></i> <?php echo __('common.delete'); ?>
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

<?php include 'views/layouts/footer.php'; ?>
