<input type="hidden" name="id">
<tr>
    <td align="right"><label>Naziv predloška :</label></td>
    <td><input type="text" name="naziv" id="naziv" required class="input-xlarge"></td>
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
                <option
                    value=<?php echo $racun_platitelja->id; ?>><?php echo $racun_platitelja->iban . '' . $racun_platitelja->naziv ?></option>
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
        </select><input type="text" name="broj_odobrenja" class="pozivNaBroj" pattern=".{19,22}"maxlength="22" required">&nbsp;<span id="errmsg1"></span></td>
    </td>
</tr><label>
    <tr class="active">
        <td>Primatelj</td>
    </tr>
    <tr>
        <td align="right"><label>Iban : </label></td>
        <td><input type="text" name="iban_prefix" id="iban_prefix" value="HR" class="input-micro">
            <input type="text"name="iban"id="iban" pattern=".{19,22}"maxlength="22" required class="input-medium">&nbsp;<span id="errmsg"></span></td>
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
            </select><input type="text" name="broj_zaduzenja" class="pozivNaBroj" maxlength="22" required">&nbsp;<span id="errmsg"></span></td>
        </td>
    </tr>
    <tr colspan="2">
        <td>Plaćanje</td>
    </tr>
    <tr>
        <td align="right"><label>Iznos : </label></td>
        <td><input type="text" name="iznos" id="iznos" class="input-small" required>
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
                    <option
                        value=<?php echo $sifra_namjene->id; ?>><?php echo $sifra_namjene->sifra . ' ' . $sifra_namjene->naziv ?></option>
                <?php } ?>
            </select></td>
    </tr>
    <tr>
        <td align="right"><label>Opis plačanja : </label></td>
        <td><textarea rows="4" cols="60" name="opis_placanja" required></textarea></td>
    </tr>
    <tr>
        <td align="right"><label>Datum izvršenja : </label></td>
        <td><input type="text" name="datum_izvrsenja" class="datepicker input-small" required></td>
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

            $(".pozivNaBroj").keydown(function (event) {
                // Prevent shift key since its not needed
                if (event.shiftKey == true) {
                    event.preventDefault();
                }
                // Allow Only: keyboard 0-9, numpad 0-9, backspace, tab, left arrow, right arrow, delete
                if ((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105) || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46 || event.keyCode == 45) {
                    // Allow normal operation
                } else {
                    // Prevent the rest
                    event.preventDefault();
                    $(this).html("Samo znamenke dozvoljene").show();
                }
            });
        })
    </script>


