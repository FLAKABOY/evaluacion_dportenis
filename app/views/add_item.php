<?php
/* echo "<pre>";
print_r($data);
echo "</pre>"; */
?>
<div class="card">
    <button id="btn_back" class="btn-back" onclick="returnIndex()">
        <img src="app/public/img/return.png" alt=""> Regresar
    </button>
    <h2><?= $data['text']['title'] ?></h2>

    <form action="<?= $data['action'] ?>" method="post">
        <div class="form-group">
            <label for="menu_name">Nombre del Menú</label>
            <input type="text" id="menu_name" name="menu_name" value="<?= isset($data['item']['name']) ? $data['item']['name'] : '' ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Descripcion</label>
            <textarea name="description" id="txt_description"><?= isset($data['item']['description']) ? $data['item']['description'] : '' ?></textarea>
        </div>

        <div class="form-group">
            <label for="parent_menu">Menú Padre</label>
            <select id="parent_menu" name="parent_menu">
                <option value="0">-</option>

                <?php $selectedItem = $data['item']['parent']['id'] ?>

                <?php foreach ($data['list']['items'] as $item): ?>
                    <option value="<?= $item['id_menu'] ?>" <?= ($selectedItem == $item['id_menu']) ? 'selected' : '' ?>><?= $item['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button class="card-button" id="btn_save" type="button" onclick="addItem()"><?= $data['text']['button'] ?></button>
    </form>
</div>