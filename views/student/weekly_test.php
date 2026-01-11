<?php $title = $test['title']; include 'views/layouts/header.php'; ?>

<div class="container">
    <div style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); padding: 25px; border-radius: 12px; margin-bottom: 30px; border-left: 5px solid var(--warning);">
        <h2 style="margin-bottom: 10px;">📅 <?php echo __('admin.weekly_test'); ?> - <?php echo __('admin.week'); ?> <?php echo $test['week_number']; ?></h2>
        <h1><?php echo $test['title']; ?></h1>
        <p style="margin-top: 15px;"><?php echo $test['description']; ?></p>
        
        <?php if ($test['is_mandatory']): ?>
        <div style="margin-top: 20px; padding: 15px; background: rgba(239, 68, 68, 0.1); border-radius: 8px;">
            <strong>⚠️ <?php echo __('admin.mandatory_test'); ?>!</strong> <?php echo __('admin.must_pass_to_continue'); ?>
        </div>
        <?php endif; ?>
        
        <?php if ($test['time_limit']): ?>
        <p style="margin-top: 15px;"><strong>⏱ <?php echo __('test.time_limit'); ?>:</strong> <?php echo $test['time_limit']; ?> <?php echo __('time.minutes'); ?></p>
        <?php endif; ?>
        
        <?php if ($test['deadline']): ?>
        <p style="margin-top: 10px;"><strong>📅 <?php echo __('admin.deadline'); ?>:</strong> <?php echo date('F d, Y H:i', strtotime($test['deadline'])); ?></p>
        <?php endif; ?>
    </div>
    
    <?php 
    $hasPassed = false;
    foreach ($results as $result) {
        if ($result['passed']) {
            $hasPassed = true;
            break;
        }
    }
    ?>
    
    <?php if ($hasPassed): ?>
        <div class="alert alert-info">✅ <?php echo __('test.already_passed'); ?>! <?php echo __('test.can_retake'); ?></div>
    <?php endif; ?>
    
    <form method="POST" action="/index.php?url=weeklyTest/submit/<?php echo $test['id']; ?>" class="test-form" data-time-limit="<?php echo $test['time_limit']; ?>">
        <?php foreach ($questions as $index => $question): ?>
        <div class="question-block">
            <h3><?php echo __('test.question'); ?> <?php echo $index + 1; ?></h3>
            <p style="font-size: 16px; line-height: 1.8;"><?php echo $question['question']; ?></p>
            
            <div class="answers">
                <?php foreach ($question['answers'] as $answer): ?>
                <label class="answer-option">
                    <input type="radio" name="question_<?php echo $question['id']; ?>" value="<?php echo $answer['id']; ?>" required>
                    <span><?php echo $answer['answer_text']; ?></span>
                </label>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
        
        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 18px; font-size: 18px;">
            ✅ <?php echo __('test.submit'); ?>
        </button>
    </form>
    
    <?php if (count($results) > 0): ?>
    <div class="previous-attempts" style="margin-top: 40px; background: rgba(255, 255, 255, 0.98); padding: 30px; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
        <h3>📜 <?php echo __('test.previous_attempts'); ?></h3>
        <table class="data-table" style="margin-top: 20px;">
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
                    <td data-label="#"><?php echo $result['attempt_number']; ?></td>
                    <td data-label="<?php echo __('test.score'); ?>"><?php echo $result['score']; ?>%</td>
                    <td data-label="<?php echo __('admin.status'); ?>">
                        <span class="status <?php echo $result['passed'] ? 'active' : 'inactive'; ?>">
                            <?php echo $result['passed'] ? __('test.passed') : __('test.failed'); ?>
                        </span>
                    </td>
                    <td data-label="<?php echo __('certificates.issued'); ?>"><?php echo date('Y-m-d H:i', strtotime($result['completed_at'])); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<?php include 'views/layouts/footer.php'; ?>

