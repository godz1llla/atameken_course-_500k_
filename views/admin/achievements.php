<?php $title = __('nav.achievements'); include 'views/layouts/header.php'; ?>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <h2 class="font-bold m-b-xs"><?php echo __('nav.achievements'); ?></h2>
        <a href="/index.php?url=admin/createAchievement" class="btn btn-sm btn-success m-t-xs">
            <i class="ion-plus"></i> <?php echo __('admin.create_achievement'); ?>
        </a>
    </div>
</div>

<div class="row">
    <?php foreach ($achievements as $achievement): ?>
    <div class="col-xs-12 col-sm-6 col-md-4 m-b-sm">
        <div class="ibox-content border-bottom text-center">
            <?php if ($achievement['icon']): ?>
                <img src="/public/uploads/<?php echo $achievement['icon']; ?>" alt="Achievement Icon" style="width: 120px; height: 120px; object-fit: contain; margin-bottom: 15px;">
            <?php else: ?>
                <div style="width: 120px; height: 120px; background: #f5f5f5; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 15px;">
                    <i class="ion-trophy" style="font-size: 60px; color: #1ab394;"></i>
                </div>
            <?php endif; ?>
            <h4 class="font-bold m-b-xs"><?php echo htmlspecialchars($achievement['title']); ?></h4>
            <p class="text-muted m-b-sm" style="font-size: 13px;"><?php echo htmlspecialchars($achievement['description']); ?></p>
            <p class="text-muted m-b-xs" style="font-size: 12px;">
                <i class="ion-clipboard"></i> Type: <?php echo htmlspecialchars($achievement['condition_type']); ?>
            </p>
            <p class="text-muted m-b-sm" style="font-size: 12px;">
                <i class="ion-flag"></i> Value: <?php echo htmlspecialchars($achievement['condition_value']); ?>
            </p>
            <a href="/index.php?url=admin/deleteAchievement/<?php echo $achievement['id']; ?>" class="btn btn-xs btn-danger" onclick="return confirm('<?php echo __('common.confirm_delete'); ?>')">
                <i class="ion-trash-a"></i> <?php echo __('common.delete'); ?>
            </a>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php include 'views/layouts/footer.php'; ?>
