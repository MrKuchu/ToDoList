<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define( 'YOUR_FOLDER', '/' . basename( dirname(__FILE__), "index.php" ) . '/' );

require_once 'vendor/autoload.php';
require_once 'Task.php';

use Relay\Relay;

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'todo',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();

$request = Zend\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

$loader = new \Twig\Loader\FilesystemLoader('.');
$twig = new \Twig\Environment($loader, array(
    'debug' => true,
    'cache' => false,
));

$router = new Aura\Router\RouterContainer();
$map = $router->getMap();

$map->get('todo.list', YOUR_FOLDER, function ($request) use ($twig) {
    $tasks = Task::all();
    $response = new Zend\Diactoros\Response\HtmlResponse($twig->render('template.twig', [
        'tasks' => $tasks
    ]));
    return $response;
});

$map->post('todo.add', YOUR_FOLDER . 'add', function ($request) {
    $data = $request->getParsedBody();
    $tasks = new Task();
    $tasks->description = $data['description'];
    $tasks->save();
    $response = new Zend\Diactoros\Response\RedirectResponse( YOUR_FOLDER );
    return $response;
});

$map->get('todo.check', YOUR_FOLDER . 'check/{id}', function ($request) {
    $id = $request->getAttribute('id');
    $tasks = Task::find($id);
    $tasks->done = true;
    $tasks->save();
    $response = new Zend\Diactoros\Response\RedirectResponse( YOUR_FOLDER );
    return $response;
});

$map->get('todo.uncheck', YOUR_FOLDER . 'uncheck/{id}', function ($request) {
    $id = $request->getAttribute('id');
    $tasks = Task::find($id);
    $tasks->done = false;
    $tasks->save();
    $response = new Zend\Diactoros\Response\RedirectResponse( YOUR_FOLDER );
    return $response;
});

$map->get('todo.delete', YOUR_FOLDER . 'delete/{id}', function ($request) {
    $id = $request->getAttribute('id');
    $tasks = Task::find($id);
    $tasks->delete();
    $response = new Zend\Diactoros\Response\RedirectResponse( YOUR_FOLDER );
    return $response;
});

$relay = new Relay([
    new Middlewares\AuraRouter($router),
    new Middlewares\RequestHandler()
]);

$response = $relay->handle($request);

foreach ($response->getHeaders() as $name => $values) {
    foreach ($values as $value) {
        header(sprintf('%s: %s', $name, $value), false);
    }
}
echo $response->getBody();