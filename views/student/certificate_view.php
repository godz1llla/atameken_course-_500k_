<?php $title = __('certificates.title'); include 'views/layouts/header.php'; ?>

<div class="container">
    <div class="certificate-view">
        <div class="certificate-border">
            <h1><?php echo __('certificates.of_completion'); ?></h1>
            <p class="certificate-text"><?php echo __('certificates.this_certifies'); ?></p>
            <h2 class="certificate-name"><?php echo $certificate['user_name']; ?></h2>
            <p class="certificate-text"><?php echo __('certificates.has_completed'); ?></p>
            <h3 class="certificate-course"><?php echo $certificate['course_title']; ?></h3>
            <p class="certificate-date"><?php echo __('certificates.issued_on'); ?>: <?php echo date('F d, Y', strtotime($certificate['issued_at'])); ?></p>
            <p class="certificate-number"><?php echo __('certificates.certificate_number'); ?>: <?php echo $certificate['certificate_number']; ?></p>
        </div>
        <button onclick="window.print()" class="btn btn-primary">🖨️ <?php echo __('certificates.print'); ?></button>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
