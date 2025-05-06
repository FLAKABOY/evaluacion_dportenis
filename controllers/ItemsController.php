<?php

require_once 'Controller.php';
require_once '../models/ItemsModel.php';
require_once '../utils/Utilerias.php';

class ItemsController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = ItemsModel::getInstance();
    }

    // Función para mostrar la vista de index
    public function index()
    {
        //Obtener los items padre de la base de datos
        $data['list']['items'] = $this->model->executeBaseProcedure('sp_get_menu_items',['get_menu_parents',''])[0]['items'];
        //Convertir el json a array
        $data['list']['items'] = Utilerias::jsonToArray($data['list']['items']);
        
        $this->view('add_item', $data); 
    }
}

// Verifica si se indica una función a ejecutar
if (isset($_GET['function'])) {
    $controller = new ItemsController();
    $function = $_GET['function'];

    if (method_exists($controller, $function)) {
        $controller->$function();
    } else {
        echo "La función '$function' no existe en ItemsController.";
    }
}