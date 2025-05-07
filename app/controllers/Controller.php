<?php
namespace App\Controllers;

use App\Models\Model;
class Controller
{
    private $model;

    public function __construct()
    {
        $this->model = Model::getInstance();
    }

    public function view($view, $data = [])
    {
        //Definir la ruta de la carpeta views sin importar la carpeta actual
        // Definir la ruta de la carpeta views sin importar la carpeta actual
        $baseDir = dirname(__FILE__, 2); // Obtener el directorio base (dos niveles arriba)
        $viewsDir = $baseDir . '/views'; // Ruta de la carpeta views
        // Verificar si la carpeta views existe
        if (!is_dir($viewsDir)) {
            die("La carpeta views no existe en la ruta: " . $viewsDir);
        }
        // Verificar si la carpeta views es accesible
        if (!is_readable($viewsDir)) {
            die("La carpeta views no es accesible: " . $viewsDir);
        }

        //Buscar en la raiz de la carpeta views
        // Verificar si la vista existe
        if (file_exists("$viewsDir/$view.php")) {
            // Extraer datos como variables
            extract($data);

            //imprimir la ruta de la vista
            

            // Incluir la vista
            require_once "$viewsDir/$view.php";
        } else {
            die("Vista no encontrada: " . $view);
        }
    }

    public function component($component, $data = [])
    {
        // Definir la ruta de la carpeta components sin importar la carpeta actual
        $baseDir = dirname(__FILE__, 2); // Obtener el directorio base (dos niveles arriba)
        $componentsDir = $baseDir . '/views/components'; // Ruta de la carpeta components
        // Verificar si la carpeta components existe
        if (!is_dir($componentsDir)) {
            die("La carpeta components no existe en la ruta: " . $componentsDir);
        }
        // Verificar si la carpeta components es accesible
        if (!is_readable($componentsDir)) {
            die("La carpeta components no es accesible: " . $componentsDir);
        }

        // Verificar si el componente existe
        if (file_exists("$componentsDir/$component.php")) {
            // Extraer datos como variables
            extract($data);

            // Incluir el componente
            require_once "$componentsDir/$component.php";
        } else {
            die("Componente no encontrado: " . $component);
        }
    }
}
