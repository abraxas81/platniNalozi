<?php require_once('../../includes/initialize.php');

if (!$session->is_logged_in()) {
    redirect_to("login.php");
}

$predlozak = new Predlozak();
if (isset($_POST['submit'])) {
    $predlozak->naziv = $_POST['naziv'];
    $predlozak->platitelj_id = $_POST['platitelj_id'];
    $predlozak->model_odobrenja_id = $_POST['model_odobrenja_id'];
    $predlozak->broj_odobrenja = $_POST['broj_odobrenja'];
    //$predlozak->primatelj_id = $_POST['iban'];
    $predlozak->iban_primatelja = $_POST['iban_prefix'].$_POST['iban'];
    $predlozak->model_zaduzenja_id = $_POST['model_zaduzenja_id'];
    $predlozak->broj_zaduzenja = $_POST['broj_zaduzenja'];
    $predlozak->korisnik_id = $_SESSION['user_id'];

    if ($predlozak->save()) {
        $session->message = ("Predložak je uspješno dodan !!!");
        $placanje = new Placanje();
        $placanje->predlozak_id = $_SESSION['last_inserted_id'];
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
        if ($placanje->save()) {
            $session->message = ("Placanje je uspješno dodano !!!");
            redirect_to('create.php');
        } else {
            //Neuspješno
            $message = join("<div class=alert alert-error>$placanje->errors</div><br>");
        }
    } else {
        //Neuspješno
        $message = join("<div class=alert alert-error>$predlozak->errors</div><br>");
    }
} else {
    $predlozak = Predlozak::find_by_id(0);
    $placanje = new Placanje();
}

include_layout_template('admin_header.php');

include_layout_template('sidebar.php'); ?>

<div class="span12">
    <form name="postForm" id="predložakForm" method="POST">
        <div class="form-horizontal">
            <table>
                <tr>
                    <th>Unos novog naloga</th>
                </tr>
                <tbody>
                <?php include('formC.php'); ?>
                <tr><td><input type="submit" name="submit" id="dodaj" value="Dodaj predložak"></td><td></td></tr>
                </tbody>
        </div>
        </table>
    </form>
</div>
<script>
    $(document).ready(function () {
        $('#predložakForm').submit(function (event) {
            var iban = $('#iban_prefix').val() + $('#iban').val();
            console.log(iban)
            if (IBAN.isValid(iban)) {
                return true;
            } else {
                event.preventDefault(); //Prevent the default submit
                $("#errmsg").html("Ispravite").show();
            }
        });
    });
</script>





