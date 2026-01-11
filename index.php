<?php
session_start();

require_once 'config/config.php';
require_once 'config/database.php';
require_once 'core/Router.php';
require_once 'core/Controller.php';
require_once 'core/Model.php';
require_once 'core/Session.php';
require_once 'core/Language.php';

Language::init();

$router = new Router();
$router->dispatch();

