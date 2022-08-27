<?php
include_once 'request.php';
include_once 'router.php';
include_once 'api/index.php';

$router = new Router(new Request);
$api = new Api();

$router->get('/employee', function ($request) use ($api) {
    return $api->getEmployee($request);
});


$router->delete('/employee', function ($request) use ($api) {
    return $api->deleteEmployee();
});

$router->post('/employee', function ($request) use ($api) {
    return $api->createEmployee();
});

$router->put('/employee', function ($request) use ($api) {
    return $api->updateEmployee();
});
