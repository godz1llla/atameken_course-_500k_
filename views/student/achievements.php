<?php $title = __('achievements.title'); include 'views/layouts/header.php'; ?>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <h2 class="font-bold m-b-xs"><i class="ion-trophy"></i> <?php echo __('achievements.title'); ?></h2>
    </div>
</div>

<div class="row">
    <?php foreach ($achievements as $achievement): ?>
    <div class="col-xs-12 col-sm-6 col-md-4 m-b-sm">
        <div class="ibox-content border-bottom text-center" style="<?php echo $achievement['earned'] ? '' : 'opacity: 0.6;'; ?>">
            <?php if ($achievement['icon']): ?>
                <img src="/public/uploads/<?php echo $achievement['icon']; ?>" alt="<?php echo htmlspecialchars($achievement['title']); ?>" style="width: 120px; height: 120px; object-fit: contain; margin-bottom: 15px;">
            <?php else: ?>
                <div style="width: 120px; height: 120px; background: <?php echo $achievement['earned'] ? '#1ab394' : '#f5f5f5'; ?>; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 15px;">
                    <i class="ion-trophy" style="font-size: 60px; color: <?php echo $achievement['earned'] ? '#fff' : '#ccc'; ?>;"></i>
                </div>
            <?php endif; ?>
            <h4 class="font-bold m-b-xs"><?php echo htmlspecialchars($achievement['title']); ?></h4>
            <p class="text-muted m-b-sm" style="font-size: 13px;"><?php echo htmlspecialchars($achievement['description']); ?></p>
            <?php if ($achievement['earned']): ?>
                <span class="label label-success"><i class="ion-checkmark"></i> <?php echo __('achievements.earned'); ?></span>
            <?php else: ?>
                <span class="label label-default"><i class="ion-locked"></i> <?php echo __('achievements.locked'); ?></span>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php include 'views/layouts/footer.php'; ?>
