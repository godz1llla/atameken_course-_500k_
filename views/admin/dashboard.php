<?php $title = __('site_name'); include 'views/layouts/header.php'; ?>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <h2 class="font-bold m-b-xs"><?php echo __('dashboard.welcome'); ?>, Admin!</h2>
        <p class="text-muted"><?php echo __('dashboard.manage_platform'); ?></p>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <ul class="dashboard-metrics list-unstyled clearfix">
            <li class="metric-item col-xs-6 col-sm-4 col-md-3 text-center">
                <span class="metric-icon fa fa-users text-primary"></span>
                <div class="metric-title"><?php echo __('dashboard.total_users'); ?></div>
                <div class="metric-value"><?php echo $stats['total_users']; ?></div>
            </li>
            <li class="metric-item col-xs-6 col-sm-4 col-md-3 text-center">
                <span class="metric-icon fa fa-graduation-cap text-success"></span>
                <div class="metric-title"><?php echo __('dashboard.students'); ?></div>
                <div class="metric-value"><?php echo $stats['total_students']; ?></div>
            </li>
            <li class="metric-item col-xs-6 col-sm-4 col-md-3 text-center">
                <span class="metric-icon fa fa-user-md text-info"></span>
                <div class="metric-title"><?php echo __('dashboard.teachers'); ?></div>
                <div class="metric-value"><?php echo $stats['total_teachers']; ?></div>
            </li>
            <li class="metric-item col-xs-6 col-sm-4 col-md-3 text-center">
                <span class="metric-icon fa fa-book text-warning"></span>
                <div class="metric-title"><?php echo __('dashboard.courses'); ?></div>
                <div class="metric-value"><?php echo $stats['total_courses']; ?></div>
            </li>
        </ul>
    </div>
</div>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom">
            <h3 class="m-b-sm"><?php echo __('dashboard.quick_actions'); ?></h3>
            <a href="/index.php?url=admin/createUser" class="btn btn-sm btn-success m-r-xs">
                <i class="ion-plus"></i> <?php echo __('admin.create_user'); ?>
            </a>
            <a href="/index.php?url=admin/createCourse" class="btn btn-sm btn-success m-r-xs">
                <i class="ion-book"></i> <?php echo __('admin.create_course'); ?>
            </a>
            <a href="/index.php?url=admin/createAchievement" class="btn btn-sm btn-success m-r-xs">
                <i class="ion-trophy"></i> <?php echo __('admin.create_achievement'); ?>
            </a>
            <a href="/index.php?url=admin/enrollments" class="btn btn-sm btn-primary m-r-xs">
                <i class="ion-person-add"></i> <?php echo __('admin.manage_enrollments'); ?>
            </a>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
