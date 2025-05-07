<?php

namespace App\controllers;

require_once '../../autoload.php';
use App\Models\IndexModel;
use App\Utils\Utilerias;

class DefaultController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = new IndexModel();
    }

    public function index()
    {
        $name = isset($_GET['name']) ? $_GET['name'] : 'default';
        if ($name == 'default') {
            $this->component('default_view', ['text' => 'Bienvenido a la vista por defecto']);
        } else {
            $this->component('default_view', ['text' => "Bienvenido a la vista de $name"]);
        }

    }

    public function getData()
    {
        $data = $this->model->executeBaseProcedure('sp_get_data');
        echo json_encode($data);
    }
}

// Verifica si se indica una función a ejecutar
if (isset($_GET['function'])) {
    $controller = new DefaultController();
    $function = $_GET['function'];

    if (method_exists($controller, $function)) {
        $controller->$function();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Función no válida.']);
    }
}
