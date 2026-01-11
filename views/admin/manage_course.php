<?php $title = __('course.manage') . ': ' . $course['title']; include 'views/layouts/header.php'; ?>
<?php $currentRole = Session::getUserRole(); ?>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <h2 class="font-bold m-b-xs">
            <i class="ion-gear-a"></i> <?php echo __('course.manage'); ?>: <?php echo htmlspecialchars($course['title']); ?>
        </h2>
        <a href="/index.php?url=<?php echo $currentRole === 'admin' ? 'admin/courses' : 'teacher/dashboard'; ?>" class="btn btn-sm btn-default m-t-xs">
            <i class="ion-arrow-left-c"></i> <?php echo __('common.back'); ?>
        </a>
    </div>
</div>

<?php if ($currentRole === 'admin'): ?>
<div class="row m-b-sm">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom">
            <div class="row">
                <div class="col-sm-8">
                    <h3 class="m-b-sm"><i class="ion-calendar"></i> <?php echo __('admin.weekly_tests'); ?></h3>
                </div>
                <div class="col-sm-4 text-right">
                    <a href="/index.php?url=test/createWeeklyTest/<?php echo $course['id']; ?>" class="btn btn-sm btn-success">
                        <i class="ion-plus"></i> <?php echo __('admin.create_weekly_test'); ?>
                    </a>
                </div>
            </div>
            
            <?php if (!empty($weekly_tests)): ?>
            <div class="table-responsive m-t-sm">
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
                                <br><small class="text-muted">
                                    <i class="ion-trophy"></i> <?php echo __('test.passing_score'); ?>: <?php echo $wTest['passing_score']; ?>%
                                    <?php if ($wTest['time_limit']): ?>
                                        | <i class="ion-clock"></i> <?php echo $wTest['time_limit']; ?> <?php echo __('time.minutes'); ?>
                                    <?php endif; ?>
                                </small>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <p class="text-muted text-center m-t-sm"><?php echo __('admin.no_weekly_tests'); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if ($currentRole === 'admin'): ?>
<div class="row m-b-sm">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom">
            <h3 class="m-b-sm"><i class="ion-plus"></i> <?php echo __('admin.add_module'); ?></h3>
            <form method="POST" action="/index.php?url=admin/createModule/<?php echo $course['id']; ?>" class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('course.title'); ?></label>
                    <div class="col-sm-10">
                        <input type="text" name="title" class="form-control" placeholder="<?php echo __('course.title'); ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('course.description'); ?></label>
                    <div class="col-sm-10">
                        <textarea name="description" class="form-control" rows="3" placeholder="<?php echo __('course.description'); ?>"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Order</label>
                    <div class="col-sm-10">
                        <input type="number" name="order_num" class="form-control" value="0">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-2">
                        <button type="submit" class="btn btn-success">
                            <i class="ion-plus"></i> <?php echo __('admin.add_module'); ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<?php foreach ($modules as $module): ?>
<div class="row m-b-sm">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom">
            <div class="row m-b-sm">
                <div class="col-sm-8">
                    <h3 class="m-b-xs"><i class="ion-folder"></i> <?php echo htmlspecialchars($module['title']); ?></h3>
                    <?php if ($module['description']): ?>
                        <p class="text-muted"><?php echo htmlspecialchars($module['description']); ?></p>
                    <?php endif; ?>
                </div>
                <?php if ($currentRole === 'admin'): ?>
                <div class="col-sm-4 text-right">
                    <a href="/index.php?url=admin/deleteModule/<?php echo $module['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('<?php echo __('admin.delete_module'); ?>?')">
                        <i class="ion-trash-a"></i> <?php echo __('admin.delete_module'); ?>
                    </a>
                </div>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($module['lessons'])): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th><?php echo __('course.title'); ?></th>
                            <th><?php echo __('admin.actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($module['lessons'] as $lesson): ?>
                        <tr>
                            <td>
                                <i class="ion-document"></i> <?php echo htmlspecialchars($lesson['title']); ?>
                            </td>
                            <td>
                                <?php if (Session::getUserRole() === 'teacher'): ?>
                                    <a href="/index.php?url=teacher/createHomework/<?php echo $lesson['id']; ?>" class="btn btn-xs btn-success m-r-xs">
                                        <i class="ion-edit"></i> <?php echo __('teacher.create_homework'); ?>
                                    </a>
                                    <?php if (!empty($lessonsWithHomeworks[$lesson['id']])): ?>
                                        <?php foreach ($lessonsWithHomeworks[$lesson['id']] as $hw): ?>
                                            <a href="/index.php?url=teacher/viewSubmissions/<?php echo $hw['id']; ?>" class="btn btn-xs btn-info m-r-xs" title="<?php echo htmlspecialchars($hw['title']); ?>">
                                                <i class="ion-clipboard"></i> <?php echo __('teacher.homework'); ?> (<?php echo $hw['submissions_count']; ?>)
                                            </a>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if ($currentRole === 'admin'): ?>
                                <a href="/index.php?url=admin/editLesson/<?php echo $lesson['id']; ?>" class="btn btn-xs btn-default m-r-xs">
                                    <i class="ion-edit"></i> <?php echo __('common.edit'); ?>
                                </a>
                                <a href="/index.php?url=admin/deleteLesson/<?php echo $lesson['id']; ?>" class="btn btn-xs btn-danger" onclick="return confirm('<?php echo __('common.confirm_delete'); ?>')">
                                    <i class="ion-trash-a"></i>
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
            
            <?php if ($currentRole === 'admin'): ?>
            <div class="m-t-sm" style="border-top: 1px solid #e7eaec; padding-top: 15px;">
                <h4 class="m-b-sm"><i class="ion-plus"></i> <?php echo __('admin.add_lesson'); ?></h4>
                <form method="POST" action="/index.php?url=admin/createLesson/<?php echo $module['id']; ?>" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo __('course.title'); ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="title" class="form-control" placeholder="<?php echo __('course.title'); ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo __('course.description'); ?></label>
                        <div class="col-sm-10">
                            <textarea name="content" class="form-control" rows="3" placeholder="<?php echo __('course.description'); ?>"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">YouTube URL</label>
                        <div class="col-sm-10">
                            <input type="text" name="video_url" class="form-control" placeholder="YouTube URL">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Order</label>
                        <div class="col-sm-10">
                            <input type="number" name="order_num" class="form-control" value="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <button type="submit" class="btn btn-success">
                                <i class="ion-plus"></i> <?php echo __('admin.add_lesson'); ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?php include 'views/layouts/footer.php'; ?>
