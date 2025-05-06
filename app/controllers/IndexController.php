<?php
namespace App\controllers;
use App\Controllers\Controller;
use App\Models\IndexModel;
use App\Utils\Utilerias;

class IndexController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = new IndexModel();
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