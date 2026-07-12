<!-- панель События по выбранной точке прохода -->
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">
            <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
            <?php echo __('events_for_door', array(':count' => isset($events) ? count($events) : 0)); ?>
            
            <span class="pull-right">
                <button class="btn btn-xs btn-primary" onclick="location.reload();">
                    <span class="glyphicon glyphicon-refresh"></span>
                </button>
            </span>
        </h3>
    </div>
    <div class="panel-body">
        <?php if (isset($events) && !empty($events)): ?>
            <div class="table-responsive">
                <table id="table3" class="table table-striped table-hover table-condensed table-bordered tablesorter">
                    <thead>
                        <tr>
                            <th><?php echo __('DATETIME'); ?></th>
                            <th><?php echo __('card'); ?></th>
                            <th><?php echo __('name'); ?></th>
                            <th><?php echo __('NAME_EVENT'); ?></th>
                            <th><?php echo __('NAME'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($events as $value):
                            $tr_color = (Arr::get($value, 'ID_EVENTTYPE') == 50) ? 'success' : 'warning';
                        ?>
                            <tr class="<?php echo $tr_color; ?>">
                                <td><?php echo date("d.m.Y H:i:s", strtotime(Arr::get($value, 'DATETIME'))); ?></td>
                                <td>
                                    <span class="label label-default"><?php echo Arr::get($value, 'ID_CARD'); ?></span>
                                </td>
                                <td>
                                    <?php echo HTML::anchor(
                                        '/people/peopleInfo/' . Arr::get($value, 'ID_PEP') . '/' . Arr::get($value, 'ID_CARD'),
                                        Arr::get($value, 'NOTE')
                                    ); ?>
                                </td>
                                <td><?php echo Arr::get($value, 'NAME'); ?></td>
                                <td><?php echo Arr::get($value, 'DEV_NAME'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="active">
                            <td colspan="5">
                                <small class="text-muted">
                                    <span class="glyphicon glyphicon-stats" aria-hidden="true"></span>
                                    <?php echo __('Всего событий') . ': ' . count($events); ?>
                                </small>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info" style="margin: 0;">
                <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                <?php echo __('Нет событий за выбранный период'); ?>
            </div>
        <?php endif; ?>
    </div>
</div>