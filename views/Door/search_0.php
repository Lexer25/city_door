<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title"><?echo __('door_panel_title')?></h3>
  </div>
  <div class="panel-body">
	
	
	<form class="navbar-form navbar-left" role="search" action="find">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Не менее 3-х букв" name="doorInfo">
        </div>
        <button type="submit" class="btn btn-default">Найти</button>
		<!-- Инициализация виджета "Bootstrap datetimepicker" -->
    <div class="row">
		<div class="col-xs-1">
			<p class="text-primary text-center">c</p>
		</div>
		<div class="col-xs-5">
		<div class="form-group">
		  <div class="input-group date" id="datetimepicker1">
			
			<input type="text" class="form-control" name="timeFrom" >
			<span class="input-group-addon">
			  <span class="glyphicon glyphicon-calendar"></span>
			</span>
			
  
		  </div>
		</div>
		</div>
		<div class="col-xs-1">
			<p class="text-primary text-center">по</p>
		</div>
		<div class="col-xs-5">
		<div class="form-group">
		  <div class="input-group date" id="datetimepicker2">
			<input type="text" class="form-control" name="timeTo">
			<span class="input-group-addon">
			  <span class="glyphicon glyphicon-calendar"></span>
			</span>
		  </div>
		</div>
		</div>
    </div>
      </form> 
	  
	 </div>
    <script type="text/javascript">
      $(function () {
	  
	  var dateEnd=new Date();
	  dateEnd.setHours(23, 59, 59, 0);
	  
		var dateBegin = new Date();
		dateBegin.setHours(0, 0, 0, 0);
	    //Инициализация datetimepicker1 и datetimepicker2
        $("#datetimepicker1").datetimepicker(
		{language: 'ru', 
		showToday: true,
		sideBySide: true,
		defaultDate: dateBegin
		}
		);
        $("#datetimepicker2").datetimepicker(
		{language: 'ru', 
		showToday: true,
		sideBySide: true,
		defaultDate: dateEnd
		}
		);
        //При изменении даты в 1 datetimepicker, она устанавливается как минимальная для 2 datetimepicker
        $("#datetimepicker1").on("dp.change",function (e) {
          $("#datetimepicker2").data("DateTimePicker").setMinDate(e.date);
        });
        //При изменении даты в 2 datetimepicker, она устанавливается как максимальная для 1 datetimepicker
        $("#datetimepicker2").on("dp.change",function (e) {
          $("#datetimepicker1").data("DateTimePicker").setMaxDate(e.date);
        });
      });
    </script>
     

	
      </form>

</div>	
	
  
</div>
</div>