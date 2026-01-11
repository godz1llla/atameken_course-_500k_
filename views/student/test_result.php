<?php $title = __('test.result'); include 'views/layouts/header.php'; ?>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <h2 class="font-bold m-b-xs"><i class="ion-stats-bars"></i> <?php echo __('test.result'); ?>: <?php echo htmlspecialchars($test['title']); ?></h2>
    </div>
</div>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom text-center">
            <h3 class="m-b-sm"><?php echo __('test.your_score'); ?>: <strong style="font-size: 32px; color: <?php echo $result['passed'] ? '#1ab394' : '#ed5565'; ?>;"><?php echo $result['score']; ?>%</strong></h3>
            <p class="m-b-sm">
                <span class="label <?php echo $result['passed'] ? 'label-success' : 'label-danger'; ?>" style="font-size: 16px; padding: 8px 16px;">
                    <?php echo $result['passed'] ? '<i class="ion-checkmark"></i> ' . __('test.passed') : '<i class="ion-close"></i> ' . __('test.failed'); ?>
                </span>
            </p>
            <p class="text-muted m-b-xs"><?php echo __('test.passing_score'); ?>: <?php echo $test['passing_score']; ?>%</p>
            <p class="text-muted m-b-xs"><i class="ion-edit"></i> Attempt: <?php echo $result['attempt_number']; ?></p>
            <p class="text-muted"><i class="ion-calendar"></i> <?php echo date('Y-m-d H:i', strtotime($result['completed_at'])); ?></p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom">
            <?php if (!$result['passed'] && (!$test['max_attempts'] || count($all_results) < $test['max_attempts'])): ?>
                <a href="/index.php?url=student/test/<?php echo $test['id']; ?>" class="btn btn-warning m-r-xs">
                    <i class="ion-refresh"></i> <?php echo __('test.try_again'); ?>
                </a>
            <?php endif; ?>
            <a href="/index.php?url=student/dashboard" class="btn btn-default">
                <i class="ion-arrow-left-c"></i> <?php echo __('common.back'); ?> to <?php echo __('nav.dashboard'); ?>
            </a>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
