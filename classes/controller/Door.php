<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Door extends Controller_Template 
{ 
    public function before()
    {
        parent::before();
        $session = Session::instance();
        //echo Debug::vars('9controller', $_POST, $_GET);
    }
    
    public function action_index()
    {
        $_SESSION['menu_active'] = 'door';
        $content = View::factory('door/search');
        $this->template->content = $content;
    }
     
    public function action_find()
    {
        $search = Arr::get($_GET, 'doorInfo');
        $_SESSION['doorEventsTimeFrom'] = Arr::get($_GET, 'timeFrom');
        $_SESSION['doorEventsTimeTo'] = Arr::get($_GET, 'timeTo');
        
        $result = Model::Factory('Door')->findIdDoor($search);
        
        if (count($result) > 0) {
            $content = View::Factory('door/select', array(
                'list' => $result,
            ));
            $this->template->content = $content;
        } else {
            $content = View::Factory('door/search');
            $this->template->content = $content;
        }
    }
    
 public function action_doorInfo($id_door = false)
{
    $id_door = $this->request->param('id');
    $_SESSION['menu_active'] = 'door';
    
    if ($id_door == NULL) {
        $this->redirect('door/find');
    }
    
    // Получаем все данные
    $door_data = Model::Factory('Door')->getDoor($id_door);
    
    $data = array(
        'door' => $door_data,
        'people_add' => Model::Factory('Door')->getDoorLoadorder($id_door),
        'people_del' => Model::Factory('Door')->getDoorDeleteOrder($id_door),
        'events' => Model::Factory('Event')->event_door($id_door),
        'keys' => Model::Factory('Door')->getKeysForDoor($id_door),
        'card_type' => Model::Factory('Door')->getCardType(),
        'enable_card_type' => Model::Factory('Door')->getEnableCardType(Arr::get($door_data, 'ID_DEVTYPE')),
        'access_categories' => Model::Factory('Door')->getAccessCategories($id_door),
        'device_groups' => Model::Factory('Door')->getDeviceGroupsWithHierarchy($id_door), 
    );
    
    $content = View::Factory('door/view', $data);
    $this->template->content = $content;
}
	
	
}
