<?php $title = __('admin.group_members'); include 'views/layouts/header.php'; ?>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <h2 class="font-bold m-b-xs">
            <i class="ion-person-stalker"></i> <?php echo __('admin.group_members'); ?>: <?php echo htmlspecialchars($group['name']); ?>
        </h2>
        <?php 
        $baseUrl = (isset($is_manager) && $is_manager) ? 'manager' : 'admin';
        ?>
        <a href="/index.php?url=<?php echo $baseUrl; ?>/groups" class="btn btn-sm btn-default m-t-xs">
            <i class="ion-arrow-left-c"></i> <?php echo __('common.back'); ?>
        </a>
    </div>
</div>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom">
            <h3 class="m-b-sm"><?php echo __('admin.current_members'); ?></h3>
            
            <?php if (!empty($members)): ?>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th><?php echo __('auth.first_name'); ?></th>
                            <th><?php echo __('auth.email'); ?></th>
                            <th><?php echo __('admin.actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($members as $member): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($member['first_name'] . ' ' . $member['last_name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($member['email']); ?></td>
                            <td>
                                <form method="POST" style="display: inline-block;" onsubmit="return confirm('<?php echo __('common.confirm_delete'); ?>');">
                                    <input type="hidden" name="action" value="remove_member">
                                    <input type="hidden" name="student_id" value="<?php echo $member['student_id']; ?>">
                                    <button type="submit" class="btn btn-xs btn-danger">
                                        <i class="ion-trash-a"></i> <?php echo __('admin.remove_from_group'); ?>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center m-t-sm" style="padding: 40px 20px;">
                <i class="ion-person-stalker" style="font-size: 48px; color: #ddd; margin-bottom: 15px;"></i>
                <p class="text-muted"><?php echo __('admin.no_members'); ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom">
            <h3 class="m-b-sm"><i class="ion-plus"></i> <?php echo __('admin.add_new_member'); ?></h3>
            
            <?php if (!empty($nonMembers)): ?>
            <form method="POST" class="form-horizontal">
                <input type="hidden" name="action" value="add_member">
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('admin.select_student'); ?></label>
                    <div class="col-sm-10">
                        <select name="student_id" class="form-control" required>
                            <option value=""><?php echo __('admin.select_student'); ?></option>
                            <?php foreach ($nonMembers as $student): ?>
                                <option value="<?php echo $student['id']; ?>">
                                    <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?> (<?php echo htmlspecialchars($student['email']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-2">
                        <button type="submit" class="btn btn-success">
                            <i class="ion-plus"></i> <?php echo __('admin.add_to_group'); ?>
                        </button>
                    </div>
                </div>
            </form>
            <?php else: ?>
            <div class="text-center m-t-sm" style="padding: 40px 20px;">
                <i class="ion-checkmark" style="font-size: 48px; color: #1ab394; margin-bottom: 15px;"></i>
                <p class="text-muted"><?php echo __('admin.no_students_available'); ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
