<?php // БЛОК: Категории доступа (сворачиваемый) ?>
<div class="panel panel-info" id="accessPanel">
    <div class="panel-heading" role="tab" id="accessHeading">
        <h3 class="panel-title">
            <a role="button" 
               data-toggle="collapse" 
               data-parent="#accordion" 
               href="#accessCollapse" 
               aria-expanded="true" 
               aria-controls="accessCollapse"
               id="accessToggle"
               style="display: block; text-decoration: none; color: inherit;">
                <span class="glyphicon glyphicon-lock" aria-hidden="true"></span>
                <?php echo __('Категории доступа, содержащие данную точку прохода'); ?>
                <span class="badge pull-right">
                    <?php echo isset($access_categories) ? count($access_categories) : 0; ?>
                </span>
                <span class="pull-right toggle-icon" style="margin-right: 10px;">
                    <span class="glyphicon glyphicon-chevron-up" aria-hidden="true" id="iconCollapse"></span>
                    <span class="glyphicon glyphicon-chevron-down" aria-hidden="true" id="iconExpand" style="display: none;"></span>
                </span>
            </a>
        </h3>
    </div>
    <div id="accessCollapse" 
         class="panel-collapse collapse in" 
         role="tabpanel" 
         aria-labelledby="accessHeading">
        <div class="panel-body">
            <?php if (isset($access_categories) && !empty($access_categories)): ?>
                <div class="row">
                    <?php 
                    $total_categories = count($access_categories);
                    $columns = 3;
                    $per_column = ceil($total_categories / $columns);
                    $current_index = 0;
                    ?>
                    
                    <?php for ($col = 0; $col < $columns; $col++): ?>
                        <div class="col-md-4 col-sm-6">
                            <?php for ($i = 0; $i < $per_column && $current_index < $total_categories; $i++, $current_index++): 
                                $category = $access_categories[$current_index];
                            ?>
                                <div style="margin-bottom: 5px;">
                                    <a href="/access/accessInfo/<?php echo $category['ID_ACCESSNAME']; ?>" 
                                       style="text-decoration: none;">
                                        <span class="label label-primary" 
                                              style="display: inline-block; padding: 5px 10px; font-size: 12px; width: 100%; cursor: pointer;">
                                            <span class="glyphicon glyphicon-tag" aria-hidden="true"></span>
                                            <?php echo htmlspecialchars($category['NAME']); ?>
                                            <small style="color: #d9edf7; float: right;">
                                                ID: <?php echo $category['ID_ACCESS']; ?>
                                            </small>
                                        </span>
                                    </a>
                                </div>
                            <?php endfor; ?>
                        </div>
                    <?php endfor; ?>
                </div>
                
                <div style="margin-top: 15px; padding-top: 10px; border-top: 1px solid #eee;">
                    <small class="text-muted">
                        <span class="glyphicon glyphicon-stats" aria-hidden="true"></span>
                        <?php echo __('Всего категорий') . ': ' . count($access_categories); ?>
                    </small>
                </div>
            <?php else: ?>
                <div class="alert alert-info" style="margin: 0;">
                    <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                    <?php echo __('Точка прохода не входит ни в одну категорию доступа'); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>