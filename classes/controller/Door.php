<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Door extends Controller_Template 
{ 
    public function before()
    {
        parent::before();
        $session = Session::instance();
    }
    
    public function action_index()
    {
        $_SESSION['menu_active'] = 'door';
        $content = View::factory('door/search');
        $this->template->content = $content;
    }
     
public function action_find()
{
    $_SESSION['menu_active'] = 'door';
    
    // Получаем параметры поиска (только doorInfo)
    $search = Arr::get($_GET, 'doorInfo', '');
    $showAll = (bool)Arr::get($_GET, 'showAll', 0);
    
    // Выполняем поиск
    $model = Model::Factory('Door');
    
    if ($showAll) {
        $result = $model->findIdDoor('', true);
    } else {
        $result = $model->findIdDoor($search);
    }
    
    // Проверяем, AJAX ли запрос
    if ($this->request->is_ajax()) {
        $this->auto_render = false;
        header('Content-Type: application/json');
        
        $data = array();
        foreach ($result as $item) {
            $data[] = array(
                'ID_DEV' => $item['ID_DEV'],
                'NAME' => $item['NAME'],
                'DEVICE_NAME' => isset($item['DEVICE_NAME']) ? $item['DEVICE_NAME'] : '',
                'SERVER_NAME' => isset($item['SERVER_NAME']) ? $item['SERVER_NAME'] : '',
                'DATE' => isset($item['DATE']) ? $item['DATE'] : ''
            );
        }
        
        echo json_encode(array(
            'success' => true,
            'data' => $data
        ));
        return;
    }
    
    // Обычный запрос - показываем страницу с результатами
    $content = View::Factory('door/search', array(
        'results' => $result,
        'searchTerm' => $search
    ));
    
    $this->template->content = $content;
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
	
/**
 * AJAX: получить события для точки прохода за период
 */
public function action_getEvents()
{
    $this->auto_render = false;
    header('Content-Type: application/json');
    
    $id_door = (int)$this->request->param('id', 0);
    $timeFrom = $this->request->query('timeFrom');
    $timeTo = $this->request->query('timeTo');
    
    if ($id_door <= 0) {
        echo json_encode(array('success' => false, 'error' => 'Invalid door ID: ' . $id_door));
        return;
    }
    
    // Получаем события за период
    $events = Model::Factory('Event')->event_door($id_door, $timeFrom, $timeTo);
    
    $data = array();
    foreach ($events as $event) {
        $data[] = array(
            'DATETIME' => date("d.m.Y H:i:s", strtotime(Arr::get($event, 'DATETIME'))),
            'ID_CARD' => Arr::get($event, 'ID_CARD'),
            'ID_PEP' => Arr::get($event, 'ID_PEP'),
            'NOTE' => Arr::get($event, 'NOTE'),
            'NAME' => Arr::get($event, 'NAME'),
            'DEV_NAME' => Arr::get($event, 'DEV_NAME'),
            'ID_EVENTTYPE' => Arr::get($event, 'ID_EVENTTYPE')
        );
    }
    
    echo json_encode(array(
        'success' => true,
        'data' => $data
    ));
}
}
