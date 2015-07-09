<input type="hidden" name="id" value="<?= $zadatak->id ?>">
<p>Naziv zadatka</p>
<input type="text" name="naziv" value="<?= $zadatak->naziv ?>">
<span class="throw_error"></span>
<p>Tekst zadatka</p>
<input type="textarea" name="tekst" value="<?php echo $zadatak->tekst ?>">
<p>Datum kad zadatak istièe:</p>
<input type="datetime" name="expire_at" value="<?php echo $zadatak->expire_at ?>">
<p>Prioritet zadatka</p>
<select name="prioritet_id">
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



