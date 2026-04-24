<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title"><?echo __('door_panel_title')?></h3>
  </div>
  <div class="panel-body">
	
	<?php 
	
	//$value=$contact;
		//echo Debug::vars('11',$value);
		?>
		<table class="table table-striped table-hover table-condensed table-bordered">
		<tr>
			<th><?echo __('door_id');?></th>
			<th><?echo __('door_name');?></th>
			<th><?echo __('device_name');?></th>
			<th><?echo __('server_name');?></th>
			<th><?echo __('count_for_add');?></th>
			<th><?echo __('count_for_del');?></th>
			
			
		</tr>
		
		<?foreach ($list as $key=>$contact)
		{
		echo '<tr>';
			echo '<td>'.Arr::get($contact, 'ID_DEV').'</td>';
				echo '<td>'.HTML::anchor('door/doorInfo/'.Arr::get($contact, 'ID_DEV'),  Arr::get($contact,'NAME')).'</td>';
				echo '<td>'.Arr::get($contact, 'DEVICE_NAME', __('No_data')).'</td>';
				echo '<td>'.Arr::get($contact, 'SERVER_NAME', __('No_data')).'</td>';
				if(Arr::get($contact, 'OPERATION')==1) 
				{
					echo '<td>'.Arr::get($contact, 'COUNT') .'</td>';
					echo '<td> - </td>';
				}				
				
				if(Arr::get($contact, 'OPERATION')==2) 
				{
					echo '<td> - </td>';
					echo '<td>'.Arr::get($contact, 'COUNT') .'</td>';
				}
				
				if(Arr::get($contact, 'OPERATION') !=1 and Arr::get($contact, 'OPERATION') !=2 ) 
				{
					echo '<td> - </td>';
					echo '<td> - </td>';
				}
				
			echo '</tr>';
					
			}
				?>
			
	</table>
		
	

</div>	
	
  
</div>
