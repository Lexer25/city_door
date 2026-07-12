<?php defined('SYSPATH') or die('No direct script access.');
class Controller_Door extends Controller_Template { 

	public function before()
	{
			
			parent::before();
			$session = Session::instance();
			//echo Debug::vars('9controller', $_POST, $_GET);
			
	}
	
	
	public function action_index()
	{
		$_SESSION['menu_active']='door';
		$content = View::factory('door/search');
        $this->template->content = $content;
	}
	 
	 
	 public function action_find()
	 {
	 
	 $search=Arr::get($_GET, 'doorInfo');
	 $_SESSION['doorEventsTimeFrom']=Arr::get($_GET, 'timeFrom');
		$_SESSION['doorEventsTimeTo']=Arr::get($_GET, 'timeTo');
	 $result=Model::Factory('Door')->findIdDoor($search);
		 if(count($result)>0)
		 {
			//$this->redirect('door/doorInfo/'.$result);
			$content=View::Factory('door/select', array(
			'list' => $result,
			
			));
		 $this->template->content = $content;
		 
		 } else {
		 $content=View::Factory('door/search');
		 $this->template->content = $content;
		 }
	 }
	
	
	
public function action_doorInfo ($id_door=false)
{
    $id_door = $this->request->param('id');
    $_SESSION['menu_active']='door';
    if ($id_door == NULL) $this->redirect('door/find');
    $door_data=Model::Factory('Door')->getDoor($id_door);
    $door_load_order=Model::Factory('Door')->getDoorLoadorder($id_door);
    $door_delete_order=Model::Factory('Door')->getDoorDeleteOrder($id_door);
    $door_events=Model::Factory('Event')->event_door($id_door);
    $key_for_door=Model::Factory('Door')->getKeysForDoor($id_door);
    $card_type=Model::Factory('Door')->getCardType();
    $enable_card_type=Model::Factory('Door')->getEnableCardType(Arr::get($door_data, 'ID_DEVTYPE'));
    $access_categories = Model::Factory('Door')->getAccessCategories($id_door); // ДОБАВИТЬ

    $content=View::Factory('door/view', array(
        'door'	=> $door_data,
        'people_add'	=> $door_load_order,
        'people_del'	=> $door_delete_order,
        'events'	=> $door_events,
        'keys'=>$key_for_door,
        'card_type'=>$card_type,
        'enable_card_type'=>$enable_card_type,
        'access_categories' => $access_categories, // ДОБАВИТЬ
    ));
        
    $this->template->content = $content;
}
	

}
