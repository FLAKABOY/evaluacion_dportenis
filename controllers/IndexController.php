<?php

require_once 'Controller.php';
require_once 'models/IndexModel.php';
require_once 'utils/Utilerias.php';

class IndexController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = IndexModel::getInstance();
    }

    public function index()
    {
        //Ejecutar el procedimiento almacenado y obtener el menu y submenu
        $menu = $this->model->executeBaseProcedure('get_menu_json')[0]['menu_json'];

        //Convertir el json a array
        $menu = Utilerias::jsonToArray($menu);

        $data['menu'] = $menu;  
        $data['list']['items'] = $this->model->executeBaseProcedure('sp_get_menu_items',['get_all',''])[0]['items'];
        //convertir el json a array
        $data['list']['items'] = Utilerias::jsonToArray($data['list']['items']);
        
        $this->view('home', $data);
    }

    public function getData()
    {
        $data = $this->model->executeBaseProcedure('sp_get_data');
        echo json_encode($data);
    }
}