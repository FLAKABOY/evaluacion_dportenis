<!-- <?php
echo "<pre>";
print_r($data);
echo "</pre>";
?> -->
<div class="card">
    <h2>Agregar Menú</h2>

    <form action="some_action.php" method="post">
        <div class="form-group">
            <label for="menu_name">Nombre del Menú</label>
            <input type="text" id="menu_name" name="menu_name" required>
        </div>

        <div class="form-group">
            <label for="parent_menu">Menú Padre</label>
            <select id="parent_menu" name="parent_menu">
                <option value="0">Seleccionar Menú Padre</option>
                <?php foreach ($data['list']['items'] as $item): ?>
                    <option value="<?= $item['id_menu'] ?>"><?= $item['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit">Agregar Menú</button>
    </form>
</div>