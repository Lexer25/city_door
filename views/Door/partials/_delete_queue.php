<!-- панель Очередь для удаления -->
<div class="panel panel-danger">
    <div class="panel-heading">
        <h3 class="panel-title">
            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
            <?php echo __('list_card_for_delete', array(':count' => isset($people_del) ? count($people_del) : 0)); ?>
            
            <span class="pull-right">
                <span class="badge" style="background-color: #d9534f; margin-right: 10px;">
                    <?php echo isset($people_del) ? count($people_del) : 0; ?>
                </span>
                <button class="btn btn-xs btn-success" onclick="location.reload();">
                    <span class="glyphicon glyphicon-refresh"></span>
                </button>
            </span>
        </h3>
    </div>
    <div class="panel-body">
        <?php if (isset($people_del) && !empty($people_del)): ?>
            <div class="table-responsive">
                <table id="table2" class="table table-striped table-hover table-condensed table-bordered tablesorter">
                    <thead>
                        <tr>
                            <th><?php echo __('PEOPLE'); ?></th>
                            <th><?php echo __('card'); ?></th>
                            <th><?php echo __('card_type'); ?></th>
                            <th><?php echo __('note'); ?></th>
                            <th><?php echo __('date_set'); ?></th>
                            <th><?php echo __('count_attampt'); ?></th>
                            <th><?php echo __('load_time'); ?></th>
                            <th><?php echo __('load_result'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $success_count = 0;
                        $error_count = 0;
                        $waiting_count = 0;
                        
                        foreach ($people_del as $value):
                            $load_result = Arr::get($value, 'LOAD_RESULT');
                            if ($load_result === null) {
                                $waiting_count++;
                            } elseif ($load_result == 0) {
                                $success_count++;
                            } else {
                                $error_count++;
                            }
                        ?>
                            <tr>
                                <td>
                                    <?php echo HTML::anchor(
                                        'people/peopleInfo/' . Arr::get($value, 'ID_PEP'),
                                        __('name_order_for_load', array(
                                            ':name' => Arr::get($value, 'NAME'),
                                            ':surname' => Arr::get($value, 'SURNAME'),
                                            ':patronymic' => Arr::get($value, 'PATRONYMIC')
                                        ))
                                    ); ?>
                                </td>
                                <td>
                                    <span class="label label-default"><?php echo Arr::get($value, 'ID_CARD'); ?></span>
                                </td>
                                <td><?php echo Arr::get($value, 'CARD_TYPE_NAME'); ?></td>
                                <td><?php echo Arr::get($value, 'NOTE'); ?></td>
                                <td><?php echo date("d.m.Y H:i:s", strtotime(Arr::get($value, 'TIME_STAMP'))); ?></td>
                                <td>
                                    <span class="label label-<?php echo (Arr::get($value, 'ATTEMPTS') > 50) ? 'danger' : 'warning'; ?>">
                                        <?php echo Arr::get($value, 'ATTEMPTS'); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php 
                                    $load_time = Arr::get($value, 'LOAD_TIME');
                                    echo $load_time ? date("d.m.Y H:i:s", strtotime($load_time)) : __('no_data');
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                    if ($load_result === null) {
                                        echo '<span class="label label-default">' . __('Ожидание') . '</span>';
                                    } elseif ($load_result == 0) {
                                        echo '<span class="label label-success">' . __('Успешно') . '</span>';
                                    } else {
                                        echo '<span class="label label-danger">' . __('Ошибка') . ' (' . $load_result . ')</span>';
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="active">
                            <td colspan="8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            <span class="glyphicon glyphicon-stats" aria-hidden="true"></span>
                                            <?php echo __('Всего записей') . ': ' . count($people_del); ?>
                                        </small>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <small>
                                            <span class="label label-default">
                                                <?php echo __('Ожидание') . ': ' . $waiting_count; ?>
                                            </span>
                                            <span class="label label-success">
                                                <?php echo __('Успешно') . ': ' . $success_count; ?>
                                            </span>
                                            <?php if ($error_count > 0): ?>
                                                <span class="label label-danger">
                                                    <?php echo __('Ошибка') . ': ' . $error_count; ?>
                                                </span>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info" style="margin: 0;">
                <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                <?php echo __('Нет карт для удаления'); ?>
            </div>
        <?php endif; ?>
    </div>
</div>