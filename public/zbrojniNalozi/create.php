<?php require_once('../../includes/initialize.php');

if (!$session->is_logged_in()) {
redirect_to("login.php");
}

$zbrojniNalog = new ZbrojniNalog();
if (isset($_POST['submit'])) {
    //$placanje = Placanje::find_by_id2($_POST['id']);

    $zbrojniNalog->naziv = $_POST['naziv'];
    //$datoteka = $_POST['datumValute'];
/*    $placanje = new Placanje();
    $placanje->predlozak_id = $_POST['id'];
    $placanje->iznos = $_POST['iznos'];
    $placanje->sifra_valute_id = $_POST['valuta'];
    $placanje->sifra_namjene_id = $_POST['sifra_namjene_id'];
    $placanje->opis = $_POST['opis_placanja'];
    $placanje->datum_izvrsenja = $_POST['datum_izvrsenja'];
    // 0 je predložak 1 je nalog
    $placanje->predlozak_nalog = 1;
    //$predlozak->potpisivanje = $_POST['potpisivanje'];
    /*$predlozak->created_at = date('YMd hms');
    $predlozak->updated_at = date('YMd hms');*/
    if ($zbrojniNalog->save()) {
    $session->message = ("Placanje je uspješno dodano !!!");
    redirect_to('show.php?id='.$_SESSION['last_inserted_id_zn']);
    } else {
    //Neuspješno
    $message = join("
    <div class=alert alert-error>$datoteka->errors</div><br>");
    }

}

/*if (empty($_GET['id'])) {
$session->message("Nije zaprimljen id predloška");
redirect_to('index.php');
}*/

//$predlozak = Predlozak::find_by_id($_GET['id']);
include_layout_template('admin_header.php');
include_layout_template('sidebar.php');
/*include('../predlosci/index.php');*/
?>
<div class="span12">
    <div id="success"></div>
    <form id="zbrojniNalog" action="create.php" method="POST">
    <table class="table" id="zbrojniNalog">
        <thead>
        <tr>
            <th>Naziv datoteke</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
            <tr>
                <td><label>Naziv datoteke</label><input type="text" name="naziv" value="<?php echo date('Ymd')?>"></td>
                <td><label>Datum valute</label><input type="text" name="datumValute"></td>
                <td></td>
                <td></td>
                <td></td>
                <?php //include('formE.php'); ?>
            </tr>
        </thead>

        <tbody id="dodaj">

        </tbody>
    </table>
        <input type="submit" name="submit" id="dodajNalogButton"  value="Dodaj datoteku">
    </form>
   <!-- <div id="nalozi" hidden>
        <form id="naloziForm" name="naloziForm" method="POST">
            <table id="dodaj">
                <th>Iznos</th>
                <th>Šifra namjene</th>
                <th>Opis</th>
                <th>Datum izvršenja</th>
                <th>Akcije</th>
            </table>
            <input type="submit" value="Dodaj">
        </form>-->
</div>
</div>
<script>
$(document).ready(function () {
   /* $('table#predlosci').hide();*/

   /* $('form#zbrojniNalog').submit(function (event) {
        event.preventDefault(); //Prevent the default submit
        $.ajax({
            type: 'POST',
            url: 'ajax.php',
            data: $(this).serialize(),
            success: function (data) {
                $('#success').fadeIn(1000).append('<p>' + data.posted + '</p>'); //If successful, than throw a success message
                $('#dodajNalogButton').hide();
                $('table#predlosci').show();
                $('#nalozi').show();
            }
        });
    });*/

    $('form#naloziForm').submit(function (event) {
        event.preventDefault(); //Prevent the default submit
        $.ajax({
            type: 'POST',
            url: 'ajax.php',
            data: $(this).serialize(),
            success: function (data) {
                $('#success').fadeIn(1000).append('<p>' + data.posted + '</p>'); //If successful, than throw a success message
                /*$('#zaduziKorisnika').hide();*/
            }
        });
    });

/*    $("a#dodaj").click(function() {
        var pokusaj = [];
        var $row = $(this).closest("tr");    // Find the row
        var $tds = $row.find("td");
        $.each($tds, function() {
            var redak = $(this).text();
            pokusaj.push(redak)
        });
        console.log(pokusaj);
        $('table#dodaj').append('<tr><input type="hidden" name="payment[pid][]" value='+pokusaj[0]+'><td><input type="text" name="payment[iznos][]" value='+pokusaj[4]+'></td><td><input type="text" name="payment[sifra_namjene][]" required value='+pokusaj[5]+'></td><td><input type="text" name="payment[opis][]" value='+pokusaj[6]+'></td><td><input type="text" name="payment[datum][]" required class="datepicker"></td><td><a id="ukloni">Remove</a></td></tr>');
    });*/

    $('body').on('click', 'a#ukloni', function() {
        $(this).closest("tr").remove();
    });

    $('body').on('focus',".datepicker", function(){
        $(this).datetimepicker({
            lang: 'hr',
            format: 'd.m.Y'
        });
    });

});
</script>