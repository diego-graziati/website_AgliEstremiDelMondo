<?php
session_start();

require_once 'sources/scripts/php/constants.php';
require_once 'sources/scripts/php/translator.php';
require_once 'sources/scripts/php/routing.php';

$selected_lang = 'it';
$state = 'IT';

$lang_map = load_localization($selected_lang, $state);
$router = new Router();
?>