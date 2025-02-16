<?php
//General constants
const CALLER_PAGE_INDEX = "index";
const CALLER_PAGE_CONTENT_DISPLAYER = "content_displayer";
const CALLER_PAGE_SINGLE_CONTENT_DISPLAYER = "single_content_displayer";

const SESSION_VAR_CALLER_PAGE = 'caller-page';
const SESSION_VAR_CONTENT_TYPE = 'content-type';
const SESSION_VAR_CONTENT_ID = 'content-id';
const SESSION_VAR_CONTENT_TITLE = 'content-title';

const POST_VAR_CALLER_PAGE = SESSION_VAR_CALLER_PAGE;
const POST_VAR_CONTENT_TYPE = SESSION_VAR_CONTENT_TYPE;
const POST_VAR_CONTENT_ID = SESSION_VAR_CONTENT_ID;
const POST_VAR_CONTENT_TITLE = SESSION_VAR_CONTENT_TITLE;

const MAX_ROUTING_DEPTH = 2;

//Path constants
define('ROOT_DIR_PATH', $_SERVER['DOCUMENT_ROOT']);
define('LANG_DIR_PATH', __DIR__ . '/../../lang/');
define('CONTENT_DIR_PATH', __DIR__ . '/../../content/');
define('ROUTES_DIR_PATH', __DIR__ . '/../../routes/');
define('LOGS_DIR_PATH', __DIR__.'/../../../logs/');
define('PHP_LOGS_FILE_PATH', LOGS_DIR_PATH .'php.log');

//Routing constants
define('PROTOCOL', (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://');
define('HOST', $_SERVER['HTTP_HOST']);

?>