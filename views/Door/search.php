<script type="text/javascript">
$(function() {
    // Инициализация datetimepicker
    var dateEnd = new Date();
    dateEnd.setHours(23, 59, 59, 0);
    
    var dateBegin = new Date();
    dateBegin.setHours(0, 0, 0, 0);
    
    $("#datetimepicker1").datetimepicker({
        language: 'ru',
        showToday: true,
        sideBySide: true,
        defaultDate: dateBegin,
        format: 'DD.MM.YYYY HH:mm'
    });
    
    $("#datetimepicker2").datetimepicker({
        language: 'ru',
        showToday: true,
        sideBySide: true,
        defaultDate: dateEnd,
        format: 'DD.MM.YYYY HH:mm'
    });
    
    // Связываем даты
    $("#datetimepicker1").on("dp.change", function(e) {
        $("#datetimepicker2").data("DateTimePicker").setMinDate(e.date);
    });
    
    $("#datetimepicker2").on("dp.change", function(e) {
        $("#datetimepicker1").data("DateTimePicker").setMaxDate(e.date);
    });
    
    // Инициализация таблицы
    $("#table1").tablesorter({
        sortList: [[0, 0]],
        headers: {}
    });
    
    // ========== Обработчик ввода ==========
    var searchTimeout;
    
    $('#doorSearchInput').on('keyup', function() {
        clearTimeout(searchTimeout);
        var term = $(this).val().trim();
        
        // Если поле пустое - очищаем таблицу и НЕ отправляем запрос
        if (term.length === 0) {
            $('#searchHelp').hide();
            // Проверяем, есть ли даты
            var timeFrom = $('input[name="timeFrom"]').val();
            var timeTo = $('input[name="timeTo"]').val();
            
            if (!timeFrom && !timeTo) {
                // Нет ни поиска, ни дат - показываем сообщение
                $('#table1 tbody').html('<tr><td colspan="4" class="text-center text-muted">Введите поисковый запрос или выберите даты</td></tr>');
                $('#totalRecords').text(0);
            } else {
                // Есть даты - отправляем запрос с пустым поиском
                searchTimeout = setTimeout(function() {
                    performSearch();
                }, 300);
            }
            return;
        }
        
        // Если меньше 3 символов - показываем подсказку и НЕ отправляем запрос
        if (term.length < 3) {
            $('#searchHelp').show();
            return;
        }
        
        // Длина >= 3 - скрываем подсказку и отправляем запрос
        $('#searchHelp').hide();
        searchTimeout = setTimeout(function() {
            performSearch();
        }, 300);
    });
    
    // ========== Фильтр по датам ==========
    $('#applyDateFilter').on('click', function() {
        var timeFrom = $('input[name="timeFrom"]').val();
        var timeTo = $('input[name="timeTo"]').val();
        var term = $('#doorSearchInput').val().trim();
        
        // Если нет ни поиска, ни дат - показываем сообщение
        if (!term && !timeFrom && !timeTo) {
            alert('Пожалуйста, введите поисковый запрос или выберите даты');
            return;
        }
        
        // Если поиск меньше 3 символов и не пустой - показываем подсказку
        if (term.length > 0 && term.length < 3) {
            $('#searchHelp').show();
            return;
        }
        
        performSearch();
    });
    
    // Сброс фильтра
    $('#resetDateFilter').on('click', function() {
        $('input[name="timeFrom"]').val('');
        $('input[name="timeTo"]').val('');
        $('#doorSearchInput').val('');
        $('#searchHelp').hide();
        $('#table1 tbody').html('<tr><td colspan="4" class="text-center text-muted">Введите поисковый запрос или выберите даты</td></tr>');
        $('#totalRecords').text(0);
    });
    
    // Очистка поиска
    $('#clearSearch').on('click', function() {
        $('#doorSearchInput').val('');
        $('#searchHelp').hide();
        
        var timeFrom = $('input[name="timeFrom"]').val();
        var timeTo = $('input[name="timeTo"]').val();
        if (!timeFrom && !timeTo) {
            $('#table1 tbody').html('<tr><td colspan="4" class="text-center text-muted">Введите поисковый запрос или выберите даты</td></tr>');
            $('#totalRecords').text(0);
        } else {
            performSearch();
        }
    });
    
    // ========== Общая функция поиска ==========
    function performSearch() {
        var term = $('#doorSearchInput').val().trim();
        var timeFrom = $('input[name="timeFrom"]').val();
        var timeTo = $('input[name="timeTo"]').val();
        
        // Если поиск пустой и даты не выбраны - НЕ отправляем запрос
        if (!term && !timeFrom && !timeTo) {
            $('#table1 tbody').html('<tr><td colspan="4" class="text-center text-muted">Введите поисковый запрос или выберите даты</td></tr>');
            $('#totalRecords').text(0);
            return;
        }
        
        // Если поиск меньше 3 символов и не пустой - НЕ отправляем запрос
        if (term.length > 0 && term.length < 3) {
            $('#searchHelp').show();
            return;
        }
        
        // Если поиск пустой, но есть даты - отправляем запрос
        // Если поиск >= 3 символов - отправляем запрос
        if (term.length === 0 && !timeFrom && !timeTo) {
            return;
        }
        
        $('#searchSpinner').show();
        
        $.ajax({
            url: '<?php echo URL::site("door/find"); ?>',
            type: 'GET',
            data: {
                doorInfo: term,
                timeFrom: timeFrom,
                timeTo: timeTo
            },
            dataType: 'json',
            cache: false,
            timeout: 30000,
            success: function(response) {
                $('#searchSpinner').hide();
                if (response.success) {
                    updateTable(response.data);
                } else {
                    alert('Ошибка при поиске: ' + (response.error || 'Неизвестная ошибка'));
                }
            },
            error: function(xhr, status, error) {
                $('#searchSpinner').hide();
                console.log('AJAX Error:', status, error);
                console.log('Response:', xhr.responseText);
                
                // Игнорируем ошибки при пустом запросе
                var term = $('#doorSearchInput').val().trim();
                var timeFrom = $('input[name="timeFrom"]').val();
                var timeTo = $('input[name="timeTo"]').val();
                if (!term && !timeFrom && !timeTo) {
                    return;
                }
                
                if (status === 'abort' || status === 'canceled') {
                    return;
                }
                
                if (status === 'timeout') {
                    alert('Превышено время ожидания ответа от сервера. Попробуйте еще раз.');
                } else {
                    // Проверяем, не вернул ли сервер HTML вместо JSON
                    if (xhr.responseText && xhr.responseText.indexOf('<!DOCTYPE') !== -1) {
                        // Сервер вернул HTML - значит ошибка на сервере
                        alert('Ошибка на сервере. Пожалуйста, проверьте правильность введенных данных.');
                    } else {
                        alert('Произошла ошибка при выполнении запроса. Пожалуйста, попробуйте еще раз.');
                    }
                }
            }
        });
    }
    
    function updateTable(data) {
        var $tbody = $('#table1 tbody');
        $tbody.empty();
        
        if (!data || data.length === 0) {
            $tbody.html('<tr><td colspan="4" class="text-center text-muted"><?php echo __('Ничего не найдено'); ?></td></tr>');
            $('#totalRecords').text(0);
            return;
        }
        
        $.each(data, function(index, item) {
            var row = '<tr>' +
                '<td>' + item.ID_DEV + '</td>' +
                '<td><a href="<?php echo URL::site("door/doorInfo"); ?>/' + item.ID_DEV + '">' + escapeHtml(item.NAME) + '</a></td>' +
                '<td>' + (item.DATE || '—') + '</td>' +
                '<td><a href="<?php echo URL::site("door/doorInfo"); ?>/' + item.ID_DEV + '" class="btn btn-xs btn-info"><span class="glyphicon glyphicon-eye-open"></span></a></td>' +
                '</tr>';
            $tbody.append(row);
        });
        
        $('#totalRecords').text(data.length);
        $("#table1").trigger("update");
    }
    
    function escapeHtml(text) {
        if (!text) return '';
        return $('<div>').text(text).html();
    }
    
    // Загружаем начальные данные
    <?php if (isset($results) && !empty($results)): ?>
        var initialData = <?php 
            $data = array();
            foreach ($results as $item) {
                $data[] = array(
                    'ID_DEV' => $item['ID_DEV'],
                    'NAME' => $item['NAME'],
                    'DATE' => isset($item['DATE']) ? $item['DATE'] : ''
                );
            }
            echo json_encode($data);
        ?>;
        if (initialData && initialData.length > 0) {
            updateTable(initialData);
        }
    <?php endif; ?>
});
</script>

<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">
            <span class="glyphicon glyphicon-search"></span> 
            <?php echo __('door_panel_title'); ?>
        </h3>
    </div>
    <div class="panel-body">
        
        <!-- Поиск по названию -->
        <div class="row" style="margin-bottom: 15px;">
            <div class="col-md-8">
                <div class="input-group">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-search"></span>
                    </span>
                    <input type="text" id="doorSearchInput" class="form-control" 
                           placeholder="<?php echo __('Введите не менее 3-х букв для поиска'); ?>"
                           value="<?php echo isset($searchTerm) ? htmlspecialchars($searchTerm) : ''; ?>">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button" id="clearSearch">
                            <span class="glyphicon glyphicon-remove"></span>
                        </button>
                    </span>
                </div>
                <span id="searchHelp" style="display: none; color: #a94442; font-size: 12px;">
                    <span class="glyphicon glyphicon-warning-sign"></span> 
                    <?php echo __('Минимальная длина поискового запроса - 3 символа'); ?>
                </span>
                <span id="searchSpinner" style="display: none; margin-left: 10px;">
                    <span class="glyphicon glyphicon-refresh glyphicon-spin"></span> 
                    <?php echo __('Поиск...'); ?>
                </span>
            </div>
            <div class="col-md-4 text-right">
                <button type="button" class="btn btn-primary" id="applyDateFilter">
                    <span class="glyphicon glyphicon-filter"></span> <?php echo __('Применить'); ?>
                </button>
                <button type="button" class="btn btn-default" id="resetDateFilter">
                    <span class="glyphicon glyphicon-refresh"></span> <?php echo __('Сбросить'); ?>
                </button>
            </div>
        </div>
        
        <!-- Выбор даты -->
        <div class="row" style="margin-bottom: 15px;">
            <div class="col-xs-5">
                <div class="form-group">
                    <label class="control-label" style="font-weight: normal; font-size: 12px; color: #999;">
                        <?php echo __('Дата с'); ?>
                    </label>
                    <div class="input-group date" id="datetimepicker1">
                        <input type="text" class="form-control" name="timeFrom" 
                               placeholder="DD.MM.YYYY HH:mm"
                               value="<?php echo isset($timeFrom) ? htmlspecialchars($timeFrom) : ''; ?>">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-xs-1 text-center" style="padding-top: 25px;">
                <span class="text-muted">—</span>
            </div>
            <div class="col-xs-5">
                <div class="form-group">
                    <label class="control-label" style="font-weight: normal; font-size: 12px; color: #999;">
                        <?php echo __('Дата по'); ?>
                    </label>
                    <div class="input-group date" id="datetimepicker2">
                        <input type="text" class="form-control" name="timeTo" 
                               placeholder="DD.MM.YYYY HH:mm"
                               value="<?php echo isset($timeTo) ? htmlspecialchars($timeTo) : ''; ?>">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-xs-1" style="padding-top: 25px;">
                <button type="button" class="btn btn-sm btn-success" id="applyDateFilter">
                    <span class="glyphicon glyphicon-ok"></span>
                </button>
            </div>
        </div>
        
        <!-- Таблица результатов -->
        <div class="table-responsive" style="margin-top: 15px;">
            <table class="table table-striped table-bordered table-hover" id="table1">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th><?php echo __('Название'); ?></th>
                        <th><?php echo __('Дата'); ?></th>
                        <th><?php echo __('Действия'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($results) && !empty($results)): ?>
                        <?php foreach ($results as $item): ?>
                            <tr>
                                <td><?php echo $item['ID_DEV']; ?></td>
                                <td>
                                    <a href="<?php echo URL::site('door/doorInfo/' . $item['ID_DEV']); ?>">
                                        <?php echo htmlspecialchars($item['NAME']); ?>
                                    </a>
                                </td>
                                <td><?php echo isset($item['DATE']) ? $item['DATE'] : '—'; ?></td>
                                <td>
                                    <a href="<?php echo URL::site('door/doorInfo/' . $item['ID_DEV']); ?>" 
                                       class="btn btn-xs btn-info">
                                        <span class="glyphicon glyphicon-eye-open"></span>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                <?php echo isset($searchTerm) && !empty($searchTerm) ? __('Ничего не найдено') : __('Введите поисковый запрос или выберите даты'); ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Информация о количестве записей -->
        <div class="row" style="margin-top: 10px;">
            <div class="col-md-12">
                <span class="text-muted" style="font-size: 12px;">
                    <?php echo __('Найдено записей'); ?>: <strong id="totalRecords"><?php echo isset($results) ? count($results) : 0; ?></strong>
                </span>
            </div>
        </div>
        
    </div>
</div>

<style>
.glyphicon-spin {
    animation: spin 1s infinite linear;
}
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
.table-responsive .table > tbody > tr > td {
    vertical-align: middle;
}
</style>