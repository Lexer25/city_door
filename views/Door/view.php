<!-- Таблица вывода информации по выбранному устройству -->
<script type="text/javascript">
  	$(function() {		
		$("#table1").tablesorter({sortList:[[0,0],[2,1]], widgets: ['zebra']});
		$("#options").tablesorter({sortList: [[0,0]], headers: { 0:{sorter: false}}});
	});	
	
	$(function() {		
		$("#table2").tablesorter({sortList:[[0,0],[2,1]], widgets: ['zebra']});
		$("#options").tablesorter({sortList: [[0,0]], headers: { 0:{sorter: false}}});
	});

	$(function() {		
		$("#table3").tablesorter({sortList:[[0,0],[2,1]], widgets: ['zebra']});
		$("#options").tablesorter({sortList: [[0,0]], headers: { 0:{sorter: false}}});
	});	
		
	$(function() {		
		$("#table4").tablesorter({sortList:[[0,0],[2,1]], widgets: ['zebra']});
		$("#options").tablesorter({sortList: [[0,0]], headers: { 0:{sorter: false}}});
	});

	$(function() {		
		$("#table5").tablesorter({sortList:[[0,0],[2,1]], widgets: ['zebra']});
		$("#options").tablesorter({sortList: [[0,0]], headers: { 0:{sorter: false}}});
	});	
</script>


	

<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title"><?echo __('door_panel_title')?></h3>
  </div>
  <div class="panel-body">
	
	<!-- Информация о точке прохода -->
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title"><?echo __('door_info_title')?></h3>
		</div>
		<table>
			<tr>
				<th width="70%">
				</th>
				<th>
					<? echo __('enable_card_type');?>
				</th>
			</tr>
			<tr>
				<td>
		<?
		echo __('door_info', array(':id_door'=>Arr::get($door, 'ID_DEV'), ':name'=>Arr::get($door, 'NAME'), ':active'=>Arr::get($door, 'ACTIVE'))).'<br>';
		echo __('device_info', array(':id_dev'=>Arr::get($door, 'ID_DEV_DEV'), ':name'=>Arr::get($door, 'DEVICE_NAME'), ':active'=>Arr::get($door, 'DEVICE_ACTIVE'))).'<br>';
		echo __('server_info', array(':name'=>Arr::get($door, 'SERVER_NAME'), ':active'=>Arr::get($door, 'SERVER_ACTIVE'), ':ip'=>Arr::get($door, 'IP'), ':port'=>Arr::get($door, 'PORT'))).'<br>';
		echo __('total_key_in_device', array(':count'=> Arr::get($door, 'KEY_COUNT'))).'<br>';
		echo __('door_active_status', array(':door_active'=> Arr::get($door, 'ACTIVE'), ':device_active'=> Arr::get($door, 'DEVICE_ACTIVE'))).'<br>';
		echo __('device_type', array(':name_door_type'=> Arr::get($door, 'NAME_DOOR_TYPE'), ':door_type'=> Arr::get($door, 'ID_DEVTYPE'))).'<br>';
		
		?> 
			</td>
			<td>
			<?
			if($card_type)
			{
				foreach ($card_type as $key=>$value)
				{
					$checked='';
					foreach ($enable_card_type as $key1=>$value1)
					{
						if(Arr::get($value1, 'ID_CARDTYPE') == Arr::get($value, 'ID') ) $checked='checked="checked"';
					
					}
					echo '<input type="checkbox" name="option2" value="a2" disabled="" '.$checked.'>'.Arr::get($value, 'NAME').'('.Arr::get($value, 'DESCRIPTION').')<Br>';
				
				}
		
			}
			
			?>
			</td>
			</tr>
		</table>
	</div>
 </div> 
</div>
	
	
	
    <?
	$cc=0;
	if(isset($people_add)) $cc=count($people_add)?>
    <ul class="nav nav-tabs">
      <li class="active"><a data-toggle="tab" href="#panel1"><?echo __('list_card_for_load', array(':count'=>$cc));?></a></li>
      <li><a data-toggle="tab" href="#panel2"><?echo __('list_card_for_delete', array(':count'=>$cc));?></a></li>
      <li><a data-toggle="tab" href="#panel3"><?echo __('events_for_dor', array(':count'=>$cc));?></a></li>
      <li><a data-toggle="tab" href="#panel4"><?echo __('keys_for_door', array(':count'=>$cc));?></a></li>
    </ul>
      
     
    <div class="tab-content">
      <div id="panel1" class="tab-pane fade in active">
        <h3>Панель 1</h3>
        <p>Содержимое 1 панели...</p>
        <!-- панель Очередь для загрузки -->
	<div class="panel panel-primary">
		<div class="panel-heading ">
			
			<h3 class="panel-title"><?echo __('list_card_for_load', array(':count'=>$cc));?></h3>
			
		</div>	
		<div class="panel-body">
	<?
		
		if(isset($people_add))
		{?>
		<!-- <table class="table table-striped table-hover table-condensed table-bordered tablesorter"> -->
		<table id="table1" class="tablesorter">
			<thead>
					<tr>
						<th><?echo __('PEOPLE');?></th>
						<th><?echo __('card');?></th>
						<th><?echo __('card_type');?></th>
						<th><?echo __('note');?></th>
						<th><?echo __('date_set');?></th>
						<th><?echo __('count_attampt');?></th>
						<th><?echo __('load_time');?></th>
						<th><?echo __('load_result');?></th>
					</tr>
			</thead>
		<tbody>
			<?foreach ($people_add as $key=>$value)
				{
					echo '<tr>';
						echo '<td>'.HTML::anchor('people/peopleInfo/'.Arr::get($value, 'ID_PEP'), __('name_order_for_load', array(':name'=> Arr::get($value, 'NAME'), ':surname'=> Arr::get($value, 'SURNAME'), ':patronymic'=> Arr::get($value, 'PATRONYMIC'), ))).'</td>';
						echo '<td>'.Arr::get($value, 'ID_CARD').'</td>';
						echo '<td>'.Arr::get($value, 'CARD_TYPE_NAME').'</td>';
						echo '<td>'.Arr::get($value, 'NOTE').'</td>';
						echo '<td>'.Arr::get($value, 'TIME_STAMP').'</td>';
						echo '<td>'.Arr::get($value, 'ATTEMPTS').'</td>';
						echo '<td>'.Arr::get($value, 'LOAD_TIME').'</td>';
						echo '<td>'.Arr::get($value, 'LOAD_RESULT').'</td>';
					echo '</tr>';
					
				}
							
				?>
						<tr>
							<td>--</td>
							<td>--</td>
							<td>--</td>
							<td>--</td>
							<td>--</td>
							<td>--</td>
							<td>--</td>
							<td>--</td>
						</tr>					
		</tbody>
				
		</table><?
		
		} else {
		
		echo __('no_data');
		}
		?>
		</div>
	</div>
      </div>
      <div id="panel2" class="tab-pane fade">
        <h3>Панель 2</h3>
        <p>Содержимое 2 панели...</p>
        <!-- панель Очередь для удалдения -->
	<div class="panel panel-primary">
		<div class="panel-heading">
			<?
			$cc=0;
			if(isset($people_del)) $cc=count($people_del)?>
			<h3 class="panel-title"><?echo __('list_card_for_delete', array(':count'=>$cc));?></h3>
		</div>	
		
		<div class="panel-body">
			<?
				if(isset($people_del))
				{?>
				<!-- <table class="table table-striped table-hover table-condensed table-bordered"> -->
			<table id="table2" class="tablesorter">
				<thead> 
					<tr>
						<th><?echo __('PEOPLE');?></th>
						<th><?echo __('card');?></th>
						<th><?echo __('card_type');?></th>
						<th><?echo __('note');?></th>
						<th><?echo __('date_set');?></th>
						<th><?echo __('count_attampt');?></th>
						<th><?echo __('load_time');?></th>
						<th><?echo __('load_result');?></th>
					</tr>
				</thead>
				<tbody>
					<?foreach ($people_del as $key=>$value)
						{
							echo '<tr>';
								echo '<td>'.HTML::anchor('people/peopleInfo/'.Arr::get($value, 'ID_PEP'), __('name_order_for_load', array(':name'=> Arr::get($value, 'NAME'), ':surname'=> Arr::get($value, 'SURNAME'), ':patronymic'=> Arr::get($value, 'PATRONYMIC'), ))).'</td>';
								echo '<td>'.Arr::get($value, 'ID_CARD').'</td>';
								echo '<td>'.Arr::get($value, 'CARD_TYPE_NAME').'</td>';
								echo '<td>'.Arr::get($value, 'NOTE').'</td>';
								echo '<td>'.Arr::get($value, 'TIME_STAMP').'</td>';
								echo '<td>'.Arr::get($value, 'ATTEMPTS').'</td>';
								echo '<td>'.Arr::get($value, 'LOAD_TIME', __('no_data')).'</td>';
								echo '<td>'.Arr::get($value, 'LOAD_RESULT', __('no_data')).'</td>';
							echo '</tr>';
							
						}
									
						?>
						<tr>
							<td>--</td>
							<td>--</td>
							<td>--</td>
							<td>--</td>
							<td>--</td>
							<td>--</td>
							<td>--</td>
						</tr>	
				</tbody>
			</table><?
				
				} else {
				
				echo __('no_data');
				}
				?>
		</div>
	</div>
      </div>
      <div id="panel3" class="tab-pane fade">
        <h3>Панель 3</h3>
        <p>Содержимое 3 панели...</p>
        <!-- панель События по выбранной точке прохода -->
	<div class="panel panel-primary">
		<div class="panel-heading">
			<?
			$cc=0;
			if(isset($people_del)) $cc=count($people_del)?>
			<h3 class="panel-title"><?echo __('events_for_dor', array(':count'=>$cc));?></h3>
		</div>
		<div class="panel-body">	
		<?
			if(isset($events))
			{?>
			<!-- <table class="table table-striped table-hover table-condensed table-bordered">  -->
			<table id="table3" class="tablesorter">
				<thead>
					<tr>
						<th><?echo __('DATETIME');?></th>
						<th><?echo __('card');?></th>
						<th><?echo __('name');?></th>
						<th><?echo __('NAME_EVENT');?></th>
						<th><?echo __('NAME');?></th>
					</tr>
				</thead>
				<tbody>	
					<?foreach ($events as $key=>$value)
						{
							$tr_color=(Arr::get($value, 'ID_EVENTTYPE') == 50) ? 'success' : 'warning';
							echo '<tr class="'.$tr_color .'">';
								echo '<td>'.Arr::get($value, 'DATETIME').'</td>';
								echo '<td>'.Arr::get($value, 'ID_CARD').'</td>';
								echo '<td>'.HTML::anchor('/people/peopleInfo/'.Arr::get($value, 'ID_PEP').'/'.Arr::get($value, 'ID_CARD'), Arr::get($value, 'NOTE')).'</td>';
								echo '<td>'.Arr::get($value, 'NAME').'</td>';
								echo '<td>'.Arr::get($value, 'DEV_NAME').'</td>';
							echo '</tr>';
							
						}?>
						<tr>
							<td>--</td>
							<td>--</td>
							<td>--</td>
							<td>--</td>
							<td>--</td>
						</tr>	
						
				</tbody>
			</table><?
			
			} else {
			
			echo __('no_data');
			}
			?>
		</div>
	</div>
	
      </div>
      <div id="panel4" class="tab-pane fade">
        <h3>Панель 4</h3>
        <p>Содержимое 4 панели...</p>
        <!-- Загруженные карты в точку прохода -->
	<div class="panel panel-primary">
		<div class="panel-heading">
			<?
			
			$cc=0;
			if(isset($people_del)) $cc=count($people_del)?>
			<h3 class="panel-title"><?echo __('keys_for_door', array(':count'=>$cc));?></h3>
		</div>	
		<div class="panel-body">
	<?
	if(count($keys)>0)
		{?>
		<!-- <table class="table table-striped table-hover table-condensed table-bordered">  -->
		<table id="table4" class="tablesorter">
			<thead>
				<tr>
					<th><?echo __('ID_CARD');?></th>
					<th><?echo __('DEVIDX');?></th>
					<th><?echo __('LOAD_TIME');?></th>
					<th><?echo __('LOAD_RESULT');?></th>
					<th><?echo __('TIME_STAMP');?></th>
					<th><?echo __('TIMESTART');?></th>
					<th><?echo __('TIMEEND');?></th>
					<th><?echo __('PEOPLE');?></th>
					<th><?echo __('ID_PEP');?></th>
				</tr>
			</thead>
			<tbody>		
			<?foreach ($keys as $key=>$value)
				{
					$tr_color=(Arr::get($value, 'ID_EVENTTYPE') == 50) ? 'success' : 'warning';
					echo '<tr class="'.$tr_color .'">';
						echo '<td>'.Arr::get($value, 'ID_CARD').'</td>';
						echo '<td>'.Arr::get($value, 'DEVIDX').'</td>';
						echo '<td>'.Arr::get($value, 'LOAD_TIME').'</td>';
						echo '<td>'.Arr::get($value, 'LOAD_RESULT').'</td>';
						echo '<td>'.Arr::get($value, 'TIME_STAMP').'</td>';
						echo '<td>'.Arr::get($value, 'TIMESTART').'</td>';
						echo '<td>'.Arr::get($value, 'TIMEEND').'</td>';
						echo '<td>'.Arr::get($value, 'PEOPLE').'</td>';
						echo '<td>'.Arr::get($value, 'ID_PEP').'</td>';
					echo '</tr>';
				}
							
				?>
				
			</tbody>
		</table><?
		} else {
			echo __('no_data');
		}
		?>
		</div>
	</div>	
	
      </div>

   </div>	