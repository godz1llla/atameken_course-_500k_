<?php $title = __('admin.class_schedule'); include 'views/layouts/header.php'; ?>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <h2 class="font-bold m-b-xs"><i class="ion-calendar"></i> <?php echo __('admin.class_schedule'); ?></h2>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom">
            <div id="calendar"></div>
        </div>
    </div>
</div>

<!-- Модальное окно для добавления занятия -->
<div id="scheduleModal" class="modal" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onclick="closeScheduleModal()" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><i class="ion-plus"></i> <?php echo __('admin.add_class'); ?></h4>
            </div>
            <form id="scheduleForm" class="form-horizontal" style="padding: 20px;">
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo __('admin.select_group'); ?></label>
                    <div class="col-sm-9">
                        <select name="group_id" id="form_group_id" class="form-control" required>
                            <option value=""><?php echo __('admin.select_group'); ?></option>
                            <?php foreach ($groups as $group): ?>
                                <option value="<?php echo $group['id']; ?>">
                                    <?php echo htmlspecialchars($group['name']); ?> 
                                    <?php if (!empty($group['course_title'])): ?>
                                        - <?php echo htmlspecialchars($group['course_title']); ?>
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo __('admin.class_title'); ?></label>
                    <div class="col-sm-9">
                        <input type="text" name="title" id="form_title" class="form-control" placeholder="<?php echo __('admin.class_title_placeholder'); ?>">
                        <span class="help-block"><?php echo __('common.optional'); ?></span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo __('admin.start_time'); ?></label>
                    <div class="col-sm-9">
                        <input type="datetime-local" name="start_time" id="form_start_time" class="form-control" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo __('admin.end_time'); ?></label>
                    <div class="col-sm-9">
                        <input type="datetime-local" name="end_time" id="form_end_time" class="form-control" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo __('admin.location'); ?></label>
                    <div class="col-sm-9">
                        <input type="text" name="location" id="form_location" class="form-control" placeholder="<?php echo __('admin.location_placeholder'); ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-9 col-sm-offset-3">
                        <button type="submit" class="btn btn-success">
                            <i class="ion-checkmark"></i> <?php echo __('admin.save_class'); ?>
                        </button>
                        <button type="button" class="btn btn-default" onclick="closeScheduleModal()">
                            <i class="ion-close"></i> <?php echo __('common.cancel'); ?>
                        </button>
                    </div>
                </div>
            </form>
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
    let selectedDate = null;
    let selectedStartTime = null;
    
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
            fetch('/index.php?url=admin/getScheduleEvents')
                .then(response => response.json())
                .then(data => {
                    successCallback(data);
                })
                .catch(error => {
                    console.error('Error loading events:', error);
                    failureCallback(error);
                });
        },
        dateClick: function(info) {
            selectedDate = info.dateStr;
            selectedStartTime = null;
            openScheduleModal(info.dateStr);
        },
        eventClick: function(info) {
            console.log('Event clicked:', info.event);
        },
        selectable: true,
        select: function(info) {
            selectedStartTime = info.startStr;
            openScheduleModal(null, info.startStr, info.endStr);
        }
    });
    
    calendar.render();
    
    function openScheduleModal(dateStr, startStr, endStr) {
        const modal = document.getElementById('scheduleModal');
        const form = document.getElementById('scheduleForm');
        
        form.reset();
        
        if (dateStr) {
            const date = new Date(dateStr + 'T09:00:00');
            const endDate = new Date(date);
            endDate.setHours(date.getHours() + 2);
            
            document.getElementById('form_start_time').value = formatDateTimeLocal(date);
            document.getElementById('form_end_time').value = formatDateTimeLocal(endDate);
        } else if (startStr && endStr) {
            document.getElementById('form_start_time').value = formatDateTimeLocal(new Date(startStr));
            document.getElementById('form_end_time').value = formatDateTimeLocal(new Date(endStr));
        }
        
        $(modal).modal('show');
    }
    
    window.closeScheduleModal = function() {
        $('#scheduleModal').modal('hide');
        document.getElementById('scheduleForm').reset();
    };
    
    document.getElementById('scheduleForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());
        
        fetch('/index.php?url=admin/addScheduleEvent', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(data).toString()
        })
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                calendar.addEvent(result.event);
                closeScheduleModal();
                alert('<?php echo __('admin.class_added_success'); ?>');
            } else {
                alert('<?php echo __('admin.error'); ?>: ' + (result.message || '<?php echo __('admin.unknown_error'); ?>'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('<?php echo __('admin.error'); ?>: <?php echo __('admin.unknown_error'); ?>');
        });
    });
    
    $('#scheduleModal').on('click', function(e) {
        if (e.target === this) {
            closeScheduleModal();
        }
    });
    
    function formatDateTimeLocal(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        
        return `${year}-${month}-${day}T${hours}:${minutes}`;
    }
});
</script>

<?php include 'views/layouts/footer.php'; ?>
