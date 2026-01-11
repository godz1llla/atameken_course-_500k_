<?php $title = $lesson['title']; include 'views/layouts/header.php'; ?>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <h2 class="font-bold m-b-xs"><i class="ion-document"></i> <?php echo htmlspecialchars($lesson['title']); ?></h2>
        <p class="text-muted">
            <a href="/index.php?url=student/course/<?php echo $course['id']; ?>" class="text-success">
                <i class="ion-home"></i> <?php echo htmlspecialchars($course['title']); ?>
            </a> / <?php echo htmlspecialchars($module['title']); ?>
        </p>
    </div>
</div>

<?php if ($youtube_url): ?>
<div class="row m-b-sm">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom">
            <div class="embed-responsive embed-responsive-16by9">
                <iframe class="embed-responsive-item" src="<?php echo htmlspecialchars($youtube_url); ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom">
            <div style="line-height: 1.8;">
                <?php echo nl2br(htmlspecialchars($lesson['content'])); ?>
            </div>
        </div>
    </div>
</div>

<?php if ($test): ?>
<div class="row m-b-sm">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom" style="background: #fffef0; border-left: 4px solid #f0ad4e;">
            <h3 class="m-b-sm"><i class="ion-edit"></i> <?php echo htmlspecialchars($test['title']); ?></h3>
            <p class="text-muted m-b-sm"><?php echo htmlspecialchars($test['description']); ?></p>
            <a href="/index.php?url=student/test/<?php echo $test['id']; ?>" class="btn btn-warning">
                <i class="ion-edit"></i> <?php echo __('lesson.take_test'); ?>
            </a>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="row">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom">
            <div class="row">
                <div class="col-sm-6">
                    <?php if (!$is_completed): ?>
                    <a href="/index.php?url=student/completeLesson/<?php echo $lesson['id']; ?>" class="btn btn-success">
                        <i class="ion-checkmark"></i> <?php echo __('lesson.mark_complete'); ?>
                    </a>
                    <?php else: ?>
                    <span class="label label-success"><i class="ion-checkmark"></i> <?php echo __('lesson.completed'); ?></span>
                    <?php endif; ?>
                </div>
                <div class="col-sm-6 text-right">
                    <?php if ($prev_lesson): ?>
                    <a href="/index.php?url=student/lesson/<?php echo $prev_lesson['id']; ?>" class="btn btn-default m-r-xs">
                        <i class="ion-arrow-left-c"></i> <?php echo __('lesson.previous'); ?>
                    </a>
                    <?php endif; ?>
                    <?php if ($next_lesson): ?>
                    <a href="/index.php?url=student/lesson/<?php echo $next_lesson['id']; ?>" class="btn btn-default">
                        <?php echo __('lesson.next'); ?> <i class="ion-arrow-right-c"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
