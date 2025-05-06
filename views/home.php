<!-- <?php

        echo "<pre>";
        print_r($data);
        echo "</pre>";

        ?> -->

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú</title>
    <link rel="stylesheet" href="css/home.css">
</head>

<body>

    <!-- Menú lateral -->
    <div class="menu">
        <h2>Menú</h2>
        <ul>
            <?php foreach ($data['menu'] as $item): ?>
                <li>
                    <span><?= $item['nombre'] ?></span>
                    <?php if (!empty($item['submenus'])): ?>
                        <ul class="submenu">
                            <?php foreach ($item['submenus'] as $submenu): ?>
                                <li>
                                    <a href="?controller=<?= strtolower($submenu['nombre']) ?>&function=index">
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
        <h2>Lista de Ítems del Menú</h2>
        <!-- Botón Agregar -->
        <button id="btn_add" class="btn-add">Agregar un item</button>
        <table>
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
                <?php foreach ($data['list']['items'] as $item): ?>
                    <?php
                    // Buscar el nombre del padre según el id_parent
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
                            <button class="btn-edit" onclick="editItem(<?= $item['id_menu'] ?>)">Editar</button>
                            <button class="btn-delete" onclick="return deleteItem(<?= $item['id_menu'] ?>)">Eliminar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('btn_add').addEventListener('click', function() {
            fetch('controllers/ItemsController.php?function=index')
                .then(response => response.text())
                .then(html => {
                    document.querySelector('.content').innerHTML = html;
                })
                .catch(error => {
                    console.error('Error al cargar la vista desde el controlador:', error);
                });
        });
    });
</script>

</html>