<?php
namespace App;
// Registrar una función para el autoloading de clases
spl_autoload_register(function ($class) {
    // Especificar el prefijo del namespace que queremos cargar
    $prefix = 'App\\'; // El namespace de tus clases

    // Definir la ruta base de las clases en el sistema de archivos
    $base_dir = __DIR__ . '/app/'; // O ajusta la ruta según tu estructura de directorios

    // Obtener la longitud del prefijo
    $len = strlen($prefix);
    
    // Verificar si el namespace coincide con el prefijo
    if (strncmp($prefix, $class, $len) !== 0) {
        return; // La clase no pertenece a este namespace, ignorar
    }

    // Obtener la clase relativa sin el prefijo
    $relative_class = substr($class, $len);

    // Convertir los separadores de namespace (\\) en directorios (/) y agregar la extensión .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // Incluir el archivo si existe
    if (file_exists($file)) {
        require $file;
    }
});