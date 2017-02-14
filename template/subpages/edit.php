<?php
$data = $todo -> getOneRecord($_GET['id']);
?>
<section>
    <div id="edit_note">
        <div class="modal-header">
            <h2>Edytuj notatkę</h2>
        </div>
        <div class="modal-body">
            <label for="notetext_edit">Treść</label>
            <div class="input-prepend"><textarea id="notetext_edit" rows="6"><?php echo $data[0]; ?></textarea></div>
            <label for="timeout_edit">Data wygaśnięcia</label>
            <div class="input-prepend"><span class="add-on"><i class="icon-time"></i></span><input type="text" id="timeout_edit" value="<?php echo date('d/m/Y', $data[1]); ?>"></div>
            <span class="badge badge-info">W formacie: DD/MM/YYYY</span>
        </div>
        <div class="modal-footer">
            <button id="submit_edit" class="btn btn-primary" value="<?php echo $todo -> secure($_GET['id']); ?>">Uaktualnij</button>
        </div>
    </div>
</section>
