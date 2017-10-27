<?php

$router = new Router();

$router->get('/api/Table', 'TestController::getTable');
$router->post('/api/SessionSubscribe', 'TestController::sessionSubscribe');

echo $router->handle();