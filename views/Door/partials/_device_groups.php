<!-- БЛОК: Группы устройств (сворачиваемый) -->
<div class="panel panel-success" id="deviceGroupsPanel">
    <div class="panel-heading" role="tab" id="deviceGroupsHeading">
        <h3 class="panel-title">
            <a role="button" 
               data-toggle="collapse" 
               data-parent="#accordion" 
               href="#deviceGroupsCollapse" 
               aria-expanded="true" 
               aria-controls="deviceGroupsCollapse"
               id="deviceGroupsToggle"
               style="display: block; text-decoration: none; color: inherit;">
                <span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>
                <?php echo __('Группы устройств, содержащие данную точку прохода'); ?>
                <span class="badge pull-right">
                    <?php 
                    $count = 0;
                    if (isset($device_groups)) {
                        foreach ($device_groups as $g) {
                            if ($g['ID_DEVGROUP'] != 1) {
                                $count++;
                            }
                        }
                    }
                    echo $count;
                    ?>
                </span>
                <span class="pull-right toggle-icon" style="margin-right: 10px;">
                    <span class="glyphicon glyphicon-chevron-up" aria-hidden="true" id="deviceGroupsIconCollapse"></span>
                    <span class="glyphicon glyphicon-chevron-down" aria-hidden="true" id="deviceGroupsIconExpand" style="display: none;"></span>
                </span>
            </a>
        </h3>
    </div>
    <div id="deviceGroupsCollapse" 
         class="panel-collapse collapse in" 
         role="tabpanel" 
         aria-labelledby="deviceGroupsHeading">
        <div class="panel-body">
            <?php if (isset($device_groups) && !empty($device_groups)): 
			
			
                // Фильтруем группы, исключая корневую (ID_DEVGROUP = 1)
                $filtered_groups = array();
                foreach ($device_groups as $group) {
                    if ($group['ID_DEVGROUP'] != 1) {
                        $filtered_groups[] = $group;
                    }
                }
                
                if (!empty($filtered_groups)):
            ?>
                <div class="row">
                    <?php 
                    $total_groups = count($filtered_groups);
                    $columns = 2;
                    $per_column = ceil($total_groups / $columns);
                    $current_index = 0;
                    ?>
                    
                    <?php for ($col = 0; $col < $columns; $col++): ?>
                        <div class="col-md-6 col-sm-6">
                            <?php for ($i = 0; $i < $per_column && $current_index < $total_groups; $i++, $current_index++): 
                                $group = $filtered_groups[$current_index];
                                $parent_chain = isset($group['PARENT_CHAIN']) ? $group['PARENT_CHAIN'] : array();
                                
                                // Фильтруем цепочку родителей - удаляем корневую группу (ID_DEVGROUP = 1)
                                $filtered_chain = array();
                                foreach ($parent_chain as $parent) {
                                    if ($parent['ID_DEVGROUP'] != 1) {
                                        $filtered_chain[] = $parent;
                                    }
                                }
                                
                                // Строим путь через слеши
                                $path_parts = array();
                                if (!empty($filtered_chain)) {
                                    $chain = array_reverse($filtered_chain);
                                    foreach ($chain as $parent) {
                                        $path_parts[] = $parent['NAME'];
                                    }
                                }
                                //$path_parts[] = $group['NAME'];//название точки прохода убираю
								
                                $full_path = implode(' / ', $path_parts);
                            ?>
                                <div style="margin-bottom: 5px;">
                                    <span class="label label-success" 
                                          style="display: inline-block; padding: 5px 10px; font-size: 12px; width: 100%; cursor: pointer; background-color: #5cb85c;">
                                        <span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>
                                        <?php echo htmlspecialchars($full_path); ?>
                                       
                                    </span>
                                </div>
                            <?php endfor; ?>
                        </div>
                    <?php endfor; ?>
                </div>
                
                <div style="margin-top: 15px; padding-top: 10px; border-top: 1px solid #eee;">
                    <small class="text-muted">
                        <span class="glyphicon glyphicon-stats" aria-hidden="true"></span>
                        <?php echo __('Всего групп') . ': ' . count($filtered_groups); ?>
                    </small>
                </div>
            <?php else: ?>
                <div class="alert alert-info" style="margin: 0;">
                    <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                    <?php echo __('Точка прохода не входит ни в одну группу устройств'); ?>
                </div>
            <?php endif; ?>
            <?php else: ?>
                <div class="alert alert-info" style="margin: 0;">
                    <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                    <?php echo __('Точка прохода не входит ни в одну группу устройств'); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>