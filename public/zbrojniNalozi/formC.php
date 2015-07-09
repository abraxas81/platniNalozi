<input type="hidden" name="id">
<label>Naziv predloska : </label>
<input type="text" name="naziv" id="naziv" required">
<label>IBAN platitelja : </label>
<select name="platitelj_id" required>
    <?php

    $racuni_platitelja = Racun::korisnik($_SESSION['user_id']); ?>

    <?php foreach ($racuni_platitelja as $racun_platitelja) {
        ?>
        <option value=<?php echo $racun_platitelja->id;?>><?php echo $racun_platitelja->iban.''. $racun_platitelja->naziv?></option>
    <?php } ?>
</select>
<label>Model - Poziv na broj platitelja : </label>
<select name="model_odobrenja_id" required>
    <?php
    $modeli = ModelPlacanja::all();?>
    <?php foreach ($modeli as $model) {
        ?>
        <option value=<?php echo $model->id;?>><?php echo $model->naziv?></option>
    <?php } ?>
</select>
<input type="text" name="broj_odobrenja" class="pozivNaBroj" required">
<label>IBAN primatelja : </label>
<select name="primatelj_id" required>
    <?php
    $racuni_primatelja = Racun::all(); ?>
    <?php foreach ($racuni_primatelja as $racun_primatelja) {
        ?>
        <option value=<?php echo $racun_primatelja->id;?>><?php echo $racun_primatelja->iban.''. $racun_primatelja->naziv?></option>
    <?php } ?>
</select>

<label>Model - Poziv na broj primatelja : </label>
<select name="model_zaduzenja_id" required>
    <?php foreach ($modeli as $model) {
        ?>
        <option value=<?php echo $model->id;?>><?php echo $model->naziv?></option>
    <?php } ?>
</select>
<input type="text" name="broj_zaduzenja" class="pozivNaBroj" required">
<label>Iznos : </label>
<input type="text" name="iznos" id="iznos" required>
<select name="valuta" required>
    <?php
        $valute = Valuta::all();
        foreach ($valute as $valuta) {
        ?>
        <option value=<?php echo $valuta->id;?>><?php echo $valuta->alfa?></option>
    <?php } ?>
</select>
<label>Šifra namjene : </label>
<select name="sifra_namjene_id" required>
    <?php
    $sifre_namjene = SifraNamjene::all();
    foreach ($sifre_namjene as $sifra_namjene) {
        ?>
        <option value=<?php echo $sifra_namjene->id;?>><?php echo $sifra_namjene->sifra.' '.$sifra_namjene->naziv?></option>
    <?php } ?>
</select>
<label>Opis pla?anja : </label>
<textarea rows="4" cols="50" name="opis_placanja" required></textarea>
<label>Datum izvršenja : </label>
<input type="text" name="datum_izvrsenja" class="datepicker" required>
<br><br>

<script>
    $(document).ready(function () {

        $("#pozivNaBroj").keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                //display error message
                $("#errmsg").html("Samo znamenke dozvoljene").show().fadeOut("slow");
                return false;
            }
        });

        $
    });
    $('.datepicker').datetimepicker({
        lang: 'hr',
        format: 'd.m.Y'
    })
</script>




