<?php
ob_start();
require '../vendor/autoload.php';  
require '../src/models/dev.php';  
require '../src/handlers/exceptions.php';

$config = include('../src/config.php');

$app = new \Slim\App(['settings'=> $config]);

$container = $app->getContainer();

$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$capsule->getContainer()->singleton(
  Illuminate\Contracts\Debug\ExceptionHandler::class,
  App\Exceptions\Handler::class
);

/****************************Start Service*********************************************************************/
///$app->get('/users/','getUsers');
$app->post('/users','getUsers');
/*************************************************************************************************/

/**************************All Method starts***********************************************************************/
function getUsers($request, $response) {
  echo "Hello";
  
  $data = $request->getParsedBody();
  
 // $row = Dev::table('devs')->where('focus', '=', $data['focus'])->first();

  $user = Dev::where('focus', '=', $data['focus'])->first(); //This is for if user already rxists
    if(count($user) > 0)
    {
    echo "There is data";
    }
    else
    echo "No data";

  //$dev = new Dev();
  //$dev->name = $data['name'];
  //$dev->focus = $data['focus'];
  //$dev->hireDate = $data['hireDate'];

  //$dev->save();

  //return $response->withStatus(201)->getBody()->write($dev->toJson());
}


/*************************************************************************************************/



$app->get('/dev/', function($request, $response) {
  return $response->getBody()->write(Dev::all()->toJson());
});

$app->get('/dev/{id}/', function($request, $response, $args) {
  $id = $args['id'];
  $dev = Dev::find($id);
  $response->getBody()->write($dev);
  return $response;
});

$app->post('/dev/', function($request, $response, $args) {
  $data = $request->getParsedBody();
  $dev = new Dev();
  $dev->name = $data['name'];
  $dev->focus = $data['focus'];
  //$dev->hireDate = $data['hireDate'];

  $dev->save();

  return $response->withStatus(201)->getBody()->write($dev->toJson());
});

$app->delete('/dev/{id}/', function($request, $response, $args) {
  $id = $args['id'];
  $dev = Dev::find($id);
  $dev->delete();

  return $response->withStatus(200);
});

$app->put('/dev/{id}/', function($request, $response, $args) {
  $id = $args['id'];
  $data = $request->getParsedBody();
  $dev = Dev::find($id);
  $dev->name = $data['name'] ?: $dev->name;
  $dev->focus = $data['focus'] ?: $dev->focus;
  $dev->hireDate = $data['hireDate'] ?: $dev->hireDate;

  $dev->save();

  return $response->getBody()->write($dev->toJson());
});

$app->run();
?>
