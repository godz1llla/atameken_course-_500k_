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
            fetch('/index.php?url=teacher/getScheduleEvents')
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
            window.location.href = '/index.php?url=teacher/classDetails/' + info.event.id;
        },
        dateClick: function(info) {
            console.log('Date clicked:', info.dateStr);
        }
    });
    
    calendar.render();
});
</script>

<?php include 'views/layouts/footer.php'; ?>
