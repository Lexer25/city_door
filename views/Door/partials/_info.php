<!-- Информация о точке прохода -->
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title"><?php echo __('door_info_title'); ?></h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-8">
                <?php
                echo __('door_info', array(
                    ':id_door' => Arr::get($door, 'ID_DEV'),
                    ':name' => Arr::get($door, 'NAME'),
                    ':active' => statusBadge(Arr::get($door, 'ACTIVE'))
                )) . '<br>';
                
                echo __('device_info', array(
                    ':id_dev' => Arr::get($door, 'ID_DEV_DEV'),
                    ':name' => Arr::get($door, 'DEVICE_NAME'),
                    ':active' => statusBadge(Arr::get($door, 'DEVICE_ACTIVE'))
                )) . '<br>';
                
                echo __('server_info', array(
                    ':name' => Arr::get($door, 'SERVER_NAME'),
                    ':active' => statusBadge(Arr::get($door, 'SERVER_ACTIVE')),
                    ':ip' => Arr::get($door, 'IP'),
                    ':port' => Arr::get($door, 'PORT')
                )) . '<br>';
                
                echo __('total_key_in_device', array(
                    ':count' => Arr::get($door, 'KEY_COUNT')
                )) . '<br>';
                
                echo __('door_active_status', array(
                    ':door_active' => statusBadge(Arr::get($door, 'ACTIVE')),
                    ':device_active' => statusBadge(Arr::get($door, 'DEVICE_ACTIVE'))
                )) . '<br>';
                
                echo __('device_type', array(
                    ':name_door_type' => Arr::get($door, 'NAME_DOOR_TYPE'),
                    ':door_type' => Arr::get($door, 'ID_DEVTYPE')
                )) . '<br>';
                ?>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title"><?php echo __('enable_card_type'); ?></h4>
                    </div>
                    <div class="panel-body">
                        <?php if ($card_type): ?>
                            <?php foreach ($card_type as $value): ?>
                                <?php
                                $checked = '';
                                foreach ($enable_card_type as $value1) {
                                    if (Arr::get($value1, 'ID_CARDTYPE') == Arr::get($value, 'ID')) {
                                        $checked = 'checked="checked"';
                                        break;
                                    }
                                }
                                ?>
                                <div>
                                    <input type="checkbox" <?php echo $checked; ?> disabled>
                                    <?php echo Arr::get($value, 'NAME'); ?>
                                    <small class="text-muted">(<?php echo Arr::get($value, 'DESCRIPTION'); ?>)</small>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted"><?php echo __('no_data'); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>