<input type="hidden" name="id" value="<?php echo $racun->id?>">
<tr><td><label>Naziv : </label></td>
<td><input type="text" name="naziv" id="naziv" required value="<?php echo trim($racun->naziv)?>"><span class="throw_error"></span></td></tr>
<tr><td><label>Iban : </label></td>
<td><input type="text" name="iban_prefix" id="iban_prefix" value="<?php if($racun->iban) echo substr($racun->iban,0,2); else echo "HR" ?>" class="input-micro"><input type="text" name="iban" id="iban" pattern=".{19,19}" maxlength="19" required value="<?php echo substr($racun->iban,2)?>">&nbsp;<span id="errmsg"></span></td></tr>

<script>
    $(document).ready(function () {

        $("#iban").keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            var iban = $('#iban_prefix').val()+$(this).val();
            console.log(iban);
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                //display error message
                $("#errmsg").html("Samo znamenke dozvoljene").show().fadeOut("slow");
                return false;
            }
        })
        .blur(function(){
            var iban = $('#iban_prefix').val()+$(this).val();
                if(IBAN.isValid(iban)){
                    $("#errmsg").html("OK").show().fadeOut(10000);
                } else {$("#errmsg").html("Ispravite").show();}

            console.log(iban);
        });

    });
</script>




