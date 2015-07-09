<?php require_once('../../includes/initialize.php');

if (!$session->is_logged_in()) {
    redirect_to("login.php");
}
$predlozak = new Predlozak();
$placanje = new Placanje();
if (isset($_POST['submit'])) {
    $predlozak = Predlozak::find_by_id2($_POST['id']);
    $predlozak->id = $_POST['id'];
    $predlozak->naziv = $_POST['naziv'];
    $predlozak->platitelj_id = $_POST['platitelj_id'];
    $predlozak->model_odobrenja_id = $_POST['model_odobrenja_id'];
    $predlozak->broj_odobrenja = $_POST['broj_odobrenja'];
    $predlozak->primatelj_id = $_POST['primatelj_id'];
    $predlozak->model_zaduzenja_id = $_POST['model_zaduzenja_id'];
    $predlozak->broj_zaduzenja = $_POST['broj_zaduzenja'];
    //$predlozak->korisnik_id = $_SESSION['user_id'];

    if ($predlozak->save()) {
        $session->message = ("Predložak je uspješno dodan !!!");
        $placanje = Placanje::find_by_id2($predlozak->id);
        $placanje->iznos = $_POST['iznos'];
        $placanje->sifra_valute_id = $_POST['valuta'];
        $placanje->sifra_namjene_id = $_POST['sifra_namjene_id'];
        $placanje->opis = $_POST['opis_placanja'];
        $placanje->datum_izvrsenja = $_POST['datum_izvrsenja'];
        // 0 je predložak 1 je nalog
        $placanje->predlozak_nalog = 0;
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
}

if (empty($_GET['id'])) {
    $session->message("Nije zaprimljen id predloška");
    redirect_to('index.php');
}
$predlozak = Predlozak::find_by_id($_GET['id']);

include_layout_template('admin_header.php');

include_layout_template('sidebar.php');

?>
<div class="span12">
    <form method="POST">
        <div class="form-horizontal">
            <?php include('formE.php'); ?>
            <input type="submit" name="submit" value="Uredi predložak">
        </div>
    </form>
</div>
