<?php 
// Подключение вспомогательных функций
include_once 'partials/_helpers.php';
?>

<!-- Таблица вывода информации по выбранному устройству -->
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">
            <?php echo __('door_panel_title'); ?>
            <span class="pull-right">
                <button class="btn btn-xs btn-primary" onclick="location.reload();">
                    <span class="glyphicon glyphicon-refresh"></span>
                </button>
            </span>
        </h3>
    </div>
    <div class="panel-body">
        
        <!-- Информация о точке прохода -->
        <?php include_once 'partials/_info.php'; ?>
        
    </div>
</div>

<!-- Категории доступа -->
<?php include_once 'partials/_access_categories.php'; ?>

<!-- Группы устройств -->
<?php include_once 'partials/_device_groups.php'; ?>

<!-- Вкладки -->
<?php
// Подсчет количества записей для каждой вкладки
$count_add = isset($people_add) ? count($people_add) : 0;
$count_del = isset($people_del) ? count($people_del) : 0;
$count_events = isset($events) ? count($events) : 0;
$count_keys = isset($keys) ? count($keys) : 0;
?>

<ul class="nav nav-tabs" role="tablist">
    <li class="active">
        <a data-toggle="tab" href="#panel1">
            <span class="glyphicon glyphicon-upload"></span>
            <?php echo __('list_card_for_load', array(':count' => $count_add)); ?>
        </a>
    </li>
    <li>
        <a data-toggle="tab" href="#panel2">
            <span class="glyphicon glyphicon-trash"></span>
            <?php echo __('list_card_for_delete', array(':count' => $count_del)); ?>
        </a>
    </li>
    <li>
        <a data-toggle="tab" href="#panel3">
            <span class="glyphicon glyphicon-list-alt"></span>
            <?php echo __('events_for_door', array(':count' => $count_events)); ?>
        </a>
    </li>
    <li>
        <a data-toggle="tab" href="#panel4">
            <span class="glyphicon glyphicon-ok-circle"></span>
            <?php echo __('keys_for_door', array(':count' => $count_keys)); ?>
        </a>
    </li>
</ul>

<div class="tab-content" style="padding-top: 15px;">
    <div id="panel1" class="tab-pane fade in active">
        <?php include_once 'partials/_load_queue.php'; ?>
    </div>
    <div id="panel2" class="tab-pane fade">
        <?php include_once 'partials/_delete_queue.php'; ?>
    </div>
    <div id="panel3" class="tab-pane fade">
        <?php include_once 'partials/_events.php'; ?>
    </div>
    <div id="panel4" class="tab-pane fade">
        <?php include_once 'partials/_keys.php'; ?>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    // Инициализация таблиц
    initTables();
    
    // Управление иконками сворачиваемых панелей
    setupCollapsibleIcons();
});

function initTables() {
    var tableOptions = {
        sortList: [[0,0], [2,1]],
        widgets: ['zebra']
    };
    
    // Инициализация всех таблиц с классом tablesorter
    $('.tablesorter').each(function() {
        if (!$(this).hasClass('tablesorter-initialized')) {
            $(this).tablesorter(tableOptions);
            $(this).addClass('tablesorter-initialized');
        }
    });
}

function setupCollapsibleIcons() {
    var panels = [
        { id: 'accessCollapse', iconUp: '#iconCollapse', iconDown: '#iconExpand', toggle: '#accessToggle' },
        { id: 'deviceGroupsCollapse', iconUp: '#deviceGroupsIconCollapse', iconDown: '#deviceGroupsIconExpand', toggle: '#deviceGroupsToggle' }
    ];
    
    panels.forEach(function(panel) {
        $('#' + panel.id).on('shown.bs.collapse', function() {
            $(panel.iconUp).show();
            $(panel.iconDown).hide();
            $(panel.toggle).attr('aria-expanded', 'true');
        });
        
        $('#' + panel.id).on('hidden.bs.collapse', function() {
            $(panel.iconUp).hide();
            $(panel.iconDown).show();
            $(panel.toggle).attr('aria-expanded', 'false');
        });
        
        if ($('#' + panel.id).hasClass('in')) {
            $(panel.iconUp).show();
            $(panel.iconDown).hide();
            $(panel.toggle).attr('aria-expanded', 'true');
        } else {
            $(panel.iconUp).hide();
            $(panel.iconDown).show();
            $(panel.toggle).attr('aria-expanded', 'false');
        }
    });
}

// Фильтрация таблиц
function filterTable(input, tableId) {
    var filter = input.value.toUpperCase();
    var table = document.getElementById(tableId);
    if (!table) return;
    
    var rows = table.getElementsByTagName('tbody');
    if (rows.length === 0) return;
    
    var tbody = rows[0];
    var trs = tbody.getElementsByTagName('tr');
    
    for (var i = 0; i < trs.length; i++) {
        var showRow = false;
        var cells = trs[i].getElementsByTagName('td');
        for (var j = 0; j < cells.length; j++) {
            if (cells[j]) {
                var text = cells[j].textContent || cells[j].innerText;
                if (text.toUpperCase().indexOf(filter) > -1) {
                    showRow = true;
                    break;
                }
            }
        }
        trs[i].style.display = showRow ? '' : 'none';
    }
}
</script>

<style type="text/css">
/* Стили для категорий доступа */
#accessPanel .label {
    transition: all 0.2s ease;
}

#accessPanel .label:hover {
    transform: scale(1.02);
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

/* Стили для групп устройств */
#deviceGroupsPanel .label {
    transition: all 0.2s ease;
}

#deviceGroupsPanel .label:hover {
    transform: scale(1.02);
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

/* Стиль для цепочки родителей */
#deviceGroupsPanel .glyphicon-share-alt {
    opacity: 0.6;
}

#deviceGroupsPanel .label-success {
    background-color: #5cb85c;
}

/* Стиль для отображения цепочки */
#deviceGroupsPanel .glyphicon-folder-close {
    margin-right: 3px;
}

/* Стили для сворачиваемых блоков */
.panel-heading a {
    transition: all 0.3s ease;
}

.panel-heading a:hover {
    background-color: rgba(255, 255, 255, 0.1);
    border-radius: 4px;
}

.toggle-icon .glyphicon {
    transition: transform 0.3s ease;
}

/* Адаптивность */
@media (max-width: 768px) {
    #accessPanel .label {
        font-size: 11px !important;
        padding: 4px 8px !important;
    }
    
    #deviceGroupsPanel .label {
        font-size: 11px !important;
        padding: 4px 8px !important;
    }
}

/* Стили для таблиц */
.table-responsive {
    overflow-x: auto;
}

.table tfoot td {
    background-color: #f5f5f5;
}

/* Стили для иконок статусов */
.glyphicon-refresh {
    animation: none;
}

/* Стили для панелей с вкладками */
.nav-tabs {
    margin-bottom: 20px;
}

.nav-tabs > li > a {
    border-radius: 4px 4px 0 0;
}

.nav-tabs > li.active > a,
.nav-tabs > li.active > a:hover,
.nav-tabs > li.active > a:focus {
    border-bottom-color: transparent;
}
</style>