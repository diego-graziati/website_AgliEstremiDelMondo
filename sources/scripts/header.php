<?php
require_once 'sources/scripts/php/constants.php';
require_once 'sources/scripts/php/translator.php';

$selected_lang = 'it';
$state = 'IT';

$lang_map = load_localization($selected_lang, $state);
?>