<?php $title = __('student.my_schedule'); include 'views/layouts/header.php'; ?>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <h2 class="font-bold m-b-xs"><i class="ion-calendar"></i> <?php echo __('student.my_schedule'); ?></h2>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom">
            <div id="calendar"></div>
        </div>
    </div>
</div>

<!-- Модальное окно с деталями занятия -->
<div id="scheduleModal" class="modal" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onclick="closeScheduleModal()" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><i class="ion-clipboard"></i> <?php echo __('student.class_details'); ?></h4>
            </div>
            <div class="modal-body" id="modalBody">
                <div style="text-align: center; padding: 40px;">
                    <div class="sk-spinner sk-spinner-wave">
                        <div class="sk-rect1"></div>
                        <div class="sk-rect2"></div>
                        <div class="sk-rect3"></div>
                        <div class="sk-rect4"></div>
                        <div class="sk-rect5"></div>
                    </div>
                    <p class="text-muted m-t-sm"><?php echo __('common.loading'); ?>...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" onclick="closeScheduleModal()">
                    <i class="ion-close"></i> <?php echo __('common.close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
#calendar {
    padding: 20px;
    min-height: 600px;
}

.fc {
    font-family: inherit;
}

.fc-header-toolbar {
    margin-bottom: 20px;
}

.fc-button {
    background: #1ab394 !important;
    border: none !important;
    padding: 8px 16px !important;
    border-radius: 4px !important;
    font-weight: 600 !important;
}

.fc-button:hover {
    background: #18a689 !important;
}

.fc-button-primary:not(:disabled).fc-button-active {
    background: #18a689 !important;
}

.fc-event {
    border: none !important;
    border-radius: 4px !important;
    padding: 4px 8px !important;
    cursor: pointer !important;
}

.fc-day-today {
    background-color: rgba(26, 179, 148, 0.05) !important;
}

.fc-col-header-cell {
    background: #f5f5f5 !important;
    padding: 10px !important;
    font-weight: 600 !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        firstDay: 1,
        height: 'auto',
        events: function(fetchInfo, successCallback, failureCallback) {
            fetch('/index.php?url=student/getMyScheduleEvents')
                .then(response => response.json())
                .then(data => {
                    successCallback(data);
                })
                .catch(error => {
                    console.error('Error loading events:', error);
                    failureCallback(error);
                });
        },
        eventClick: function(info) {
            openScheduleModal(info.event);
        },
        dateClick: function(info) {
            console.log('Date clicked:', info.dateStr);
        }
    });
    
    calendar.render();
    
    function openScheduleModal(event) {
        const modal = document.getElementById('scheduleModal');
        const modalBody = document.getElementById('modalBody');
        
        $(modal).modal('show');
        modalBody.innerHTML = `
            <div style="text-align: center; padding: 40px;">
                <div class="sk-spinner sk-spinner-wave">
                    <div class="sk-rect1"></div>
                    <div class="sk-rect2"></div>
                    <div class="sk-rect3"></div>
                    <div class="sk-rect4"></div>
                    <div class="sk-rect5"></div>
                </div>
                <p class="text-muted m-t-sm"><?php echo __('common.loading'); ?>...</p>
            </div>
        `;
        
        fetch('/index.php?url=student/getAttendanceStatus/' + event.id)
            .then(response => response.json())
            .then(attendanceData => {
                const scheduleData = {
                    scheduleTitle: event.extendedProps.scheduleTitle || '',
                    groupName: event.extendedProps.groupName || '',
                    courseTitle: event.extendedProps.courseTitle || '',
                    teacherName: event.extendedProps.teacherName || '',
                    location: event.extendedProps.location || ''
                };
                
                let statusBadge = '';
                if (attendanceData.hasStatus && attendanceData.status) {
                    let badgeClass = 'label-default';
                    let statusText = '<?php echo __('teacher.unknown_status'); ?>';
                    
                    if (attendanceData.status === 'present') {
                        badgeClass = 'label-success';
                        statusText = '<?php echo __('teacher.present'); ?>';
                    } else if (attendanceData.status === 'absent') {
                        badgeClass = 'label-danger';
                        statusText = '<?php echo __('teacher.absent'); ?>';
                    } else if (attendanceData.status === 'late') {
                        badgeClass = 'label-warning';
                        statusText = '<?php echo __('teacher.late'); ?>';
                    }
                    
                    statusBadge = `<span class="label ${badgeClass}">${statusText}</span>`;
                } else {
                    statusBadge = `<span class="label label-default"><?php echo __('student.not_marked'); ?></span>`;
                }
                
                const startDate = new Date(event.start);
                const endDate = new Date(event.end);
                const formattedStart = formatDateTime(startDate);
                const formattedEnd = formatDateTime(endDate);
                
                modalBody.innerHTML = `
                    <dl class="dl-horizontal">
                        ${scheduleData.scheduleTitle ? `
                        <dt><?php echo __('admin.class_title'); ?>:</dt>
                        <dd>${scheduleData.scheduleTitle}</dd>
                        ` : ''}
                        <dt><?php echo __('admin.group_name'); ?>:</dt>
                        <dd>${scheduleData.groupName || '-'}</dd>
                        ${scheduleData.courseTitle ? `
                        <dt><?php echo __('course.title'); ?>:</dt>
                        <dd>${scheduleData.courseTitle}</dd>
                        ` : ''}
                        ${scheduleData.teacherName ? `
                        <dt><?php echo __('course.teacher'); ?>:</dt>
                        <dd>${scheduleData.teacherName}</dd>
                        ` : ''}
                        <dt><?php echo __('admin.start_time'); ?>:</dt>
                        <dd>${formattedStart}</dd>
                        <dt><?php echo __('admin.end_time'); ?>:</dt>
                        <dd>${formattedEnd}</dd>
                        ${scheduleData.location ? `
                        <dt><?php echo __('admin.location'); ?>:</dt>
                        <dd>${scheduleData.location}</dd>
                        ` : ''}
                        <dt><?php echo __('teacher.attendance_status'); ?>:</dt>
                        <dd>${statusBadge}</dd>
                    </dl>
                `;
            })
            .catch(error => {
                console.error('Error loading attendance:', error);
                modalBody.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="ion-alert-circled"></i> <?php echo __('student.error_loading_details'); ?>
                    </div>
                `;
            });
    }
    
    window.closeScheduleModal = function() {
        $('#scheduleModal').modal('hide');
    };
    
    $('#scheduleModal').on('click', function(e) {
        if (e.target === this) {
            closeScheduleModal();
        }
    });
    
    function formatDateTime(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        
        return `${day}.${month}.${year} ${hours}:${minutes}`;
    }
});
</script>

<?php include 'views/layouts/footer.php'; ?>
