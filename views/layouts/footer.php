<?php if (Session::isLoggedIn()): ?>
        </div> <!-- .wrapper crm-content -->
    </div> <!-- #page-wrapper -->
</div> <!-- #wrapper -->
<?php else: ?>
</div> <!-- .main-container -->
<?php endif; ?>

<footer class="footer no-padding">
    <div class="container m-t-sm text-center">
        <p class="text-muted no-padding m-b-sm">
            © <a href="/" target="_blank" class="text-success crm-thin-link">atameken course</a>
            &nbsp;
            <?php echo date('Y'); ?>
            |
            <a href="tel:<?php echo __('landing.phone_number_link'); ?>" class="text-success crm-thin-link"><?php echo __('landing.contact_phone_label'); ?>: <?php echo __('landing.phone_number'); ?></a>
            |
            <a href="https://www.instagram.com/atameken.course/" target="_blank" class="text-success crm-thin-link"><?php echo __('landing.contact_instagram_label'); ?></a>
        </p>
    </div>
</footer>

<!-- Modal Windows -->
<div id="common-modal-lg" class="modal" role="dialog" style="display: none;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content common-modal-content"></div>
    </div>
</div>

<div id="common-modal" class="modal" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content common-modal-content"></div>
    </div>
</div>

<div id="common-modal-content" class="hide">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title"><?php echo __('common.loading'); ?></h4>
    </div>
    <div class="modal-body">
        <div class="spiner-example">
            <div class="sk-spinner sk-spinner-wave">
                <div class="sk-rect1"></div>
                <div class="sk-rect2"></div>
                <div class="sk-rect3"></div>
                <div class="sk-rect4"></div>
                <div class="sk-rect5"></div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white btn-w-m btn-sm" data-dismiss="modal"><?php echo __('common.cancel'); ?></button>
    </div>
</div>

<div id="common-modal-sm" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content common-modal-content"></div>
    </div>
</div>

<div id="common-modal-over" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content common-modal-content"></div>
    </div>
</div>

<div id="common-modal-ext" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content common-modal-content"></div>
    </div>
</div>

<!-- JavaScript Files from Template -->
<!-- Socket.io and Highcharts (if needed) -->
<?php if (file_exists(__DIR__ . '/../../public/socket.io/socket.io.js')): ?>
<script src="/public/socket.io/socket.io.js"></script>
<?php endif; ?>
<?php if (file_exists(__DIR__ . '/../../public/highcharts/min.js')): ?>
<script src="/public/highcharts/min.js"></script>
<?php endif; ?>

<!-- Core JS Libraries -->
<script src="/public/jquery.min.js"></script>
<script src="/public/jquery-ui.min.js"></script>
<script src="/public/bootstrap/min.js"></script>

<!-- Yii Framework (if exists) -->
<?php if (file_exists(__DIR__ . '/../../public/yii/yii.js')): ?>
<script src="/public/yii/yii.js"></script>
<?php endif; ?>

<!-- Datepicker -->
<script src="/public/datapicker/datepicker.min.js"></script>
<?php 
$currentLang = Language::getCurrentLanguage();
if ($currentLang === 'ru' && file_exists(__DIR__ . '/../../public/datapicker/datepicker.ru.min.js')):
?>
<script src="/public/datapicker/datepicker.ru.min.js"></script>
<?php elseif ($currentLang === 'kk' && file_exists(__DIR__ . '/../../public/datapicker/datepicker.ro.min.js')): ?>
<script src="/public/datapicker/datepicker.ro.min.js"></script>
<?php elseif ($currentLang === 'en' && file_exists(__DIR__ . '/../../public/datapicker/datepicker.ja.min.js')): ?>
<script src="/public/datapicker/datepicker.ja.min.js"></script>
<?php endif; ?>

<!-- Chosen Select -->
<script src="/public/chosen/chosen.js"></script>

<!-- FullCalendar (conditional) -->
<?php 
$currentUrl = $_GET['url'] ?? '';
if (strpos($currentUrl, 'schedule') !== false || (isset($title) && strpos(strtolower($title), 'schedule') !== false)):
?>
<script src="/public/fullcalendar/moment.js"></script>
<script src="/public/fullcalendar/min.js"></script>
<?php endif; ?>

<!-- TinyMCE -->
<script src="/public/tinymce/tinymce.min.js"></script>
<?php if ($currentLang === 'ru' && file_exists(__DIR__ . '/../../public/tinymce/tinymce.ru.js')): ?>
<script src="/public/tinymce/tinymce.ru.js"></script>
<?php endif; ?>

<!-- Toastr -->
<script src="/public/toastr/toastr.js"></script>

<!-- SweetAlert -->
<script src="/public/sweetalert/min.js"></script>

<!-- Colorpicker -->
<?php if (file_exists(__DIR__ . '/../../public/colorpicker/spectrum.min.js')): ?>
<script src="/public/colorpicker/spectrum.min.js"></script>
<?php endif; ?>

<!-- Plugins -->
<script src="/public/plugins/plupload.min.js"></script>
<script src="/public/plugins/jquery.inputmask-multi.min.js"></script>
<script src="/public/plugins/jquery.inputmask.bundle.min.js"></script>
<script src="/public/plugins/jquery.maskedinput.min.js"></script>
<script src="/public/plugins/phone-mask.init.js"></script>

<!-- Yii Additional (if exists) -->
<?php if (file_exists(__DIR__ . '/../../public/yii/dialog.min.js')): ?>
<script src="/public/yii/dialog.min.js"></script>
<script src="/public/yii/dialog-yii.min.js"></script>
<script src="/public/yii/yii.activeForm.js"></script>
<script src="/public/yii/jquery.pjax.js"></script>
<?php endif; ?>

<!-- Tags Input -->
<script src="/public/tagsinput/bootstrap-tagsinput.min.js"></script>

<!-- DateTime Picker -->
<script src="/public/datetime-picker/bootstrap-datetimepicker.min.js"></script>

<!-- Main JS -->
<script src="/public/js/main.js"></script>

<script>
// Menu toggle functionality
jQuery(function($) {
    $('.crm-menu-toggle').on('click', function(e) {
        e.preventDefault();
        $('#wrapper').toggleClass('sidebar-collapsed');
    });
    
    // Mobile menu toggle
    $('.dropdown-toggle[data-toggle="dropdown"]').on('click', function(e) {
        if ($(window).width() <= 767) {
            e.preventDefault();
            $(this).next('.dropdown-menu').toggle();
        }
    });
    
    // Close mobile menu when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.dropdown').length) {
            $('.dropdown-menu').hide();
        }
    });
});
</script>

<?php if (!Session::isLoggedIn()): ?>
<script>
// Mobile menu toggle for non-logged users
function toggleMobileMenu() {
    const navLinks = document.getElementById('navLinks');
    if (navLinks) {
        navLinks.classList.toggle('active');
    }
}

// Закрыть меню при клике на ссылку
document.querySelectorAll('.nav-links a').forEach(link => {
    link.addEventListener('click', function() {
        const navLinks = document.getElementById('navLinks');
        if (navLinks) {
            navLinks.classList.remove('active');
        }
    });
});

// Закрыть меню при клике вне его
document.addEventListener('click', function(e) {
    const navLinks = document.getElementById('navLinks');
    const toggle = document.querySelector('.mobile-menu-toggle');
    
    if (navLinks && toggle && !navLinks.contains(e.target) && !toggle.contains(e.target)) {
        navLinks.classList.remove('active');
    }
});
</script>
<?php endif; ?>
</body>
</html>
