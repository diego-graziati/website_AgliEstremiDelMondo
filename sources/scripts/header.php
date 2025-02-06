<?php
session_start();

require_once 'sources/scripts/php/constants.php';
require_once 'sources/scripts/php/translator.php';
require_once 'sources/scripts/php/routing.php';

$selected_lang = 'it';
$state = 'IT';

$lang_map = load_localization($selected_lang, $state);
$router = new Router();

$request_uri = $_SERVER['REQUEST_URI'];
$request_path = parse_url($request_uri, PHP_URL_PATH);
$routing_info = $router->getCompleteRoutingInfo($request_path);
$request_post_body = "";
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $body = file_get_contents("php://inputs");
    $request_post_body = json_decode($body, true);
}
$router->callRoute($routing_info, $request_post_body);
?>