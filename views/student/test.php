<?php $title = $test['title']; include 'views/layouts/header.php'; ?>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <h2 class="font-bold m-b-xs"><i class="ion-edit"></i> <?php echo htmlspecialchars($test['title']); ?></h2>
        <p class="text-muted"><?php echo htmlspecialchars($test['description']); ?></p>
        <?php if ($test['time_limit']): ?>
            <p class="text-muted m-t-xs"><i class="ion-clock"></i> <?php echo __('test.time_limit'); ?>: <?php echo $test['time_limit']; ?> <?php echo __('time.minutes'); ?></p>
        <?php endif; ?>
        <?php if ($test['max_attempts']): ?>
            <p class="text-muted"><i class="ion-refresh"></i> <?php echo __('test.max_attempts'); ?>: <?php echo $test['max_attempts']; ?> | <?php echo __('test.your_attempts'); ?>: <?php echo count($results); ?></p>
        <?php endif; ?>
    </div>
</div>

<?php if ($test['max_attempts'] && count($results) >= $test['max_attempts']): ?>
<div class="row m-b-sm">
    <div class="col-sm-12">
        <div class="alert alert-warning">
            <i class="ion-alert-circled"></i> <?php echo __('test.max_attempts_reached'); ?>
        </div>
        <a href="/index.php?url=student/testResult/<?php echo $test['id']; ?>" class="btn btn-primary">
            <i class="ion-ios-eye"></i> <?php echo __('common.view'); ?> <?php echo __('test.result'); ?>
        </a>
    </div>
</div>
<?php else: ?>
<div class="row">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom">
            <form method="POST" action="/index.php?url=student/submitTest/<?php echo $test['id']; ?>" class="test-form" data-time-limit="<?php echo $test['time_limit']; ?>">
                <?php foreach ($questions as $index => $question): ?>
                <div class="m-b-lg" style="padding: 20px; background: #f9f9f9; border-radius: 5px; margin-bottom: 20px;">
                    <h4 class="m-b-sm">
                        <?php echo __('test.question'); ?> <?php echo $index + 1; ?>: <?php echo htmlspecialchars($question['question']); ?>
                    </h4>
                    <div class="m-t-sm">
                        <?php foreach ($question['answers'] as $answer): ?>
                        <div class="radio m-b-xs">
                            <label>
                                <input type="radio" name="question_<?php echo $question['id']; ?>" value="<?php echo $answer['id']; ?>" required>
                                <?php echo htmlspecialchars($answer['answer_text']); ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <div class="text-center m-t-lg">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="ion-checkmark"></i> <?php echo __('test.submit'); ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if (count($results) > 0): ?>
<div class="row m-t-sm">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom">
            <h3 class="m-b-sm"><i class="ion-clipboard"></i> Previous Attempts</h3>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?php echo __('test.score'); ?></th>
                            <th><?php echo __('admin.status'); ?></th>
                            <th><?php echo __('certificates.issued'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $result): ?>
                        <tr>
                            <td><?php echo $result['attempt_number']; ?></td>
                            <td><strong><?php echo $result['score']; ?>%</strong></td>
                            <td>
                                <span class="label <?php echo $result['passed'] ? 'label-success' : 'label-danger'; ?>">
                                    <?php echo $result['passed'] ? __('test.passed') : __('test.failed'); ?>
                                </span>
                            </td>
                            <td><?php echo date('Y-m-d H:i', strtotime($result['completed_at'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php include 'views/layouts/footer.php'; ?>
