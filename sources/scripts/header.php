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
error_log("[".date("Y-M-D h:m:s")."] [Info] [header.php] Request URI: " . print_r($request_uri, true) ."\n", 3, PHP_LOGS_FILE_PATH);
$request_post_body = "";
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $body = file_get_contents("php://inputs");
    $request_post_body = json_decode($body, true);
    error_log("[".date("Y-M-D h:m:s")."] [Info] [header.php] POST request JSON body: " . print_r($request_post_body, true) ."\n", 3, PHP_LOGS_FILE_PATH);
}
$router->callRoute($routing_info, $request_post_body);
?>