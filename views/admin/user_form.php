<?php $title = isset($user) ? __('admin.edit_user') : __('admin.create_user'); include 'views/layouts/header.php'; ?>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <h2 class="font-bold m-b-xs"><?php echo isset($user) ? __('admin.edit_user') : __('admin.create_user'); ?></h2>
        <?php 
        $baseUrl = (Session::getUserRole() === 'manager') ? 'manager' : 'admin';
        ?>
        <a href="/index.php?url=<?php echo $baseUrl; ?>/users" class="btn btn-sm btn-default m-t-xs">
            <i class="ion-arrow-left-c"></i> <?php echo __('common.back'); ?>
        </a>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom">
            <form method="POST" class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('auth.first_name'); ?></label>
                    <div class="col-sm-10">
                        <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('auth.last_name'); ?></label>
                    <div class="col-sm-10">
                        <input type="text" name="last_name" class="form-control" value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('auth.email'); ?></label>
                    <div class="col-sm-10">
                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('auth.password'); ?></label>
                    <div class="col-sm-10">
                        <input type="password" name="password" class="form-control" <?php echo !isset($user) ? 'required' : ''; ?>>
                        <?php if (isset($user)): ?>
                            <span class="help-block m-b-none"><?php echo __('profile.leave_empty'); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('admin.role'); ?></label>
                    <div class="col-sm-10">
                        <?php if (isset($is_manager) && $is_manager): ?>
                            <input type="hidden" name="role" value="student">
                            <input type="text" class="form-control" value="<?php echo __('dashboard.students'); ?>" disabled style="background: #f5f5f5; cursor: not-allowed;">
                            <span class="help-block m-b-none"><?php echo __('manager.can_only_create_students'); ?></span>
                        <?php else: ?>
                            <select name="role" class="form-control" required id="roleSelect" onchange="toggleRateField()">
                                <option value="student" <?php echo (isset($user) && $user['role'] === 'student') ? 'selected' : ''; ?>><?php echo __('dashboard.students'); ?></option>
                                <option value="teacher" <?php echo (isset($user) && $user['role'] === 'teacher') ? 'selected' : ''; ?>><?php echo __('dashboard.teachers'); ?></option>
                                <option value="manager" <?php echo (isset($user) && $user['role'] === 'manager') ? 'selected' : ''; ?>><?php echo __('manager.manager'); ?></option>
                                <option value="admin" <?php echo (isset($user) && $user['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                            </select>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php if (!isset($is_manager) || !$is_manager): ?>
                <div class="form-group" id="rateField" style="<?php echo (isset($user) && $user['role'] === 'teacher') || (!isset($user)) ? '' : 'display: none;'; ?>">
                    <label class="col-sm-2 control-label"><?php echo __('teacher.rate_per_class'); ?></label>
                    <div class="col-sm-10">
                        <input type="number" name="rate_per_class" class="form-control" value="<?php echo isset($user['rate_per_class']) ? htmlspecialchars($user['rate_per_class']) : ''; ?>" min="0" step="0.01" placeholder="0.00">
                        <span class="help-block m-b-none"><?php echo __('teacher.rate_per_class_help'); ?></span>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-2">
                        <button type="submit" class="btn btn-success">
                            <i class="ion-checkmark"></i> <?php echo __('common.save'); ?>
                        </button>
                        <a href="/index.php?url=<?php echo $baseUrl; ?>/users" class="btn btn-default">
                            <i class="ion-close"></i> <?php echo __('common.cancel'); ?>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleRateField() {
    const roleSelect = document.getElementById('roleSelect');
    const rateField = document.getElementById('rateField');
    
    if (roleSelect && rateField) {
        if (roleSelect.value === 'teacher') {
            rateField.style.display = '';
        } else {
            rateField.style.display = 'none';
        }
    }
}
</script>

<?php include 'views/layouts/footer.php'; ?>
