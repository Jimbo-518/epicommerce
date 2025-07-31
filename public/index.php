<?php
require_once '../config/config.php';
require_once '../app/core/App.php';
require_once '../app/core/Controller.php';
require_once '../app/core/Session.php';

Session::start();

$app = new App();
?>