<input type="hidden" name="id" value="<?= $zadatak->id ?>">
<label>Naziv : </label>
<input type="text" name="naziv" id="naziv" required value="<?= $zadatak->naziv ?>">
<span class="throw_error"></span>
<label>Tekst : </label>
<input type="textarea" name="tekst" required value="<?php echo $zadatak->tekst ?>">
<label>Rok izvršenja : </label>
<input type="datetime" id="expire_at" name="expire_at" class=datetimepicker value="<?php echo $zadatak->expire_at ?>">
<p>Prioritet zadatka</p>
<select name="prioritet_id" required>
    <option value="">Odaberi Prioritet</option>
    </option>
    <?php
    $prioriteti = Prioritet::all(); ?>
    <?php foreach ($prioriteti as $prioritet) {
        ?>
        <option value=<?php echo $prioritet->id;
        if ($prioritet->id == $zadatak->prioritet_id) echo " selected=\"selected\""; ?>><?php echo $prioritet->naziv ?></option>
    <?php } ?>
</select>


