<?php $title = __('profile.title'); include 'views/layouts/header.php'; ?>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <h2 class="font-bold m-b-xs"><i class="ion-person"></i> <?php echo __('profile.title'); ?></h2>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom">
            <form method="POST" enctype="multipart/form-data" class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('profile.avatar'); ?></label>
                    <div class="col-sm-10">
                        <?php if ($user['avatar']): ?>
                            <img src="/public/uploads/<?php echo $user['avatar']; ?>" alt="Avatar" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; margin-bottom: 10px; border: 2px solid #ddd;">
                        <?php endif; ?>
                        <input type="file" name="avatar" class="form-control" accept="image/*">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('auth.first_name'); ?></label>
                    <div class="col-sm-10">
                        <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('auth.last_name'); ?></label>
                    <div class="col-sm-10">
                        <input type="text" name="last_name" class="form-control" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('auth.email'); ?></label>
                    <div class="col-sm-10">
                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('profile.new_password'); ?></label>
                    <div class="col-sm-10">
                        <input type="password" name="new_password" class="form-control">
                        <span class="help-block"><?php echo __('profile.leave_empty'); ?></span>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-2">
                        <button type="submit" class="btn btn-success">
                            <i class="ion-checkmark"></i> <?php echo __('profile.save'); ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
