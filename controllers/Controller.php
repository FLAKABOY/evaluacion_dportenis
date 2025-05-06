<?php


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
}
