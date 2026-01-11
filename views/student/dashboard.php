<?php $title = __('site_name'); include 'views/layouts/header.php'; ?>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <h2 class="font-bold m-b-xs"><?php echo __('dashboard.welcome_back'); ?></h2>
        <p class="text-muted"><?php echo __('dashboard.continue_learning'); ?></p>
        <div class="m-t-sm">
            <?php if ($unread_messages > 0): ?>
                <a href="/index.php?url=student/messages" class="btn btn-sm btn-warning m-r-xs">
                    <i class="ion-ios-email"></i> <?php echo __('nav.messages'); ?>
                    <span class="label label-danger"><?php echo $unread_messages; ?></span>
                </a>
            <?php endif; ?>
            <a href="/index.php?url=student/achievements" class="btn btn-sm btn-default">
                <i class="ion-trophy"></i> <?php echo __('nav.achievements'); ?>
            </a>
        </div>
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
            <div class="progress m-b-sm" style="height: 8px; background: #f0f0f0; border-radius: 4px;">
                <div class="progress-bar" role="progressbar" style="width: <?php echo $course['progress']; ?>%; background: #1ab394;" aria-valuenow="<?php echo $course['progress']; ?>" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <p class="text-muted m-b-sm" style="font-size: 12px;">
                <?php if ($course['progress'] == 100): ?>
                    <i class="ion-checkmark-circled text-success"></i> <?php echo __('course.completed'); ?>!
                <?php else: ?>
                    <?php echo $course['progress']; ?>% <?php echo __('course.progress'); ?>
                <?php endif; ?>
            </p>
            <a href="/index.php?url=student/course/<?php echo $course['id']; ?>" class="btn btn-sm btn-primary">
                <i class="ion-play"></i> <?php echo $course['progress'] == 100 ? __('course.review') : __('course.continue'); ?>
            </a>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php else: ?>
<div class="row">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom text-center" style="padding: 60px 20px;">
            <i class="ion-book" style="font-size: 64px; color: #ddd; margin-bottom: 20px;"></i>
            <h3 class="m-b-xs"><?php echo __('dashboard.no_courses'); ?></h3>
            <p class="text-muted"><?php echo __('dashboard.contact_admin'); ?></p>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if (count($achievements) > 0): ?>
<div class="row m-t-sm">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom">
            <h3 class="m-b-sm"><?php echo __('dashboard.recent_achievements'); ?></h3>
            <div class="row">
                <?php foreach (array_slice($achievements, 0, 5) as $achievement): ?>
                <div class="col-xs-6 col-sm-4 col-md-2 text-center m-b-sm">
                    <?php if ($achievement['icon']): ?>
                        <img src="/public/uploads/<?php echo $achievement['icon']; ?>" alt="<?php echo $achievement['title']; ?>" style="width: 80px; height: 80px; object-fit: contain; margin-bottom: 10px;">
                    <?php else: ?>
                        <div style="width: 80px; height: 80px; background: #f5f5f5; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                            <i class="ion-trophy" style="font-size: 40px; color: #1ab394;"></i>
                        </div>
                    <?php endif; ?>
                    <div style="font-size: 12px; color: #666;"><?php echo htmlspecialchars($achievement['title']); ?></div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center m-t-sm">
                <a href="/index.php?url=student/achievements" class="btn btn-sm btn-default">
                    <?php echo __('dashboard.view_all'); ?> <i class="ion-arrow-right-c"></i>
                </a>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php include 'views/layouts/footer.php'; ?>
