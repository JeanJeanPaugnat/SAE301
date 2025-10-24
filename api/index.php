<?php
// ⚠️ RIEN AVANT cette ligne

session_set_cookie_params(
    86400,              // lifetime
    '/~paugnat7/',      // path
    '',                 // domain vide
    true,               // secure
    true                // httponly
);

ini_set('session.cookie_samesite', 'None');

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ⭐ Headers CORS corrigés
// header("Access-Control-Allow-Origin: https://mmi.unilim.fr");
// header("Access-Control-Allow-Credentials: true");
// header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
// header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once "src/Controller/ProductController.php";
require_once "src/Controller/CategoryController.php";
require_once "src/Controller/UserController.php";
require_once "src/Controller/OrderController.php";
require_once "src/Class/HttpRequest.php";

$router = [
    "products" => new ProductController(),
    "categories" => new CategoryController(),
    "users" => new UserController(),
    "orders" => new OrderController()
];

$request = new HttpRequest();

if ($request->getMethod() == "OPTIONS"){
    http_response_code(200);
    exit();
}

$route = $request->getRessources();

if ( isset($router[$route]) ){
    $ctrl = $router[$route];
    $json = $ctrl->jsonResponse($request);
    if ($json){ 
        header("Content-type: application/json;charset=utf-8");
        echo $json;
    }
    else{
        http_response_code(404);
    }
    die();
}
http_response_code(404);
die();
?>