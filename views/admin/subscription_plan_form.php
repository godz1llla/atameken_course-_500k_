<?php 
$isEdit = isset($plan);
$title = $isEdit ? __('admin.edit_plan') : __('admin.create_plan'); 
include 'views/layouts/header.php'; 
?>

<div class="content-area">
    <div class="card">
        <h1><?php echo $isEdit ? '📝 ' . __('admin.edit_plan') : '➕ ' . __('admin.create_plan'); ?></h1>
        
        <form method="POST" class="form" style="margin-top: 30px;">
            <div class="form-group">
                <label><?php echo __('admin.plan_name'); ?> *</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($plan['name'] ?? ''); ?>" required placeholder="<?php echo __('admin.plan_name_placeholder'); ?>" maxlength="255">
            </div>
            
            <div class="form-group">
                <label><?php echo __('admin.price'); ?> *</label>
                <input type="number" name="price" value="<?php echo htmlspecialchars($plan['price'] ?? ''); ?>" required min="0" step="0.01" placeholder="0.00">
                <small style="color: var(--gray); margin-top: 5px; display: block;"><?php echo __('admin.price_description'); ?></small>
            </div>
            
            <div class="form-group">
                <label><?php echo __('admin.duration_days'); ?> *</label>
                <input type="number" name="duration_days" value="<?php echo htmlspecialchars($plan['duration_days'] ?? ''); ?>" required min="1" placeholder="30">
                <small style="color: var(--gray); margin-top: 5px; display: block;"><?php echo __('admin.duration_days_description'); ?></small>
            </div>
            
            <div class="form-group">
                <label><?php echo __('admin.lesson_count'); ?> *</label>
                <input type="number" name="lesson_count" value="<?php echo htmlspecialchars($plan['lesson_count'] ?? ''); ?>" required min="0" placeholder="10">
                <small style="color: var(--gray); margin-top: 5px; display: block;"><?php echo __('admin.lesson_count_description'); ?></small>
            </div>
            
            <div class="form-group">
                <label><?php echo __('course.description'); ?></label>
                <textarea name="description" rows="4" placeholder="<?php echo __('admin.plan_description_placeholder'); ?>"><?php echo htmlspecialchars($plan['description'] ?? ''); ?></textarea>
            </div>
            
            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" <?php echo (isset($plan) && $plan['is_active']) || !isset($plan) ? 'checked' : ''; ?> style="width: 20px; height: 20px; cursor: pointer;">
                    <span><?php echo __('admin.is_active'); ?></span>
                </label>
                <small style="color: var(--gray); margin-top: 5px; display: block;"><?php echo __('admin.is_active_description'); ?></small>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-success">💾 <?php echo __('common.save'); ?></button>
                <a href="/index.php?url=admin/subscriptionPlans" class="btn btn-outline">❌ <?php echo __('common.cancel'); ?></a>
            </div>
        </form>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>

