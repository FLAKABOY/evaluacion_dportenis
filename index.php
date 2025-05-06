<?php
//Rediigir al IndexController
require_once 'controllers/IndexController.php';
require_once 'models/IndexModel.php'; // si aún no lo cargas

$controller = new IndexController();
$controller->index();