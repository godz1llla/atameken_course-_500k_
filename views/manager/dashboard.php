<?php $title = __('manager.dashboard'); include 'views/layouts/header.php'; ?>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <h2 class="font-bold m-b-xs"><?php echo __('manager.welcome'); ?></h2>
        <p class="text-muted"><?php echo __('manager.dashboard_description'); ?></p>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <ul class="dashboard-metrics list-unstyled clearfix">
            <li class="metric-item col-xs-6 col-sm-4 col-md-3 text-center">
                <span class="metric-icon fa fa-graduation-cap text-success"></span>
                <div class="metric-title"><?php echo __('dashboard.students'); ?></div>
                <div class="metric-value"><?php echo $stats['total_students']; ?></div>
            </li>
            <li class="metric-item col-xs-6 col-sm-4 col-md-3 text-center">
                <span class="metric-icon fa fa-users text-info"></span>
                <div class="metric-title"><?php echo __('admin.groups'); ?></div>
                <div class="metric-value"><?php echo $stats['total_groups']; ?></div>
            </li>
        </ul>
    </div>
</div>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom">
            <h3 class="m-b-sm"><?php echo __('dashboard.quick_actions'); ?></h3>
            <a href="/index.php?url=manager/createUser" class="btn btn-sm btn-success m-r-xs">
                <i class="ion-plus"></i> <?php echo __('manager.add_student'); ?>
            </a>
            <a href="/index.php?url=manager/createGroup" class="btn btn-sm btn-success m-r-xs">
                <i class="ion-person-stalker"></i> <?php echo __('admin.create_group'); ?>
            </a>
            <a href="/index.php?url=manager/users" class="btn btn-sm btn-primary m-r-xs">
                <i class="ion-person"></i> <?php echo __('manager.view_students'); ?>
            </a>
            <a href="/index.php?url=manager/groups" class="btn btn-sm btn-primary">
                <i class="ion-person-stalker"></i> <?php echo __('admin.groups'); ?>
            </a>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
