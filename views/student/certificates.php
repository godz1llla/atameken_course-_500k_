<?php $title = __('certificates.title'); include 'views/layouts/header.php'; ?>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <h2 class="font-bold m-b-xs"><i class="ion-trophy"></i> <?php echo __('certificates.title'); ?></h2>
    </div>
</div>

<?php if (count($certificates) > 0): ?>
<div class="row">
    <?php foreach ($certificates as $certificate): ?>
    <div class="col-xs-12 col-sm-6 col-md-4 m-b-sm">
        <div class="ibox-content border-bottom text-center">
            <div style="width: 100%; height: 200px; background: #f5f5f5; display: flex; align-items: center; justify-content: center; margin-bottom: 15px;">
                <i class="ion-trophy" style="font-size: 80px; color: #1ab394;"></i>
            </div>
            <h4 class="font-bold m-b-xs"><?php echo htmlspecialchars($certificate['course_title']); ?></h4>
            <p class="text-muted m-b-xs" style="font-size: 12px;">
                <?php echo __('certificates.certificate_number'); ?>: <strong><?php echo htmlspecialchars($certificate['certificate_number']); ?></strong>
            </p>
            <p class="text-muted m-b-sm" style="font-size: 12px;">
                <?php echo __('certificates.issued'); ?>: <?php echo date('F d, Y', strtotime($certificate['issued_at'])); ?>
            </p>
            <a href="/index.php?url=student/certificate/<?php echo $certificate['certificate_number']; ?>" class="btn btn-sm btn-primary">
                <i class="ion-ios-eye"></i> <?php echo __('certificates.view'); ?>
            </a>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php else: ?>
<div class="row">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom text-center" style="padding: 60px 20px;">
            <i class="ion-trophy" style="font-size: 80px; color: #ddd; margin-bottom: 20px;"></i>
            <h3 class="m-b-xs"><?php echo __('certificates.no_certificates'); ?></h3>
            <p class="text-muted"><?php echo __('certificates.complete_courses'); ?></p>
        </div>
    </div>
</div>
<?php endif; ?>

<?php include 'views/layouts/footer.php'; ?>
