<?php $title = __('student.my_teachers'); include 'views/layouts/header.php'; ?>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <h2 class="font-bold m-b-xs"><i class="ion-university"></i> <?php echo __('student.my_teachers'); ?></h2>
    </div>
</div>

<?php if (count($teachers) > 0): ?>
<div class="row">
    <?php foreach ($teachers as $teacher): ?>
    <div class="col-xs-12 col-sm-6 col-md-4 m-b-sm">
        <div class="ibox-content border-bottom text-center">
            <div style="width: 100%; height: 200px; background: #f5f5f5; display: flex; align-items: center; justify-content: center; margin-bottom: 15px;">
                <i class="ion-university" style="font-size: 80px; color: #1ab394;"></i>
            </div>
            <h4 class="font-bold m-b-xs"><?php echo htmlspecialchars($teacher['teacher_name']); ?></h4>
            <p class="text-muted m-b-xs"><strong><?php echo __('nav.courses'); ?>:</strong> <?php echo htmlspecialchars($teacher['course_title']); ?></p>
            <p class="text-muted m-b-sm" style="font-size: 12px;"><?php echo htmlspecialchars($teacher['teacher_email']); ?></p>
            <a href="/index.php?url=student/conversation/<?php echo $teacher['teacher_id']; ?>" class="btn btn-sm btn-primary">
                <i class="ion-ios-email"></i> <?php echo __('student.send_message'); ?>
            </a>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php else: ?>
<div class="row">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom text-center" style="padding: 60px 20px;">
            <i class="ion-university" style="font-size: 80px; color: #ddd; margin-bottom: 20px;"></i>
            <h3 class="m-b-xs"><?php echo __('student.no_teachers'); ?></h3>
            <p class="text-muted"><?php echo __('dashboard.no_courses'); ?></p>
        </div>
    </div>
</div>
<?php endif; ?>

<?php include 'views/layouts/footer.php'; ?>
