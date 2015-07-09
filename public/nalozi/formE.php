<input type="hidden" name="id" value="<?php echo $_GET['id']?>">
<tr>
    <td align="right"><label>Naziv predloška :</label></td>
    <td><input type="text" name="naziv" id="naziv" required class="input-xlarge" value="<?php echo trim($predlozak->naziv);?>"></td>
</tr>
<tr colspan="2">
    <td>Platitelj</td>
</tr>
<tr>
    <td align="right"><label>IBAN platitelja :</label></td>
    <td><select name="platitelj_id" required class="input-ibanplatitelja">
            <?php $racuni_platitelja = Racun::korisnik($_SESSION['user_id']); ?>
            <?php foreach ($racuni_platitelja as $racun_platitelja) {
                ?>
                <option value=<?php echo $racun_platitelja->id;
                if ($racun_platitelja->id == $predlozak->platitelj_id) echo " selected=\"selected\""; ?>><?php echo $racun_platitelja->iban.''. $racun_platitelja->naziv?></option>
            <?php } ?>
        </select></td>
</tr>
<tr>
    <td>Model - Poziv na broj platitelja :</label></td>
    <td>HR <select name="model_odobrenja_id" class="input-mini" required>
            <?php
            $modeli = ModelPlacanja::all(); ?>
            <?php foreach ($modeli as $model) {
                ?>
                <option value=<?php echo $model->id; ?>><?php echo $model->naziv ?></option>
            <?php } ?>
        </select><input type="text" name="broj_odobrenja" class="pozivNaBroj" required value="<?php echo $predlozak->broj_odobrenja?>">
    </td>
</tr> <tr>
        <td>Primatelj</td>
    </tr>
    <tr>
    <tr><td><label>Iban : </label></td>
        <td><input type="text" name="iban_prefix" id="iban_prefix" value="<?php if($predlozak->iban_primatelja) echo substr($predlozak->iban_primatelja,0,2); else echo "HR" ?>" class="input-micro"><input type="text" name="iban" id="iban" pattern=".{19,19}" maxlength="19" required value="<?php echo substr($predlozak->iban_primatelja,2)?>">&nbsp;<span id="errmsg"></span></td></tr>
    </tr>
    <!--<select name="primatelj_id" required>
    <?php
    /*    $racuni_primatelja = Racun::all(); */
    ?>
    <?php /*foreach ($racuni_primatelja as $racun_primatelja) {
        */
    ?>
        <option value=<?php /*echo $racun_primatelja->id;*/ ?>><?php /*echo $racun_primatelja->iban.''. $racun_primatelja->naziv*/ ?></option>
    <?php /*} */ ?>
</select>-->
    <tr>
        <td align="right"><label>Model - Poziv na broj primatelja : </label></td>
        <td>HR <select name="model_zaduzenja_id" class="input-mini" required>
                <?php foreach ($modeli as $model) {
                    ?>
                    <option value=<?php echo $model->id; ?>><?php echo $model->naziv ?></option>
                <?php } ?>
            </select><input type="text" name="broj_zaduzenja" class="pozivNaBroj" required" value="<?php echo $predlozak->broj_zaduzenja?>">
        </td>
    </tr>
    <tr colspan="2">
        <td>Plaćanje</td>
    </tr>
    <tr>
        <td align="right"><label>Iznos : </label></td>
        <td><input type="text" name="iznos" id="iznos" class="input-small" required value="<?= $predlozak->iznos ?>">
            <select name="valuta" class="input-small" required>
                <?php
                $valute = Valuta::all();
                foreach ($valute as $valuta) {
                    ?>
                    <option value=<?php echo $valuta->id; ?>><?php echo $valuta->alfa ?></option>
                <?php } ?>
            </select></td>
    </tr>
    <tr>
        <td align="right"><label>Šifra namjene : </label></td>
        <td><select name="sifra_namjene_id" required class="input-ibanplatitelja">
                <option value="0"></option>
                <?php
                $sifre_namjene = SifraNamjene::all();
                foreach ($sifre_namjene as $sifra_namjene) {
                    ?>
                    <option value=<?php echo $sifra_namjene->id;
                    if ($sifra_namjene->id == $predlozak->sifra_namjene_id) echo " selected=\"selected\"";?>><?php echo $sifra_namjene->sifra.' '.$sifra_namjene->naziv?></option>
                <?php } ?>
            </select></td>
    </tr>
    <tr>
        <td align="right"><label>Opis plačanja : </label></td>
        <td><textarea rows="4" cols="60" name="opis_placanja" required><?php echo $predlozak->opis?></textarea></td>
    </tr>
    <tr>
        <td align="right"><label>Datum izvršenja : </label></td>
        <td><input type="text" name="datum_izvrsenja" class="datepicker input-small" required value="<?= $predlozak->datum_izvrsenja?>"></td>
    </tr>
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

            $('.datepicker').datetimepicker({
                lang: 'hr',
                format: 'd.m.Y',
                timepicker: false,
                inline: false,
                minDate: '0',//yesterday is minimum date(for today use 0 or -1970/01/01),
                closeOnDateSelect: true,
                dayOfWeekStart: 1
            });

            $("#iban").keypress(function (e) {
                //if the letter is not digit then display error and don't type anything
                var iban = $('#iban_prefix').val() + $(this).val();
                console.log(iban);
                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    //display error message
                    $("#errmsg").html("Samo znamenke dozvoljene").show().fadeOut("slow");
                    return false;
                }
            })
                .blur(function () {
                    var iban = $('#iban_prefix').val() + $(this).val();
                    if (IBAN.isValid(iban)) {
                        $("#errmsg").html("OK").show().fadeOut(10000);
                    } else {
                        $("#errmsg").html("Ispravite").show();
                    }

                    console.log(iban);
                })
        })
    </script>

