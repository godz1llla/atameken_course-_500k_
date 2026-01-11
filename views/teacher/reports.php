<?php $title = __('teacher.reports'); include 'views/layouts/header.php'; ?>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <h2 class="font-bold m-b-xs"><i class="ion-stats-bars"></i> <?php echo __('teacher.reports'); ?></h2>
    </div>
</div>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom">
            <h3 class="m-b-sm"><i class="ion-calendar"></i> <?php echo __('teacher.filter_period'); ?></h3>
            <form method="POST" class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('teacher.start_date'); ?> *</label>
                    <div class="col-sm-4">
                        <input type="date" name="start_date" class="form-control" value="<?php echo date('Y-m-d', strtotime($startDate)); ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('teacher.end_date'); ?> *</label>
                    <div class="col-sm-4">
                        <input type="date" name="end_date" class="form-control" value="<?php echo date('Y-m-d', strtotime($endDate)); ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ion-stats-bars"></i> <?php echo __('teacher.generate_report'); ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row m-b-sm">
    <div class="col-xs-12">
        <ul class="dashboard-metrics list-unstyled clearfix">
            <li class="metric-item col-xs-6 col-sm-4 col-md-3 text-center">
                <span class="metric-icon fa fa-book text-primary"></span>
                <div class="metric-title"><?php echo __('teacher.classes_conducted'); ?></div>
                <div class="metric-value"><?php echo $reportData['total_classes']; ?></div>
            </li>
            <li class="metric-item col-xs-6 col-sm-4 col-md-3 text-center">
                <span class="metric-icon fa fa-clock-o text-success"></span>
                <div class="metric-title"><?php echo __('teacher.total_hours'); ?></div>
                <div class="metric-value"><?php echo $reportData['total_hours']; ?></div>
            </li>
            <li class="metric-item col-xs-6 col-sm-4 col-md-3 text-center">
                <span class="metric-icon fa fa-check-circle text-info"></span>
                <div class="metric-title"><?php echo __('teacher.average_attendance'); ?></div>
                <div class="metric-value"><?php echo $reportData['average_attendance_percent']; ?>%</div>
            </li>
        </ul>
    </div>
</div>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom">
            <h3 class="m-b-sm"><i class="ion-cash"></i> <?php echo __('teacher.salary_calculation'); ?></h3>
            
            <?php if ($ratePerClass > 0): ?>
            <div class="m-t-sm" style="padding: 20px; background: #f5f5f5; border-radius: 5px;">
                <div class="row m-b-sm">
                    <div class="col-sm-6">
                        <span class="text-muted"><?php echo __('teacher.classes_conducted'); ?>:</span>
                    </div>
                    <div class="col-sm-6 text-right">
                        <strong><?php echo $reportData['total_classes']; ?></strong>
                    </div>
                </div>
                <div class="row m-b-sm">
                    <div class="col-sm-6">
                        <span class="text-muted"><?php echo __('teacher.rate_per_class'); ?>:</span>
                    </div>
                    <div class="col-sm-6 text-right">
                        <strong><?php echo number_format($ratePerClass, 2, '.', ' '); ?> ₸</strong>
                    </div>
                </div>
                <div class="row" style="border-top: 2px solid #1ab394; padding-top: 15px; margin-top: 15px;">
                    <div class="col-sm-6">
                        <strong style="font-size: 18px;"><?php echo __('teacher.total_salary'); ?>:</strong>
                    </div>
                    <div class="col-sm-6 text-right">
                        <strong style="font-size: 24px; color: #1ab394;"><?php echo number_format($salary, 2, '.', ' '); ?> ₸</strong>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="alert alert-warning m-t-sm">
                <i class="ion-alert-circled"></i> <?php echo __('teacher.rate_not_set'); ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom">
            <h3 class="m-b-sm"><i class="ion-clipboard"></i> <?php echo __('teacher.classes_detail'); ?></h3>
            
            <?php if (!empty($reportData['detailed_data'])): ?>
            <div class="table-responsive m-t-sm">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th><?php echo __('teacher.class_date'); ?></th>
                            <th><?php echo __('admin.group_name'); ?></th>
                            <th><?php echo __('lesson.title'); ?></th>
                            <th><?php echo __('teacher.students_count'); ?></th>
                            <th><?php echo __('teacher.attendance_percent'); ?></th>
                            <th><?php echo __('teacher.duration'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reportData['detailed_data'] as $detail): ?>
                        <tr>
                            <td>
                                <strong><?php echo date('d.m.Y', strtotime($detail['date'])); ?></strong><br>
                                <small class="text-muted"><?php echo date('H:i', strtotime($detail['date'])); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($detail['group_name']); ?></td>
                            <td>
                                <?php echo htmlspecialchars($detail['title']); ?><br>
                                <small class="text-muted"><?php echo htmlspecialchars($detail['course_title']); ?></small>
                            </td>
                            <td>
                                <strong style="color: #1ab394;"><?php echo $detail['student_count']; ?></strong>
                                <?php if ($detail['present_count'] > 0): ?>
                                    <br><small class="text-success"><?php echo __('teacher.present'); ?>: <?php echo $detail['present_count']; ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="progress" style="height: 20px; width: 100px; display: inline-block; margin-right: 10px;">
                                    <div class="progress-bar" role="progressbar" style="width: <?php echo $detail['attendance_percent']; ?>%; background: <?php echo $detail['attendance_percent'] >= 70 ? '#1ab394' : '#ed5565'; ?>;" aria-valuenow="<?php echo $detail['attendance_percent']; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <strong style="color: <?php echo $detail['attendance_percent'] >= 70 ? '#1ab394' : '#ed5565'; ?>;">
                                    <?php echo $detail['attendance_percent']; ?>%
                                </strong>
                            </td>
                            <td><?php echo $detail['duration_hours']; ?> <?php echo __('teacher.hours'); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center m-t-sm" style="padding: 60px 20px;">
                <i class="ion-stats-bars" style="font-size: 64px; color: #ddd; margin-bottom: 20px;"></i>
                <h3 class="m-b-xs"><?php echo __('teacher.no_classes_in_period'); ?></h3>
                <p class="text-muted"><?php echo __('teacher.no_classes_in_period_description'); ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
