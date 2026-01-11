<?php $title = __('test.result'); include 'views/layouts/header.php'; ?>

<div class="container">
    <h1>📊 <?php echo __('test.result'); ?>: <?php echo $test['title']; ?></h1>
    
    <div class="test-result-card">
        <h2><?php echo __('test.your_score'); ?>: <?php echo $result['score']; ?>%</h2>
        <p class="result-status <?php echo $result['passed'] ? 'passed' : 'failed'; ?>">
            <?php echo $result['passed'] ? '✓ ' . __('test.passed') : '✗ ' . __('test.failed'); ?>
        </p>
        <p style="font-size: 18px; margin: 20px 0;"><?php echo __('test.passing_score'); ?>: <?php echo $test['passing_score']; ?>%</p>
        <p>🔢 <?php echo __('test.attempt'); ?>: <?php echo $result['attempt_number']; ?></p>
        <p>📅 <?php echo date('Y-m-d H:i', strtotime($result['completed_at'])); ?></p>
        
        <?php if ($test['is_mandatory'] && !$result['passed']): ?>
        <div class="alert alert-warning" style="margin-top: 30px;">
            ⚠️ <?php echo __('admin.mandatory_test'); ?>! <?php echo __('admin.must_pass'); ?>
        </div>
        <?php endif; ?>
    </div>
    
    <?php if (!$result['passed']): ?>
        <a href="/index.php?url=weeklyTest/take/<?php echo $test['id']; ?>" class="btn btn-primary">
            🔄 <?php echo __('test.try_again'); ?>
        </a>
    <?php endif; ?>
    
    <a href="/index.php?url=student/dashboard" class="btn btn-secondary">
        ← <?php echo __('common.back'); ?> to <?php echo __('nav.dashboard'); ?>
    </a>
</div>

<?php include 'views/layouts/footer.php'; ?>

