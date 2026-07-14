<!-- панель События по выбранной точке прохода -->
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">
            <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
            <?php echo __('События'); ?>
            <span class="pull-right">
                <span class="badge" id="eventsBadge">0</span>
                <button class="btn btn-xs btn-primary" onclick="location.reload();">
                    <span class="glyphicon glyphicon-refresh"></span>
                </button>
            </span>
        </h3>
    </div>
    <div class="panel-body">
        
        <!-- Фильтр по дате событий -->
        <div class="row" style="margin-bottom: 15px; background: #f9f9f9; padding: 12px 15px; border-radius: 4px; border: 1px solid #e7e7e7;">
            <div class="col-md-12">
                <div style="margin-bottom: 8px;">
                    <span class="glyphicon glyphicon-calendar" style="color: #337ab7;"></span>
                    <strong style="font-size: 13px;"><?php echo __('Фильтр по дате событий'); ?></strong>
                    <span style="font-weight: normal; font-size: 11px; color: #999; margin-left: 10px;">
                        <?php echo __('Выберите период и нажмите "Получить"'); ?>
                    </span>
                </div>
                <div class="row">
                    <div class="col-xs-4">
                        <div class="input-group date" id="eventDatetimepicker1">
                            <span class="input-group-addon" style="background: #fff; font-size: 11px; color: #666; border-color: #ddd;">
                                <?php echo __('С'); ?>
                            </span>
                            <input type="text" class="form-control" id="eventTimeFrom" 
                                   name="eventTimeFrom"
                                   placeholder="DD.MM.YYYY HH:mm"
                                   value="<?php echo date('d.m.Y') . ' 00:00'; ?>"
                                   style="font-size: 13px;">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <div class="col-xs-1 text-center" style="padding-top: 5px;">
                        <span class="text-muted" style="font-size: 18px;">—</span>
                    </div>
                    <div class="col-xs-4">
                        <div class="input-group date" id="eventDatetimepicker2">
                            <span class="input-group-addon" style="background: #fff; font-size: 11px; color: #666; border-color: #ddd;">
                                <?php echo __('По'); ?>
                            </span>
                            <input type="text" class="form-control" id="eventTimeTo" 
                                   name="eventTimeTo"
                                   placeholder="DD.MM.YYYY HH:mm"
                                   value="<?php echo date('d.m.Y H:i'); ?>"
                                   style="font-size: 13px;">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <div class="col-xs-2" style="padding-top: 5px;">
                        <button type="button" class="btn btn-sm btn-success" id="getEventsBtn" style="width: 100%;">
                            <span class="glyphicon glyphicon-ok"></span> <?php echo __('Получить'); ?>
                        </button>
                    </div>
                    <div class="col-xs-1" style="padding-top: 5px;">
                        <button type="button" class="btn btn-sm btn-default" id="resetEventsBtn" style="width: 100%;">
                            <span class="glyphicon glyphicon-refresh"></span>
                        </button>
                    </div>
                </div>
                <div id="eventSpinner" style="display: none; margin-top: 8px;">
                    <span class="glyphicon glyphicon-refresh glyphicon-spin"></span> 
                    <?php echo __('Загрузка событий...'); ?>
                </div>
            </div>
        </div>
        
        <!-- Таблица событий -->
        <div class="table-responsive" id="eventsTableContainer">
            <table id="table3" class="table table-striped table-hover table-condensed table-bordered tablesorter">
                <thead>
                    <tr>
                        <th><?php echo __('DATETIME'); ?></th>
                        <th><?php echo __('card'); ?></th>
                        <th><?php echo __('name'); ?></th>
                        <th><?php echo __('org_name'); ?></th>
                        <th><?php echo __('NAME_EVENT'); ?></th>
                        <th><?php echo __('NAME'); ?></th>
                    </tr>
                </thead>
                <tbody id="eventsBody">
                    <!-- Тело таблицы заполняется через AJAX -->
                    <tr id="noEventsRow">
                        <td colspan="5" class="text-center text-muted">
                            <?php echo __('Загрузка событий...'); ?>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="active">
                        <td colspan="6">
                            <small class="text-muted">
                                <span class="glyphicon glyphicon-stats" aria-hidden="true"></span>
                                <?php echo __('Всего событий') . ': <span id="eventsTotalCount">0</span>'; ?>
                            </small>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    // Получаем ID двери из URL
    var doorId = <?php echo isset($door) ? (int)$door['ID_DEV'] : 0; ?>;
    
    // Если doorId не определен, пытаемся получить из URL
    if (!doorId || doorId === 0) {
        var path = window.location.pathname;
        var parts = path.split('/');
        doorId = parseInt(parts[parts.length - 1]);
    }
    
    console.log('Door ID:', doorId);
    
    // Инициализация datetimepicker для событий (текущие сутки с 00:00)
    var now = new Date();
    var todayStart = new Date(now);
    todayStart.setHours(0, 0, 0, 0);
    
    $("#eventDatetimepicker1").datetimepicker({
        language: 'ru',
        showToday: true,
        sideBySide: true,
        defaultDate: todayStart,
        format: 'DD.MM.YYYY HH:mm'
    });
    
    $("#eventDatetimepicker2").datetimepicker({
        language: 'ru',
        showToday: true,
        sideBySide: true,
        defaultDate: now,
        format: 'DD.MM.YYYY HH:mm'
    });
    
    // Связываем даты
    $("#eventDatetimepicker1").on("dp.change", function(e) {
        $("#eventDatetimepicker2").data("DateTimePicker").setMinDate(e.date);
        updateEventTimeFrom();
    });
    
    $("#eventDatetimepicker2").on("dp.change", function(e) {
        $("#eventDatetimepicker1").data("DateTimePicker").setMaxDate(e.date);
        updateEventTimeTo();
    });
    
    // Функции обновления полей
    function updateEventTimeFrom() {
        var val = $('#eventDatetimepicker1').data("DateTimePicker").date();
        if (val) {
            $('#eventTimeFrom').val(val.format('DD.MM.YYYY HH:mm'));
        }
    }
    
    function updateEventTimeTo() {
        var val = $('#eventDatetimepicker2').data("DateTimePicker").date();
        if (val) {
            $('#eventTimeTo').val(val.format('DD.MM.YYYY HH:mm'));
        }
    }
    
    // Инициализация таблицы событий
    $("#table3").tablesorter({
        sortList: [[0, 1]],
        headers: {
            0: { sorter: 'text' },
            1: { sorter: 'digit' },
            2: { sorter: 'text' },
            3: { sorter: 'text' },
            4: { sorter: 'text' }
        },
        widgets: ['zebra']
    });
    
    // ========== Получить события ==========
    $('#getEventsBtn').on('click', function() {
        if (!doorId || doorId === 0) {
            alert('Ошибка: ID точки прохода не определен');
            return;
        }
        loadEvents();
    });
    
    // ========== Сброс фильтра ==========
    $('#resetEventsBtn').on('click', function() {
        // Сбрасываем на текущие сутки с 00:00
        var now = new Date();
        var todayStart = new Date(now);
        todayStart.setHours(0, 0, 0, 0);
        
        $('#eventTimeFrom').val(formatDate(todayStart));
        $('#eventTimeTo').val(formatDate(now));
        
        // Обновляем datetimepicker
        $("#eventDatetimepicker1").data("DateTimePicker").setDate(todayStart);
        $("#eventDatetimepicker2").data("DateTimePicker").setDate(now);
        
        if (doorId && doorId !== 0) {
            loadEvents();
        }
    });
    
    // ========== Загрузка событий ==========
    function loadEvents() {
        var timeFrom = $('#eventTimeFrom').val();
        var timeTo = $('#eventTimeTo').val();
        
        console.log('timeFrom:', timeFrom);
        console.log('timeTo:', timeTo);
        console.log('doorId:', doorId);
        
        if (!timeFrom || !timeTo) {
            alert('Пожалуйста, выберите обе даты');
            return;
        }
        
        if (!doorId || doorId === 0) {
            alert('Ошибка: ID точки прохода не определен');
            return;
        }
        
        $('#eventSpinner').show();
        
        $.ajax({
            url: '<?php echo URL::site("door/getEvents"); ?>/' + doorId,
            type: 'GET',
            data: {
                timeFrom: timeFrom,
                timeTo: timeTo
            },
            dataType: 'json',
            cache: false,
            timeout: 30000,
            success: function(response) {
                $('#eventSpinner').hide();
                console.log('Response:', response);
                if (response.success) {
                    updateEventsTable(response.data);
                } else {
                    alert('Ошибка при загрузке событий: ' + (response.error || 'Неизвестная ошибка'));
                }
            },
            error: function(xhr, status, error) {
                $('#eventSpinner').hide();
                console.log('AJAX Error:', status, error);
                console.log('Response:', xhr.responseText);
                alert('Произошла ошибка при загрузке событий. Пожалуйста, попробуйте еще раз.');
            }
        });
    }
    
    function updateEventsTable_(data) {
        var $tbody = $('#eventsBody');
        $tbody.empty();
        
        // Обновляем бейдж
        $('#eventsBadge').text(data ? data.length : 0);
        
        if (!data || data.length === 0) {
            $tbody.html('<tr id="noEventsRow"><td colspan="6" class="text-center text-muted"><?php echo __('Нет событий за выбранный период'); ?></td></tr>');
            $('#eventsTotalCount').text(0);
            return;
        }
        
        $.each(data, function(index, item) {
            var trClass = (item.ID_EVENTTYPE == 50) ? 'success' : 'warning';
            var row = '<tr class="' + trClass + '">' +
                '<td>' + item.DATETIME + '</td>' +
                '<td><span class="label label-default">' + item.ID_CARD + '</span></td>' +
               '<td><a href="/people/peopleInfo/' + item.ID_PEP + '/' + item.ID_CARD + '">' + escapeHtml(item.NOTE) + '</a></td>' +
			    '<td>' + escapeHtml(item.ORG_NAME) + '</td>' +
                '<td>' + escapeHtml(item.NAME) + '</td>' +
                '<td>' + escapeHtml(item.DEV_NAME) + '</td>' +
                '</tr>';
            $tbody.append(row);
        });
        
        $('#eventsTotalCount').text(data.length);
        $("#table3").trigger("update");
    }
	
	function updateEventsTable(data) {
    var $tbody = $('#eventsBody');
    $tbody.empty();
    
    if (!data || data.length === 0) {
        $tbody.html('<tr id="noEventsRow"><td colspan="6" class="text-center text-muted"><?php echo __('Нет событий за выбранный период'); ?></td></tr>');
        $('#eventsTotalCount').text(0);
        return;
    }
    
    $.each(data, function(index, item) {
        var trClass = (item.ID_EVENTTYPE == 50) ? 'success' : 'warning';
        var row = '<tr class="' + trClass + '">' +
            '<td>' + item.DATETIME + '</td>' +
            '<td><span class="label label-default">' + item.ID_CARD + '</span></td>' +
            '<td><a href="' + item.PEOPLE_URL + '">' + escapeHtml(item.NOTE) + '</a></td>' +
            '<td>' + escapeHtml(item.ORG_NAME) + '</td>' +
			'<td>' + escapeHtml(item.NAME) + '</td>' +
            '<td>' + escapeHtml(item.DEV_NAME) + '</td>' +
            '</tr>';
        $tbody.append(row);
    });
    
    $('#eventsTotalCount').text(data.length);
    $("#table3").trigger("update");
}


    
    function formatDate(date) {
        var d = date.getDate();
        var m = date.getMonth() + 1;
        var y = date.getFullYear();
        var h = date.getHours();
        var min = date.getMinutes();
        return ('0' + d).slice(-2) + '.' + ('0' + m).slice(-2) + '.' + y + ' ' + 
               ('0' + h).slice(-2) + ':' + ('0' + min).slice(-2);
    }
    
    function escapeHtml(text) {
        if (!text) return '';
        return $('<div>').text(text).html();
    }
    
    // При загрузке страницы автоматически загружаем события
    if (doorId && doorId !== 0) {
        loadEvents();
    }
});
</script>