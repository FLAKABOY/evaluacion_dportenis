<?php
require_once 'autoload.php'; // Cargar la configuración
//Rediigir al IndexController
use App\controllers\IndexController;
use App\Models\IndexModel;

$controller = new IndexController();
$controller->index();
