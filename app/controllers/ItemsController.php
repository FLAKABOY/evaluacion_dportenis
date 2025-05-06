<?php

namespace App\Controllers;

require_once '../../autoload.php';

use App\Models\ItemsModel;
use App\Utils\Utilerias;

class ItemsController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = new ItemsModel();
    }

    // Función para mostrar la vista de index
    public function index()
    {
        //Obtener los items padre de la base de datos
        $data['list']['items'] = $this->model->executeBaseProcedure('sp_get_menu_items', ['get_menu_parents', ''])[0]['items'];
        //Convertir el json a array
        $data['list']['items'] = Utilerias::jsonToArray($data['list']['items']);
        $data['action'] = 'http://localhost' . $_SERVER['PHP_SELF'] . '?controller=items&function=saveItem';
        $data['text'] = [
            'title' => 'Agregar Item',
            'button' => 'Agregar Item',
        ];
        $this->view('add_item', $data);
    }

    public function edit()
    {
        //Obtener el id del item a editar
        $id = $_GET['id'];
        //Obtener los items padre de la base de datos
        $data['list']['items'] = $this->model->executeBaseProcedure('sp_get_menu_items', ['get_menu_parents', ''])[0]['items'];
        //Convertir el json a array
        $data['list']['items'] = Utilerias::jsonToArray($data['list']['items']);

        $data['text'] = [
            'title' => 'Editar Item',
            'button' => 'Actualizar Item',
        ];

        //Armar los filtros para la consulta
        $filters = Utilerias::arrayToJson(
            [
            'id' => $id,
            ]
        );
        

        //Obtener el registro a editar
        $data['item'] = $this->model->executeBaseProcedure('sp_get_menu_items', ['get_item_by_id', $filters])[0]['item'];
        //Convertir el json a array
        $data['item'] = Utilerias::jsonToArray($data['item']);

        //accion del formulario
        $data['action'] = 'http://localhost' . $_SERVER['PHP_SELF'] . '?controller=items&function=update&id=' . $id;

        //Reutilizar la vista de agregar item para editar
        $this->view('add_item', $data);
    }

    //Funcion para guardar el item
    public function saveItem()
    {
        header('Content-Type: application/json'); // Establece el tipo de contenido a JSON

        $menu_name = $_POST['menu_name']; // Nombre del menú
        $parent_menu = $_POST['parent_menu']; // ID del menú padre

        //Colocamos los valores en el modelo ya que si un tipo de dato no es correcto se lanza una excepción
        $this->model->setName($menu_name);
        $this->model->setIdParent($parent_menu);

        if (empty($menu_name)) {
            echo json_encode(['status' => 'error', 'message' => 'El nombre del menú es obligatorio.']);
            exit;
        }

        //Consumir el procedimiento almacenado para guardar el item y obtener la respuesta de error
        $error = $this->model->executeBaseProcedure('sp_save_item', ['create', $this->model->getIdMenu() ,$this->model->getIdParent(), $this->model->getName()])[0]['ERROR'];

        if (!$error) {
            echo json_encode(['status' => 'success', 'message' => 'El menú se ha guardado correctamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al guardar el menú.']);
        }

        exit;
    }

    //Funcion para actualizar el item
    public function update()
    {
        header('Content-Type: application/json'); // Establece el tipo de contenido a JSON

        $menu_name = $_POST['menu_name']; // Nombre del menú
        $parent_menu = $_POST['parent_menu']; // ID del menú padre
        $id = $_GET['id']; // ID del menú a editar


        //Colocamos los valores en el modelo ya que si un tipo de dato no es correcto se lanza una excepción
        $this->model->setName($menu_name);
        $this->model->setIdParent($parent_menu);
        $this->model->setIdMenu($id);

        if (empty($menu_name)) {
            echo json_encode(['status' => 'error', 'message' => 'El nombre del menú es obligatorio.']);
            exit;
        }

        //Consumir el procedimiento almacenado para guardar el item y obtener la respuesta de error
        $error = $this->model->executeBaseProcedure('sp_save_item', ['update', $this->model->getIdMenu() ,$this->model->getIdParent(), $this->model->getName()])[0]['ERROR'];

        if (!$error) {
            echo json_encode(['status' => 'success', 'message' => 'El menú se ha actualizado correctamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el menú.']);
        }

        exit;
    }

    public function delete()
    {
        header('Content-Type: application/json'); // Establece el tipo de contenido a JSON

        $id = $_GET['id']; // ID del menú a eliminar


        //Consumir el procedimiento almacenado para eliminar el item y obtener la respuesta de error
        $error = $this->model->executeBaseProcedure('sp_save_item', ['delete', $id,'',''])[0]['ERROR'];

        if (!$error) {
            echo json_encode(['status' => 'success', 'message' => 'El menú se ha eliminado correctamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al eliminar el menú.']);
        }

        exit;
    }

}


// Verifica si se indica una función a ejecutar
if (isset($_GET['function'])) {
    $controller = new ItemsController();
    $function = $_GET['function'];

    if (method_exists($controller, $function)) {
        $controller->$function();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Función no válida.']);
    }
}
