<?php defined('SYSPATH') or die('No direct script access.');
defined('DOOR_VERSION') OR define('DOOR_VERSION', '2.0.0');

Kohana::$config->load('menu')
    ->set('door', array(
        'title' => 'Точка прохода',
        'url' => 'door/doorinfo',
        'icon' => 'fa-cog',
        'order' => 40,
       
    ));
	
	
	// AJAX маршрут для получения событий
Route::set('door_getEvents', 'door/getEvents/<id>', array('id' => '\d+'))
    ->defaults(array(
        'controller' => 'Door',
        'action' => 'getEvents',
    ));