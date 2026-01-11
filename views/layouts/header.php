<?php
require_once __DIR__ . '/../../config/config.php';
?>
<!DOCTYPE html>
<html lang="<?php echo Language::getCurrentLanguage(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
    <title><?php echo $title ?? 'LMS System'; ?></title>
    
    <!-- CSS Files from Template (matching TEMPLATE_ATAMEKEN order) -->
    <link href="<?php echo BASE_URL; ?>/public/jquery-ui.min.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>/public/bootstrap/min.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>/public/datapicker/datepicker3.min.css" rel="stylesheet">
    <!-- FontAwesome CDN (v4.3.0) - заменено с локального из-за отсутствия файлов шрифтов -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>/public/flaticon/flaticon.css" rel="stylesheet">
    <!-- Ionicons CDN (v2.0.0) - заменено с локального из-за отсутствия файлов шрифтов -->
    <link href="https://cdn.jsdelivr.net/npm/ionicons@2.0.0/css/ionicons.min.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>/public/chosen/chosen.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>/public/toastr/toastr.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>/public/fullcalendar/min.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>/public/sweetalert/css.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>/public/colorpicker/spectrum.min.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>/public/fa/animate.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>/public/tagsinput/bootstrap-tagsinput.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>/public/datetime-picker/bootstrap-datetimepicker.css" rel="stylesheet">
    <!-- Custom styles -->
    <link href="<?php echo BASE_URL; ?>/public/css/style.css?v=<?php echo time(); ?>" rel="stylesheet">
</head>
<body>
<?php if (Session::isLoggedIn()): ?>
<?php
    require_once __DIR__ . '/../../models/User.php';
    $userModel = new User();
    // Безопасное получение ID пользователя
    $uid = Session::getUserId();
    $currentUser = $uid ? $userModel->findById($uid) : null;
    
    $userName = $currentUser['name'] ?? Session::get('user_name', 'User');
    $userRole = Session::getUserRole();
    $currentUrl = $_GET['url'] ?? '';
    
    // Avatar Logic
    $userAvatar = 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40"><circle cx="20" cy="20" r="20" fill="#ddd"/><text x="20" y="20" font-size="16" text-anchor="middle" dy=".3em" fill="#999">' . mb_substr($userName, 0, 1, 'UTF-8') . '</text></svg>');
    
    // Проверка аватара с правильным путем
    if (isset($currentUser['avatar_path']) && !empty($currentUser['avatar_path'])) {
        $avatarPhysicalPath = $_SERVER['DOCUMENT_ROOT'] . $currentUser['avatar_path'];
        if(file_exists($avatarPhysicalPath)){
             $userAvatar = BASE_URL . $currentUser['avatar_path'];
        }
    }
?>
<div class="border-bottom no-padding m-b-sm white-bg">
    <nav class="navbar no-margins no-padding" role="navigation">
        <ul class="nav navbar-top-links no-margins no-padding">
            <li class="pull-left border-right hidden-xs nav-action">
                <a href="#" title="<?php echo __('common.toggle_menu'); ?>" class="crm-menu-toggle" style="padding-top: 5px">
                    <big class="fa-2x">
                        <i class="ion-navicon"></i>
                    </big>
                </a>
            </li>
            <li class="border-right alfa-header text-center pull-left no-padding no-margins hidden-xs">
                <a href="<?php echo BASE_URL; ?>/index.php?url=<?php echo strtolower($userRole); ?>/dashboard" style="padding-top: 12px">
                    <img src="<?php echo BASE_URL; ?>/public/images/logo.png" alt="ATAMEKEN" style="width: 140px; height: 30px; object-fit: contain;">
                </a>
            </li>
            <li class="dropdown pull-left border-right visible-xs nav-action">
                <a aria-expanded="false" role="button" href="#" class="dropdown-toggle font-noraml" data-toggle="dropdown" title="<?php echo __('common.toggle_menu'); ?>" style="padding-top: 5px">
                    <big class="fa-2x m-l-xxs m-r-xxs">
                        <i class="ion-navicon"></i>
                    </big>
                </a>
                <ul role="menu" class="dropdown-menu">
                    <?php if ($userRole === 'admin'): ?>
                        <li><a href="<?php echo BASE_URL; ?>/index.php?url=admin/dashboard"><i class="ion-map"></i> <?php echo __('nav.dashboard'); ?></a></li>
                        <li><a href="<?php echo BASE_URL; ?>/index.php?url=admin/users"><i class="ion-person-stalker"></i> <?php echo __('nav.users'); ?></a></li>
                        <li><a href="<?php echo BASE_URL; ?>/index.php?url=admin/courses"><i class="ion-book"></i> <?php echo __('nav.courses'); ?></a></li>
                        <li><a href="<?php echo BASE_URL; ?>/index.php?url=admin/groups"><i class="ion-person-stalker"></i> <?php echo __('admin.groups'); ?></a></li>
                        <li><a href="<?php echo BASE_URL; ?>/index.php?url=admin/schedule"><i class="ion-calendar"></i> <?php echo __('admin.schedule'); ?></a></li>
                        <li><a href="<?php echo BASE_URL; ?>/index.php?url=admin/achievements"><i class="ion-trophy"></i> <?php echo __('nav.achievements'); ?></a></li>
                        <li><a href="<?php echo BASE_URL; ?>/index.php?url=admin/statistics"><i class="ion-stats-bars"></i> <?php echo __('nav.statistics'); ?></a></li>
                        <li><a href="<?php echo BASE_URL; ?>/index.php?url=admin/enrollments"><i class="ion-person-add"></i> <?php echo __('nav.enrollments'); ?></a></li>
                    <?php elseif ($userRole === 'manager'): ?>
                        <li><a href="<?php echo BASE_URL; ?>/index.php?url=manager/dashboard"><i class="ion-map"></i> <?php echo __('nav.dashboard'); ?></a></li>
                        <li><a href="<?php echo BASE_URL; ?>/index.php?url=manager/users"><i class="ion-person"></i> <?php echo __('manager.students'); ?></a></li>
                        <li><a href="<?php echo BASE_URL; ?>/index.php?url=manager/groups"><i class="ion-person-stalker"></i> <?php echo __('admin.groups'); ?></a></li>
                    <?php elseif ($userRole === 'teacher'): ?>
                        <li><a href="<?php echo BASE_URL; ?>/index.php?url=teacher/dashboard"><i class="ion-map"></i> <?php echo __('nav.dashboard'); ?></a></li>
                        <li><a href="<?php echo BASE_URL; ?>/index.php?url=teacher/schedule"><i class="ion-calendar"></i> <?php echo __('admin.schedule'); ?></a></li>
                        <li><a href="<?php echo BASE_URL; ?>/index.php?url=teacher/messages"><i class="ion-ios-email"></i> <?php echo __('nav.messages'); ?></a></li>
                        <li><a href="<?php echo BASE_URL; ?>/index.php?url=teacher/reports"><i class="ion-stats-bars"></i> <?php echo __('teacher.reports'); ?></a></li>
                    <?php elseif ($userRole === 'student'): ?>
                        <li><a href="<?php echo BASE_URL; ?>/index.php?url=student/dashboard"><i class="ion-map"></i> <?php echo __('nav.dashboard'); ?></a></li>
                        <li><a href="<?php echo BASE_URL; ?>/index.php?url=student/schedule"><i class="ion-calendar"></i> <?php echo __('student.my_schedule'); ?></a></li>
                        <li><a href="<?php echo BASE_URL; ?>/index.php?url=student/statistics"><i class="ion-stats-bars"></i> <?php echo __('nav.statistics'); ?></a></li>
                        <li><a href="<?php echo BASE_URL; ?>/index.php?url=student/achievements"><i class="ion-trophy"></i> <?php echo __('nav.achievements'); ?></a></li>
                        <li><a href="<?php echo BASE_URL; ?>/index.php?url=student/certificates"><i class="ion-ribbon-a"></i> <?php echo __('nav.certificates'); ?></a></li>
                        <li><a href="<?php echo BASE_URL; ?>/index.php?url=student/myTeachers"><i class="ion-university"></i> <?php echo __('student.my_teachers'); ?></a></li>
                        <li><a href="<?php echo BASE_URL; ?>/index.php?url=student/messages"><i class="ion-ios-email"></i> <?php echo __('nav.messages'); ?></a></li>
                        <li><a href="<?php echo BASE_URL; ?>/index.php?url=student/profile"><i class="ion-person"></i> <?php echo __('nav.profile'); ?></a></li>
                    <?php endif; ?>
                </ul>
            </li>

            <li class="pull-left hidden-xs hidden-sm nav-action pull-left border-right">
                <a href="#" title="<?php echo __('common.settings'); ?>"><i class="ion-wrench"></i></a>
            </li>
            
            <li class="nav-action pull-left border-right">
                <a href="<?php echo BASE_URL; ?>/index.php?url=<?php echo strtolower($userRole); ?>/messages" title="<?php echo __('nav.messages'); ?>"><i class="ion-ios-email"></i></a>
            </li>
            
            <li class="pull-left border-right dropdown dropdown-hover">
                <a aria-expanded="false" role="button" href="#" class="dropdown-toggle font-noraml" data-toggle="dropdown" style="padding-top: 15px">
                    <i class="ion-plus-round"></i>
                </a>
                <ul role="menu" class="dropdown-menu">
                    <?php if ($userRole === 'admin'): ?>
                        <li><a href="<?php echo BASE_URL; ?>/index.php?url=admin/createUser"><i class="ion-person-add m-r-xs"></i> <?php echo __('admin.add_user'); ?></a></li>
                        <li><a href="<?php echo BASE_URL; ?>/index.php?url=admin/createCourse"><i class="ion-book m-r-xs"></i> <?php echo __('admin.add_course'); ?></a></li>
                        <li><a href="<?php echo BASE_URL; ?>/index.php?url=admin/createGroup"><i class="ion-person-stalker m-r-xs"></i> <?php echo __('admin.add_group'); ?></a></li>
                        <li class="divider"></li>
                        <li><a href="<?php echo BASE_URL; ?>/index.php?url=admin/addScheduleEvent"><i class="ion-calendar m-r-xs"></i> <?php echo __('admin.add_schedule'); ?></a></li>
                    <?php elseif ($userRole === 'manager'): ?>
                        <li><a href="<?php echo BASE_URL; ?>/index.php?url=manager/createUser"><i class="ion-person-add m-r-xs"></i> <?php echo __('manager.add_student'); ?></a></li>
                        <li><a href="<?php echo BASE_URL; ?>/index.php?url=manager/createGroup"><i class="ion-person-stalker m-r-xs"></i> <?php echo __('admin.add_group'); ?></a></li>
                    <?php endif; ?>
                </ul>
            </li>

            <li class="pull-left hidden-xs crm-fulltext-search">
                <input type="text" class="input-sm ui-autocomplete-input" style="width: 150px; border: none; background: white; margin-top: 10px" placeholder="<?php echo __('common.search'); ?>" autocomplete="off">
            </li>

            <li class="dropdown dropdown-hover profile font-noraml pull-right">
                <a href="#" data-toggle="dropdown" class="profile-link" style="padding: 5px 10px">
                    <span class="hidden-xs">
                        <?php echo htmlspecialchars($userName); ?>
                    </span>
                    <img alt="image" class="img img-circle img-thumbnail m-l-xs" src="<?php echo htmlspecialchars($userAvatar); ?>" style="width: 40px; height: 40px;">
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo BASE_URL; ?>/index.php?url=<?php echo strtolower($userRole); ?>/profile"><i class="ion-person"></i> <?php echo __('nav.profile'); ?></a></li>
                    <li class="divider"></li>
                    <li>
                        <select class="form-control" onchange="window.location.href='?lang='+this.value+'&url='+new URLSearchParams(window.location.search).get('url')" style="margin: 5px; width: calc(100% - 10px);">
                            <?php foreach (Language::getLanguages() as $code => $name): ?>
                                <option value="<?php echo $code; ?>" <?php echo Language::getCurrentLanguage() === $code ? 'selected' : ''; ?>>
                                    <?php echo $name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>/index.php?url=auth/logout">
                            <i class="ion-android-exit fa-fw fa"></i>
                            <?php echo __('nav.logout'); ?>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</div>
<div id="wrapper">
    <nav class="navbar navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse" style="width: 200px;">
            <ul class="nav metismenu" id="side-menu">
                <?php if ($userRole === 'admin'): ?>
                    <li>
                        <a href="<?php echo BASE_URL; ?>/index.php?url=admin/dashboard" class="<?php echo strpos($currentUrl, 'admin/dashboard') !== false ? 'active' : ''; ?>">
                            <i class="ion-map"></i>
                            <?php echo __('nav.dashboard'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>/index.php?url=admin/users" class="<?php echo strpos($currentUrl, 'admin/users') !== false ? 'active' : ''; ?>">
                            <i class="ion-person-stalker"></i>
                            <?php echo __('nav.users'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>/index.php?url=admin/courses" class="<?php echo strpos($currentUrl, 'admin/courses') !== false ? 'active' : ''; ?>">
                            <i class="ion-book"></i>
                            <?php echo __('nav.courses'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>/index.php?url=admin/groups" class="<?php echo (strpos($currentUrl, 'admin/groups') !== false || strpos($currentUrl, 'admin/createGroup') !== false || strpos($currentUrl, 'admin/editGroup') !== false) ? 'active' : ''; ?>">
                            <i class="ion-person-stalker"></i>
                            <?php echo __('admin.groups'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>/index.php?url=admin/schedule" class="<?php echo (strpos($currentUrl, 'admin/schedule') !== false || strpos($currentUrl, 'admin/getScheduleEvents') !== false || strpos($currentUrl, 'admin/addScheduleEvent') !== false) ? 'active' : ''; ?>">
                            <i class="ion-calendar"></i>
                            <?php echo __('admin.schedule'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>/index.php?url=admin/achievements" class="<?php echo strpos($currentUrl, 'admin/achievements') !== false ? 'active' : ''; ?>">
                            <i class="ion-trophy"></i>
                            <?php echo __('nav.achievements'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>/index.php?url=admin/statistics" class="<?php echo strpos($currentUrl, 'admin/statistics') !== false ? 'active' : ''; ?>">
                            <i class="ion-stats-bars"></i>
                            <?php echo __('nav.statistics'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>/index.php?url=admin/enrollments" class="<?php echo strpos($currentUrl, 'admin/enrollments') !== false ? 'active' : ''; ?>">
                            <i class="ion-person-add"></i>
                            <?php echo __('nav.enrollments'); ?>
                        </a>
                    </li>
                <?php elseif ($userRole === 'manager'): ?>
                    <li>
                        <a href="<?php echo BASE_URL; ?>/index.php?url=manager/dashboard" class="<?php echo strpos($currentUrl, 'manager/dashboard') !== false ? 'active' : ''; ?>">
                            <i class="ion-map"></i>
                            <?php echo __('nav.dashboard'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>/index.php?url=manager/users" class="<?php echo (strpos($currentUrl, 'manager/users') !== false || strpos($currentUrl, 'manager/createUser') !== false || strpos($currentUrl, 'manager/editUser') !== false) ? 'active' : ''; ?>">
                            <i class="ion-person"></i>
                            <?php echo __('manager.students'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>/index.php?url=manager/groups" class="<?php echo (strpos($currentUrl, 'manager/groups') !== false || strpos($currentUrl, 'manager/createGroup') !== false || strpos($currentUrl, 'manager/editGroup') !== false || strpos($currentUrl, 'manager/groupDetails') !== false) ? 'active' : ''; ?>">
                            <i class="ion-person-stalker"></i>
                            <?php echo __('admin.groups'); ?>
                        </a>
                    </li>
                <?php elseif ($userRole === 'teacher'): ?>
                    <li>
                        <a href="<?php echo BASE_URL; ?>/index.php?url=teacher/dashboard" class="<?php echo strpos($currentUrl, 'teacher/dashboard') !== false ? 'active' : ''; ?>">
                            <i class="ion-map"></i>
                            <?php echo __('nav.dashboard'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>/index.php?url=teacher/schedule" class="<?php echo (strpos($currentUrl, 'teacher/schedule') !== false || strpos($currentUrl, 'teacher/classDetails') !== false || strpos($currentUrl, 'teacher/getScheduleEvents') !== false) ? 'active' : ''; ?>">
                            <i class="ion-calendar"></i>
                            <?php echo __('admin.schedule'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>/index.php?url=teacher/messages" class="<?php echo (strpos($currentUrl, 'teacher/messages') !== false || strpos($currentUrl, 'teacher/conversation') !== false) ? 'active' : ''; ?>">
                            <i class="ion-ios-email"></i>
                            <?php echo __('nav.messages'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>/index.php?url=teacher/reports" class="<?php echo strpos($currentUrl, 'teacher/reports') !== false ? 'active' : ''; ?>">
                            <i class="ion-stats-bars"></i>
                            <?php echo __('teacher.reports'); ?>
                        </a>
                    </li>
                <?php elseif ($userRole === 'student'): ?>
                    <li>
                        <a href="<?php echo BASE_URL; ?>/index.php?url=student/dashboard" class="<?php echo strpos($currentUrl, 'student/dashboard') !== false ? 'active' : ''; ?>">
                            <i class="ion-map"></i>
                            <?php echo __('nav.dashboard'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>/index.php?url=student/schedule" class="<?php echo (strpos($currentUrl, 'student/schedule') !== false || strpos($currentUrl, 'student/getMyScheduleEvents') !== false || strpos($currentUrl, 'student/getAttendanceStatus') !== false) ? 'active' : ''; ?>">
                            <i class="ion-calendar"></i>
                            <?php echo __('student.my_schedule'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>/index.php?url=student/statistics" class="<?php echo strpos($currentUrl, 'student/statistics') !== false ? 'active' : ''; ?>">
                            <i class="ion-stats-bars"></i>
                            <?php echo __('nav.statistics'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>/index.php?url=student/achievements" class="<?php echo strpos($currentUrl, 'student/achievements') !== false ? 'active' : ''; ?>">
                            <i class="ion-trophy"></i>
                            <?php echo __('nav.achievements'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>/index.php?url=student/certificates" class="<?php echo strpos($currentUrl, 'student/certificates') !== false ? 'active' : ''; ?>">
                            <i class="ion-ribbon-a"></i>
                            <?php echo __('nav.certificates'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>/index.php?url=student/myTeachers" class="<?php echo strpos($currentUrl, 'student/myTeachers') !== false ? 'active' : ''; ?>">
                            <i class="ion-university"></i>
                            <?php echo __('student.my_teachers'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>/index.php?url=student/messages" class="<?php echo (strpos($currentUrl, 'student/messages') !== false || strpos($currentUrl, 'student/conversation') !== false) ? 'active' : ''; ?>">
                            <i class="ion-ios-email"></i>
                            <?php echo __('nav.messages'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>/index.php?url=student/profile" class="<?php echo strpos($currentUrl, 'student/profile') !== false ? 'active' : ''; ?>">
                            <i class="ion-person"></i>
                            <?php echo __('nav.profile'); ?>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <div id="page-wrapper" class="gray-bg" style="min-height: 591px;">
        <div class="wrapper crm-content">
<?php else: ?>
<!-- For non-logged in users, keep old structure -->
<div class="main-container">
<?php endif; ?>
