<?php $title = __('nav.users'); include 'views/layouts/header.php'; ?>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <h2 class="font-bold m-b-xs"><?php echo (Session::getUserRole() === 'manager') ? __('manager.students') : __('nav.users'); ?></h2>
        <?php 
        $baseUrl = (Session::getUserRole() === 'manager') ? 'manager' : 'admin';
        ?>
        <a href="/index.php?url=<?php echo $baseUrl; ?>/createUser" class="btn btn-sm btn-success m-t-xs">
            <i class="ion-plus"></i> <?php echo (Session::getUserRole() === 'manager') ? __('manager.add_student') : __('admin.create_user'); ?>
        </a>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th><?php echo __('auth.first_name'); ?></th>
                            <th><?php echo __('auth.email'); ?></th>
                            <th><?php echo __('admin.role'); ?></th>
                            <th><?php echo __('admin.status'); ?></th>
                            <th><?php echo __('admin.actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><span class="label label-default"><?php echo htmlspecialchars($user['role']); ?></span></td>
                            <td>
                                <span class="label <?php echo $user['is_active'] ? 'label-success' : 'label-danger'; ?>">
                                    <?php echo $user['is_active'] ? __('admin.active') : __('admin.inactive'); ?>
                                </span>
                            </td>
                            <td>
                                <?php 
                                $baseUrl = (Session::getUserRole() === 'manager') ? 'manager' : 'admin';
                                ?>
                                <a href="/index.php?url=<?php echo $baseUrl; ?>/editUser/<?php echo $user['id']; ?>" class="btn btn-xs btn-default m-r-xs">
                                    <i class="ion-edit"></i> <?php echo __('common.edit'); ?>
                                </a>
                                <?php if ($user['role'] === 'student' && Session::getUserRole() === 'admin'): ?>
                                    <a href="/index.php?url=admin/studentFinances/<?php echo $user['id']; ?>" class="btn btn-xs btn-info m-r-xs">
                                        <i class="ion-cash"></i> <?php echo __('admin.finances'); ?>
                                    </a>
                                <?php endif; ?>
                                <a href="/index.php?url=<?php echo $baseUrl; ?>/toggleUserStatus/<?php echo $user['id']; ?>" class="btn btn-xs btn-warning m-r-xs">
                                    <i class="ion-refresh"></i>
                                </a>
                                <a href="/index.php?url=<?php echo $baseUrl; ?>/deleteUser/<?php echo $user['id']; ?>" class="btn btn-xs btn-danger" onclick="return confirm('<?php echo __('common.confirm_delete'); ?>')">
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

<?php include 'views/layouts/footer.php'; ?>
