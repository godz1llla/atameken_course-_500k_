<?php $title = __('site_name'); include 'views/layouts/header.php'; ?>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <h2 class="font-bold m-b-xs"><?php echo __('dashboard.welcome'); ?>, <?php echo __('dashboard.teachers'); ?>!</h2>
        <p class="text-muted"><?php echo __('dashboard.manage_platform'); ?></p>
        <?php if ($unread_messages > 0): ?>
        <a href="/index.php?url=teacher/messages" class="btn btn-sm btn-warning m-t-xs">
            <i class="ion-ios-email"></i> <?php echo __('nav.messages'); ?>
            <span class="label label-danger"><?php echo $unread_messages; ?></span>
        </a>
        <?php endif; ?>
    </div>
</div>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom">
            <h3 class="m-b-sm"><?php echo __('dashboard.my_courses'); ?></h3>
        </div>
    </div>
</div>

<?php if (count($courses) > 0): ?>
<div class="row">
    <?php foreach ($courses as $course): ?>
    <div class="col-xs-12 col-sm-6 col-md-4 m-b-sm">
        <div class="ibox-content border-bottom">
            <?php if ($course['image']): ?>
                <img src="/public/uploads/<?php echo $course['image']; ?>" alt="<?php echo $course['title']; ?>" style="width: 100%; height: 200px; object-fit: cover; margin-bottom: 15px;">
            <?php else: ?>
                <div style="width: 100%; height: 200px; background: #f5f5f5; display: flex; align-items: center; justify-content: center; margin-bottom: 15px;">
                    <i class="ion-book" style="font-size: 64px; color: #ccc;"></i>
                </div>
            <?php endif; ?>
            <h4 class="font-bold m-b-xs"><?php echo htmlspecialchars($course['title']); ?></h4>
            <p class="text-muted m-b-sm" style="font-size: 13px;"><?php echo htmlspecialchars(substr($course['description'], 0, 100)); ?>...</p>
            <div class="m-t-sm">
                <a href="/index.php?url=teacher/manageCourse/<?php echo $course['id']; ?>" class="btn btn-sm btn-primary m-r-xs">
                    <i class="ion-gear-a"></i> <?php echo __('course.manage'); ?>
                </a>
                <a href="/index.php?url=teacher/courseStudents/<?php echo $course['id']; ?>" class="btn btn-sm btn-default">
                    <i class="ion-person-stalker"></i> <?php echo __('teacher.view_students'); ?>
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php else: ?>
<div class="row">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom text-center" style="padding: 60px 20px;">
            <i class="ion-book" style="font-size: 64px; color: #ddd; margin-bottom: 20px;"></i>
            <h3 class="m-b-xs"><?php echo __('teacher.no_courses'); ?></h3>
            <p class="text-muted"><?php echo __('teacher.contact_admin'); ?></p>
        </div>
    </div>
</div>
<?php endif; ?>

<?php include 'views/layouts/footer.php'; ?>
