<?php
// error_reporting(0);
/* echo "<pre>";
print_r($data);
echo "</pre>"; */

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú</title>
    <link rel="stylesheet" href="app/css/home.css">
    <link rel="stylesheet" href="app/css/pagination.css">

</head>

<body>

    <!-- Menú lateral -->
    <div class="menu">
        <h2><a href="#" onclick="returnIndex()">Menú</a></h2>
        <hr>
        <ul>
            <?php foreach ($data['menu'] as $item): ?>
                <li>
                    <span><?= $item['nombre'] ?></span>
                    <?php if (!empty($item['submenus'])): ?>
                        <ul class="submenu">
                            <?php foreach ($item['submenus'] as $submenu): ?>
                                <li>
                                    <a href="#" class="submenu-link" onclick="defaultController('<?= $submenu['nombre'] ?>')">
                                        <?= $submenu['nombre'] ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>


    <!-- Contenido principal -->
    <div class="content">


        <?php
        // Configurar paginación
        $itemsPerPage = 2; // Número de ítems por página
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Página actual
        $totalItems = count($data['list']['items']); // Total de ítems
        $totalPages = ceil($totalItems / $itemsPerPage); // Total de páginas
        $offset = ($currentPage - 1) * $itemsPerPage; // Desplazamiento para la paginación
        $itemsPaginated = array_slice($data['list']['items'], $offset, $itemsPerPage); // Ítems para la página actual
        ?>

        <div class="tabla-container">
            <table>

                    <h2  class="tabla-titulo" >Lista de Ítems del Menú</h2>
                <!-- Botón Agregar -->
                <div class="add-btn-container">
                    <button id="btn_add" class="btn-add">
                        <img class="image-icon" src="app/public/img/mas.png" alt="">  Agregar un item
                    </button>
                </div>

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Status</th>
                        <th>Padre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($itemsPaginated as $item): ?>
                        <?php
                        $parentName = '';
                        foreach ($data['list']['items'] as $possibleParent) {
                            if ($possibleParent['id_menu'] == $item['id_parent']) {
                                $parentName = $possibleParent['name'];
                                break;
                            }
                        }
                        ?>
                        <tr>
                            <td><?= $item['id_menu'] ?></td>
                            <td><?= $item['name'] ?></td>
                            <td><?= $item['description'] ?></td>
                            <td><?= $item['status'] ?></td>
                            <td><?= $parentName ?></td>
                            <td class="actions">
                                <button class="btn-edit" onclick="editItem(<?= $item['id_menu'] ?>)">
                                    <img class="image-icon" src="app/public/img/editar.png" alt="">
                                </button>
                                <button class="btn-delete" onclick="return deleteItem(<?= $item['id_menu'] ?>)">
                                    <img class="image-icon" src="app/public/img/eliminar.png" alt="">
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="pagination-container">
            <ul class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </div>
    </div>


</body>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('btn_add').addEventListener('click', function() {
            fetch('app/controllers/ItemsController.php?function=index')
                .then(response => response.text())
                .then(html => {
                    document.querySelector('.content').innerHTML = html;
                })
                .catch(error => {
                    console.error('Error al cargar la vista desde el controlador:', error);
                });
        });
    });

    function addItem() {
        // Validar el campo nombre del menú
        var menuName = document.getElementById('menu_name').value;
        if (menuName.trim() === '') {
            alert('El nombre del menú es obligatorio.');
            return;
        }

        // Imprimir en consola para verificar
        console.log('Nombre del menú:', menuName);

        // Obtener el formulario y enviarlo vía AJAX
        var form = document.querySelector('form');
        var formData = new FormData(form);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', form.action, true);

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            alert(response.message);
                            window.location.href = 'http://localhost/evaluacion';
                        } else {
                            alert(response.message);
                        }
                    } catch (e) {
                        console.error('Respuesta inválida del servidor:', xhr.responseText);
                        alert('Error al procesar la respuesta del servidor.');
                    }
                } else {
                    console.error('Error en la petición AJAX:', xhr.status);
                    alert('Error en la conexión con el servidor.');
                }
            }
        };

        xhr.send(formData);
    }

    function editItem(id) {
        // Redirigir a la página de edición del ítem

        fetch('app/controllers/ItemsController.php?function=edit&id=' + id)
            .then(response => response.text())
            .then(html => {
                document.querySelector('.content').innerHTML = html;
            })
            .catch(error => {
                console.error('Error al cargar la vista desde el controlador:', error);
            });

    }

    function deleteItem(id) {
        if (confirm('¿Estás seguro de que deseas eliminar este ítem?')) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'app/controllers/ItemsController.php?function=delete&id=' + id, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.status === 'success') {
                                alert(response.message);
                                window.location.href = 'http://localhost/evaluacion';
                            } else {
                                alert(response.message);
                            }
                        } catch (e) {
                            console.error('Respuesta inválida del servidor:', xhr.responseText);
                            alert('Error al procesar la respuesta del servidor.');
                        }
                    } else {
                        console.error('Error en la petición AJAX:', xhr.status);
                        alert('Error en la conexión con el servidor.');
                    }
                }
            };
            xhr.send();
        }
        return false; // Evitar el comportamiento por defecto del botón
    }

    function defaultController(name) {
        event.preventDefault(); // Evita que el enlace se siga

        // Quitar estado activo previo
        document.querySelectorAll('.submenu-link').forEach(link => {
            link.classList.remove('active');
            const marker = link.querySelector('.marker');
            if (marker) marker.remove();
        });

        // Marcar el nuevo enlace como activo
        const clicked = event.currentTarget;
        clicked.classList.add('active');

        // Agregar el ✔ si no está ya
        const marker = document.createElement('span');
        marker.classList.add('marker');
        marker.textContent = '✔';
        clicked.appendChild(marker);

        // Redirigir a defaultController
        fetch('app/controllers/DefaultController.php?function=index&name=' + name)
            .then(response => response.text())
            .then(html => {
                document.querySelector('.content').innerHTML = html;
            })
            .catch(error => {
                console.error('Error al cargar la vista desde el controlador:', error);
            });
    }

    function returnIndex() {
        // Redirigir a la página de inicio
        window.location.href = 'http://localhost/evaluacion';
    }
</script>

</html>