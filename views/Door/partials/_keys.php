<!-- Загруженные карты в точку прохода -->
<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title">
            <span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span>
            <?php echo __('keys_for_door', array(':count' => isset($keys) ? count($keys) : 0)); ?>
            
            <span class="pull-right">
                <span class="badge" style="background-color: #5cb85c; margin-right: 10px;">
                    <?php echo isset($keys) ? count($keys) : 0; ?>
                </span>
                <button class="btn btn-xs btn-success" onclick="location.reload();">
                    <span class="glyphicon glyphicon-refresh"></span>
                </button>
            </span>
        </h3>
    </div>
    <div class="panel-body">
        <?php if (isset($keys) && !empty($keys)): ?>
            <div class="table-responsive">
                <table id="table4" class="table table-striped table-hover table-condensed table-bordered tablesorter">
                    <thead>
                        <tr>
                            <th><?php echo __('ID_CARD'); ?></th>
                            <th><?php echo __('LOAD_TIME'); ?></th>
                            <th><?php echo __('LOAD_RESULT'); ?></th>
                            <th><?php echo __('TIME_STAMP'); ?></th>
                            <th><?php echo __('TIMESTART'); ?></th>
                            <th><?php echo __('TIMEEND'); ?></th>
                            <th><?php echo __('PEOPLE'); ?></th>
                            <th><?php echo __('ID_PEP'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $active_count = 0;
                        $expired_count = 0;
                        $error_count = 0;
                        
                        foreach ($keys as $value):
                            $load_result = Arr::get($value, 'LOAD_RESULT');
                            $tr_color = ($load_result == 0) ? 'success' : 'warning';
                            
                            // Проверка активности карты по датам
                            $is_active = true;
                            $timestart = Arr::get($value, 'TIMESTART');
                            $timeend = Arr::get($value, 'TIMEEND');
                            $now = time();
                            
                            if ($timestart && strtotime($timestart) > $now) {
                                $is_active = false;
                                $tr_color = 'info';
                            }
                            if ($timeend && strtotime($timeend) < $now) {
                                $is_active = false;
                                $tr_color = 'danger';
                                $expired_count++;
                            }
                            
                            if ($is_active) {
                                $active_count++;
                            }
                            
                            if ($load_result != 0 && $load_result !== null) {
                                $error_count++;
                            }
                        ?>
                            <tr class="<?php echo $tr_color; ?>">
                                <td>
                                    <span class="label label-<?php echo $is_active ? 'success' : 'default'; ?>">
                                        <?php echo Arr::get($value, 'ID_CARD'); ?>
                                    </span>
                                </td>
                                 <td>
                                    <?php 
                                    $load_time = Arr::get($value, 'LOAD_TIME');
                                    echo $load_time ? date("d.m.Y H:i:s", strtotime($load_time)) : '—';
                                    ?>
                                </td>
                                <td>
                                    <?php 
									//echo Debug::vars('82', $load_time, $load_result);//exit;
                                    if ($load_time === null) {
                                        echo '<span class="label label-danger">' . __('Не загружена') . '</span>';
                                    } else {
                                        echo '<span class="label label-success">' . __('Успешно') . ' '.$load_result. '</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                    $time_stamp = Arr::get($value, 'TIME_STAMP');
                                    echo $time_stamp ? date("d.m.Y H:i:s", strtotime($time_stamp)) : '—';
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                    $timestart = Arr::get($value, 'TIMESTART');
                                    echo $timestart ? date("d.m.Y H:i:s", strtotime($timestart)) : '—';
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                    $timeend = Arr::get($value, 'TIMEEND');
                                    echo $timeend ? date("d.m.Y H:i:s", strtotime($timeend)) : '—';
                                    ?>
                                </td>
                                <td>
                                    <?php echo HTML::anchor(
                                        'people/peopleInfo/' . Arr::get($value, 'ID_PEP'),
                                        Arr::get($value, 'PEOPLE')
                                    ); ?>
                                </td>
                                <td><?php echo Arr::get($value, 'ID_PEP'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="active">
                            <td colspan="9">
                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            <span class="glyphicon glyphicon-stats" aria-hidden="true"></span>
                                            <?php echo __('Всего карт') . ': ' . count($keys); ?>
                                        </small>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <small>
                                            <span class="label label-success">
                                                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                                                <?php echo __('Активные') . ': ' . $active_count; ?>
                                            </span>
                                            <span class="label label-danger">
                                                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                                <?php echo __('Истекшие') . ': ' . $expired_count; ?>
                                            </span>
                                            <?php if ($error_count > 0): ?>
                                                <span class="label label-warning">
                                                    <span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span>
                                                    <?php echo __('С ошибкой') . ': ' . $error_count; ?>
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
                <?php echo __('В точку прохода не загружено ни одной карты'); ?>
            </div>
        <?php endif; ?>
    </div>
</div>