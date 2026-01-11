<?php $title = __('admin.groups'); include 'views/layouts/header.php'; ?>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <h2 class="font-bold m-b-xs"><?php echo __('admin.groups'); ?></h2>
        <?php 
        $baseUrl = (isset($is_manager) && $is_manager) ? 'manager' : 'admin';
        ?>
        <a href="/index.php?url=<?php echo $baseUrl; ?>/createGroup" class="btn btn-sm btn-success m-t-xs">
            <i class="ion-plus"></i> <?php echo __('admin.create_group'); ?>
        </a>
    </div>
</div>

<?php if (!empty($groups)): ?>
<div class="row">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th><?php echo __('admin.group_name'); ?></th>
                            <th><?php echo __('course.title'); ?></th>
                            <th><?php echo __('course.teacher'); ?></th>
                            <th><?php echo __('admin.actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($groups as $group): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($group['id']); ?></td>
                            <td><strong><?php echo htmlspecialchars($group['name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($group['course_title'] ?? 'N/A'); ?></td>
                            <td>
                                <?php if (!empty($group['teacher_name'])): ?>
                                    <?php echo htmlspecialchars($group['teacher_name']); ?>
                                <?php else: ?>
                                    <span class="text-muted"><?php echo __('admin.no_teacher'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php 
                                $baseUrl = (isset($is_manager) && $is_manager) ? 'manager' : 'admin';
                                ?>
                                <a href="/index.php?url=<?php echo $baseUrl; ?>/groupDetails/<?php echo $group['id']; ?>" class="btn btn-xs btn-info m-r-xs">
                                    <i class="ion-person-stalker"></i> <?php echo __('admin.members'); ?>
                                </a>
                                <a href="/index.php?url=<?php echo $baseUrl; ?>/editGroup/<?php echo $group['id']; ?>" class="btn btn-xs btn-default m-r-xs">
                                    <i class="ion-edit"></i> <?php echo __('common.edit'); ?>
                                </a>
                                <a href="/index.php?url=<?php echo $baseUrl; ?>/deleteGroup/<?php echo $group['id']; ?>" class="btn btn-xs btn-danger" onclick="return confirm('<?php echo __('common.confirm_delete'); ?>')">
                                    <i class="ion-trash-a"></i> <?php echo __('common.delete'); ?>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
<div class="row">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom text-center" style="padding: 60px 20px;">
            <i class="ion-person-stalker" style="font-size: 64px; color: #ddd; margin-bottom: 20px;"></i>
            <h3 class="m-b-xs"><?php echo __('admin.no_groups'); ?></h3>
            <p class="text-muted m-b-sm"><?php echo __('admin.no_groups'); ?></p>
            <?php 
            $baseUrl = (isset($is_manager) && $is_manager) ? 'manager' : 'admin';
            ?>
            <a href="/index.php?url=<?php echo $baseUrl; ?>/createGroup" class="btn btn-sm btn-success">
                <i class="ion-plus"></i> <?php echo __('admin.create_group'); ?>
            </a>
        </div>
    </div>
</div>
<?php endif; ?>

<?php include 'views/layouts/footer.php'; ?>
