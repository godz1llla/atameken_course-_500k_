<?php $title = __('admin.subscription_plans'); include 'views/layouts/header.php'; ?>

<div class="content-area">
    <div class="page-header">
        <h1>💳 <?php echo __('admin.subscription_plans'); ?></h1>
        <a href="/index.php?url=admin/createPlan" class="btn btn-primary">➕ <?php echo __('admin.create_plan'); ?></a>
    </div>
    
    <?php if (!empty($plans)): ?>
    <div class="card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th><?php echo __('admin.plan_name'); ?></th>
                    <th><?php echo __('admin.price'); ?></th>
                    <th><?php echo __('admin.duration_days'); ?></th>
                    <th><?php echo __('admin.lesson_count'); ?></th>
                    <th><?php echo __('admin.status'); ?></th>
                    <th><?php echo __('admin.actions'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($plans as $plan): ?>
                <tr>
                    <td data-label="ID"><?php echo htmlspecialchars($plan['id']); ?></td>
                    <td data-label="<?php echo __('admin.plan_name'); ?>">
                        <strong><?php echo htmlspecialchars($plan['name']); ?></strong>
                        <?php if (!empty($plan['description'])): ?>
                            <br><small style="color: var(--gray);"><?php echo htmlspecialchars(substr($plan['description'], 0, 100)); ?><?php echo strlen($plan['description']) > 100 ? '...' : ''; ?></small>
                        <?php endif; ?>
                    </td>
                    <td data-label="<?php echo __('admin.price'); ?>">
                        <strong><?php echo number_format($plan['price'], 2, '.', ' '); ?> ₸</strong>
                    </td>
                    <td data-label="<?php echo __('admin.duration_days'); ?>">
                        <?php echo htmlspecialchars($plan['duration_days']); ?> <?php echo __('admin.days'); ?>
                    </td>
                    <td data-label="<?php echo __('admin.lesson_count'); ?>">
                        <?php echo htmlspecialchars($plan['lesson_count']); ?>
                    </td>
                    <td data-label="<?php echo __('admin.status'); ?>">
                        <?php if ($plan['is_active']): ?>
                            <span class="badge badge-success">✅ <?php echo __('admin.active'); ?></span>
                        <?php else: ?>
                            <span class="badge badge-danger">❌ <?php echo __('admin.inactive'); ?></span>
                        <?php endif; ?>
                    </td>
                    <td data-label="<?php echo __('admin.actions'); ?>" class="actions">
                        <a href="/index.php?url=admin/editPlan/<?php echo $plan['id']; ?>" class="btn btn-small btn-primary">📝 <?php echo __('common.edit'); ?></a>
                        <?php if ($plan['is_active']): ?>
                            <a href="/index.php?url=admin/deactivatePlan/<?php echo $plan['id']; ?>" class="btn btn-small btn-warning" onclick="return confirm('<?php echo __('admin.confirm_deactivate'); ?>')">🚫 <?php echo __('admin.deactivate'); ?></a>
                        <?php else: ?>
                            <a href="/index.php?url=admin/activatePlan/<?php echo $plan['id']; ?>" class="btn btn-small btn-success" onclick="return confirm('<?php echo __('admin.confirm_activate'); ?>')">✅ <?php echo __('admin.activate'); ?></a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="card">
        <div style="text-align: center; padding: 60px 20px;">
            <div style="font-size: 64px; margin-bottom: 20px;">💳</div>
            <h2 style="color: var(--gray); margin-bottom: 15px;"><?php echo __('admin.no_plans'); ?></h2>
            <p style="color: var(--gray); margin-bottom: 30px;"><?php echo __('admin.no_plans_description'); ?></p>
            <a href="/index.php?url=admin/createPlan" class="btn btn-primary">➕ <?php echo __('admin.create_plan'); ?></a>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include 'views/layouts/footer.php'; ?>

