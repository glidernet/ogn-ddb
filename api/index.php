<?php
include '../config.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../mapper.php';

$app = new \Slim\App(["settings" => $config]);

$container = $app->getContainer();

$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'],
        $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
};

$container['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        return $c['response']->withStatus(500)
                             ->withHeader('Content-type', 'application/json')
                             ->write(json_encode(array('message' => 'Internal Server Error.')));
    };
};

$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['response']
            ->withStatus(404)
            ->withHeader('Content-type', 'application/json')
            ->write(json_encode(array('message' => 'Resource not found.')));
    };
};

$container['notAllowedHandler'] = function ($c) {
    return function ($request, $response, $methods) use ($c) {
        return $c['response']
            ->withStatus(405)
            ->withHeader('Content-type', 'application/json')
            ->write(json_encode(array('message' => 'Method not allowed for the requested URL.')));
    };
};

$app->add(function ($request, $response, $next) use ($container) {
	$response = $response->withHeader('Content-type', 'application/json');
    $response = $next($request, $response);

    if ($response->getStatusCode() == 404 && $response->getBody()->getSize() == 0)
    {
        $handler = $container['notFoundHandler'];
        return $handler($request, $response);
    }

	return $response;
});

$app->get('/devices', function (Request $request, Response $response) {
    $device_mapper = new DeviceMapper($this->db);
    $devices = $device_mapper->getDevices();
    foreach ($devices as $key => $device) {
        unset($devices[$key]['owner_id']);
    }

    if ($devices) {
        return $response
            ->withStatus(200)
            ->write(json_encode($devices));
    } else {
        return $response->withStatus(404);
    }
});

$app->get('/devices/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');

    $device_mapper = new DeviceMapper($this->db);
    $device = $device_mapper->getDeviceById($id);
    if ($device) {
        unset($device['owner_id']);
        return $response
            ->withStatus(200)
            ->write(json_encode($device));
    } else {
        return $response->withStatus(404);
    }
});

$app->post('/devices', function (Request $request, Response $response) {
    $request_data = json_decode($request->getBody(), true);
    $user_id = ;

    $device = array('id' => $data['id'],
                    'type' => $data['type'],
                    'aircraft_model_id' => $data['aircraft_model_id'],
                    'aircraft_registration' => $data['aircraft_registration'],
                    'aircraft_competition_id' => $data['aircraft_competition_id'],
                    'tracked' => $data['tracked'],
                    'identified' => $data['identified'],
                    'owner_id' => $user_id,
                    );

    $device_mapper = new DeviceMapper($this->db);
    $result = $device_mapper->save($device);
    if (!$result) {
        return $response->withStatus(404);
    }

    unset($device['owner_id']);
    return $response
        ->withStatus(200)
        ->write(json_encode($device));
});


$app->get('/test', function (Request $request, Response $response) {
    $device_mapper = new DeviceMapper($this->db);
    $device = array('id' => 'DEADBE',
                    'type' => 'FLARM',
                    'aircraft_model_id' => '209',
                    'aircraft_registration' => 'X-0123',
                    'aircraft_competition_id' => 'XL',
                    'tracked' => true,
                    'identified' => true,
                    'owner_id' => 1,
                 );
    $device_mapper->save($device);
    $counter = $device_mapper->getCounter();
    // $device_mapper->deleteDeviceById('DEADBE');
    print_r($device_mapper->getDeviceById('DDABBA'));
    print("<br/>");
    print_r($device_mapper->getDevices());
    print("<br/>");

    $aircraft_model_mapper = new AircraftModelMapper($this->db);
    print_r($aircraft_model_mapper->getAircraftModels());
    print("<br/>");

    $user_mapper = new UserMapper($this->db);
    print_r($user_mapper->getUserByCredentials('test@example.com', 'test', 'Gl'));
    print("<br/>");
    $response->getBody()->write("Counter: $counter <br/> Device:<br/>");

    return $response;
});
$app->run();
