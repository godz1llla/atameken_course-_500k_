<?php $title = $course['title']; include 'views/layouts/header.php'; ?>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom">
            <div class="row">
                <div class="col-sm-8">
                    <h2 class="font-bold m-b-xs"><?php echo htmlspecialchars($course['title']); ?></h2>
                    <p class="text-muted"><?php echo htmlspecialchars($course['description']); ?></p>
                </div>
                <div class="col-sm-4 text-right">
                    <?php if (isset($course['teacher_id'])): ?>
                    <a href="/index.php?url=student/conversation/<?php echo $course['teacher_id']; ?>" class="btn btn-sm btn-primary">
                        <i class="ion-ios-email"></i> <?php echo __('student.message_teacher'); ?>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-3">
        <div class="ibox-content border-bottom">
            <h3 class="m-b-sm"><?php echo __('course.progress'); ?></h3>
            <div class="progress m-b-sm" style="height: 20px;">
                <div class="progress-bar" role="progressbar" style="width: <?php echo $progress; ?>%; background: #1ab394;" aria-valuenow="<?php echo $progress; ?>" aria-valuemin="0" aria-valuemax="100">
                    <?php echo $progress; ?>%
                </div>
            </div>
            
            <h4 class="m-b-sm"><?php echo __('course.modules'); ?></h4>
            <div class="list-group">
                <?php foreach ($modules as $module): ?>
                <div class="list-group-item" style="border: none; padding: 10px 0;">
                    <strong><?php echo htmlspecialchars($module['title']); ?></strong>
                    <ul class="list-unstyled m-t-xs" style="padding-left: 15px;">
                        <?php foreach ($module['lessons'] as $lesson): ?>
                        <li class="m-b-xs">
                            <a href="/index.php?url=student/lesson/<?php echo $lesson['id']; ?>" class="<?php echo $lesson['is_completed'] ? 'text-success' : ''; ?>">
                                <i class="ion-<?php echo $lesson['is_completed'] ? 'checkmark' : 'play'; ?>"></i> <?php echo htmlspecialchars($lesson['title']); ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <div class="col-sm-9">
        <?php if (!empty($weekly_tests)): ?>
        <div class="ibox-content border-bottom m-b-sm" style="background: #fffef0; border-left: 4px solid #f0ad4e;">
            <h3 class="m-b-sm"><i class="ion-calendar"></i> <?php echo __('admin.weekly_tests'); ?></h3>
            <div class="table-responsive">
                <table class="table table-hover">
                    <tbody>
                        <?php foreach ($weekly_tests as $wTest): ?>
                        <tr>
                            <td>
                                <strong><?php echo __('admin.week'); ?> <?php echo $wTest['week_number']; ?>: <?php echo htmlspecialchars($wTest['title']); ?></strong>
                                <?php if ($wTest['is_mandatory']): ?>
                                    <span class="label label-warning m-l-xs"><?php echo __('admin.mandatory'); ?></span>
                                <?php endif; ?>
                                <br><small class="text-muted"><?php echo htmlspecialchars($wTest['description']); ?></small>
                            </td>
                            <td style="text-align: right;">
                                <?php if ($wTest['is_passed']): ?>
                                    <span class="label label-success"><i class="ion-checkmark"></i> <?php echo __('test.passed'); ?></span>
                                <?php else: ?>
                                    <a href="/index.php?url=weeklyTest/take/<?php echo $wTest['id']; ?>" class="btn btn-sm btn-warning">
                                        <i class="ion-edit"></i> <?php echo __('lesson.take_test'); ?>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if (!empty($mandatory_not_passed)): ?>
            <div class="alert alert-warning m-t-sm">
                <i class="ion-alert-circled"></i> <strong><?php echo __('admin.attention'); ?>!</strong> 
                <?php echo __('admin.mandatory_tests_remaining'); ?>: <?php echo count($mandatory_not_passed); ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
