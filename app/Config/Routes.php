<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\ItemsController;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('items',[ItemsController::class, 'index']);
$routes->get('items/list',[ItemsController::class, 'list']);
$routes->post('items/checked',[ItemsController::class, 'checked']);
