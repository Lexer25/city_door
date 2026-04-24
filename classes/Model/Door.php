<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Door extends Model
{
	
	public function findIdDoor ($search) // поиск двери по названию
	{
	if ($search == NULL) return NULL;
	//echo Debug::vars('9', strlen($search)); //exit;
	//if(strlen($search)<2) return NULL;
	
	$sql='select distinct d.id_dev, d.name, d."ACTIVE" , d2.id_dev as id_dev_dev, d2.name as device_name, d2."ACTIVE" as device_active, s.name as server_name, s.ip, s.port, s."ACTIVE" as server_active, cd.operation, count(*) from device d
        join device d2 on d2.id_ctrl=d.id_ctrl and d2.id_reader is null
        join server s on d2.id_server=s.id_server
        left join cardindev cd on d.id_dev=cd.id_dev
        where d.name containing \''.$search.'\' and
        d.id_reader is not null
        group by d.id_dev, d.name, d."ACTIVE" , d2.id_dev, d2.name, d2."ACTIVE", s.name, s.ip, s.port, s."ACTIVE", cd.operation';
		
	if(is_numeric($search)) 
	{
	$sql='select distinct d.id_dev, d.name, d."ACTIVE" , d2.id_dev as id_dev_dev, d2.name as device_name, d2."ACTIVE" as device_active, s.name as server_name, s.ip, s.port, s."ACTIVE" as server_active, cd.operation, count(*) from device d
        join device d2 on d2.id_ctrl=d.id_ctrl and d2.id_reader is null
        join server s on d2.id_server=s.id_server
        left join cardindev cd on d.id_dev=cd.id_dev
        where (d.name containing \''.$search.'\' and
        d.id_reader is not null) or (
		d.id_dev='.$search.')
        group by d.id_dev, d.name, d."ACTIVE" , d2.id_dev, d2.name, d2."ACTIVE", s.name, s.ip, s.port, s."ACTIVE", cd.operation';


	}		

		$query = DB::query(Database::SELECT, iconv('UTF-8','windows-1251',$sql))
			->execute(Database::instance('fb'))
			->as_array();
	//echo Debug::vars('28',$sql,  $query); exit;

	
	$res=array();
		foreach ($query as $key=>$value)
		{
			foreach ($value as $name=>$data)
				{
					
					if($name=='NAME' or $name=='DEVICE_NAME' or $name=='SERVER_NAME')
						{ $res[$key][$name]=iconv('windows-1251','UTF-8',$data);
						
						} else {
						
						$res[$key][$name]=$data;
						}
				}

		}
	return $res;
	}
	
	
	
	public function getDoor($id_door)//полученние данных для указанной точки прохода
	{
	$sql='select d.*, d2.id_dev as id_dev_dev, d2.name as device_name, d2."ACTIVE" as device_active, s.name as server_name, s.ip, s.port, s."ACTIVE" as server_active, dt.name as name_door_type from device d
        join device d2 on d2.id_ctrl=d.id_ctrl and d2.id_reader is null
        left join server s on d2.id_server=s.id_server
		join devtype dt on dt.id_devtype=d.id_devtype
		where d.id_dev='.$id_door;

		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		$res=array();
		
		foreach ($query as $key=>$value)
		{
			foreach ($value as $name=>$data)
				{
					if($name=='NAME' or $name=='DEVICE_NAME' or $name=='SERVER_NAME' or $name=='NAME_DOOR_TYPE')
						{ $res[$name]=iconv('windows-1251','UTF-8',$data);
					} else {
					$res[$name]=$data;
					}
				}
			$res['IP'] = Model::Factory('Stat')->IntToIP(Arr::get($res, 'IP'));
		}
		
		$sql='select count(*) from cardidx cd where cd.id_dev='.$id_door;
		$query = DB::query(Database::SELECT, $sql)
		->execute(Database::instance('fb'))
		->get('COUNT', 0);
		$res['KEY_COUNT']=$query;
		
		return $res;
	}

	public function getDoorLoadorder($id_door)// список пользователей для загрузки 
	{
		$maxAtt=Model::Factory('Stat')->getmaxAttempts();
		$operation=1;//операция запись в контроллеры
		$res=$this->getListPeopleFromCardInDev($id_door, $operation);
		return $res;
	}
	
	
	public function getDoorDeleteOrder($id_door)// список пользователей для удаления 
	{
		$maxAtt=Model::Factory('Stat')->getmaxAttempts();
		$operation=2;//операция запись в контроллеры
		$res=$this->getListPeopleFromCardInDev($id_door, $operation);
		return $res;
	}


	public function getListPeopleFromCardInDev ($id_dev, $operation, $maxAtt = false)//получить список пользователей из таблицы cardindev
	{
		$maxAtt=Model::Factory('Stat')->getmaxAttempts();
		
		$sql='select cd.id_cardindev, cd.id_card, cd.operation, cd.attempts, cd.id_pep, cd.time_stamp, cd.id_cardtype, ct.name as card_type_name, p.surname, p.name, p.patronymic, p.note, cx.load_time, cx.load_result from cardindev cd
            left join people p on p.id_pep=cd.id_pep
            left join cardidx cx on cd.id_card=cx.id_card and cd.id_dev=cx.id_dev
            left join cardtype ct on ct.id=cd.id_cardtype
			where cd.id_dev='.$id_dev.' and
			cd.attempts<200 and
			cd.operation='.$operation;
			
		$sql='select cd.id_cardindev, cd.id_card, cd.operation, cd.attempts, cd.id_pep, cd.time_stamp, cd.id_cardtype, ct.name as card_type_name, p.surname, p.name, p.patronymic, p.note, cx.load_time, cx.load_result from cardindev cd
            left join people p on p.id_pep=cd.id_pep
            left join cardidx cx on cd.id_card=cx.id_card and cd.id_dev=cx.id_dev
            left join cardtype ct on ct.id=cd.id_cardtype
			where cd.id_dev='.$id_dev.' and
			cd.operation='.$operation;
			
			
	//echo Debug::vars('125', $sql); exit;	
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		$res=array();
		//if ($operation==2) echo Debug::vars('114', $sql, $query);
		//if ($operation==2) echo Debug::vars('114', $sql);

		foreach ($query as $key=>$value)
		{
			foreach ($value as $name=>$data)
				{
					if($name=='NAME' or $name=='SURNAME' or $name=='PATRONYMIC' or $name=='NOTE' or $name=='LOAD_RESULT' or $name=='CARD_TYPE_NAME')
						{ $res[$key][$name]=iconv('windows-1251','UTF-8',$data);
						} else {
						$res[$key][$name]=$data;
						}
				}
		}
		return $res;
	
	}
	
	public function getKeysForDoor($id_dev) 
	{
		$sql='select cd.id_card, cd.devidx, cd.load_time, cd.load_result, cd.time_stamp, c.timestart, c.timeend, p.surname,p.id_pep, p.name, p.patronymic from cardidx cd
			join card c on cd.id_card=c.id_card
			join people p on c.id_pep=p.id_pep
			where cd.id_dev='.$id_dev.'
			order by cd.id_card';
		$query = DB::query(Database::SELECT, $sql)
		->execute(Database::instance('fb'))
		->as_array();
		$res=array();
		foreach ($query as $key=>$value)
		{
			$res[$key]['ID_CARD']=Arr::get($value, 'ID_CARD');
			$res[$key]['DEVIDX']=Arr::get($value, 'DEVIDX');
			$res[$key]['LOAD_TIME']=Arr::get($value, 'LOAD_TIME');
			$res[$key]['LOAD_RESULT']=iconv('windows-1251','UTF-8',Arr::get($value, 'LOAD_RESULT'));
			$res[$key]['TIME_STAMP']=Arr::get($value, 'TIME_STAMP');
			$res[$key]['TIMESTART']=Arr::get($value, 'TIMESTART');
			$res[$key]['TIMEEND']=Arr::get($value, 'TIMEEND');
			$res[$key]['PEOPLE']=iconv('windows-1251','UTF-8', Arr::get($value, 'NAME').' '.Arr::get($value, 'SURNAME').' '.Arr::get($value, 'PATRONYMIC'));
			$res[$key]['ID_PEP']=Arr::get($value, 'ID_PEP');
			
		}
		return $res;
	}
	
	public function getCardType()
	{
		$sql='select ct.id, ct.name, ct.description from cardtype ct';
		$query = DB::query(Database::SELECT, $sql)
		->execute(Database::instance('fb'))
		->as_array();
		foreach ($query as $key=>$value)
		{
			foreach ($value as $name=>$data)
				{
					if($name=='NAME' or $name=='DESCRIPTION')
						{ $res[$key][$name]=iconv('windows-1251','UTF-8',$data);
						} else {
						$res[$key][$name]=$data;
						}
				}
		}
		return $res;
	
	}
	
	public function getEnableCardType($devtype)//список допустимых типов карт для указанного типа контроллера
	{
		$sql='select dc.id_cardtype from devtype_cardtype dc where dc.id_devtype='.$devtype;
		$query = DB::query(Database::SELECT, $sql)
		->execute(Database::instance('fb'))
		->as_array();
		return $query;
	
	}
	
}
	

