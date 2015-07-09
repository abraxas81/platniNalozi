<?php require_once('../../includes/initialize.php');

if (!$session->is_logged_in()) {
    redirect_to("login.php");
}

$racun = new Racun();
if (isset($_POST['submit'])) {
    $racun->id = $_POST['id'];
    $racun->naziv = $_POST['naziv'];
    $racun->iban = $_POST['iban'];
    /*$racun->created_at = date('YMd hms');
    $racun->updated_at = date('YMd hms');*/
    $racun->korisnik_id = $_SESSION['user_id'];
    if ($racun->save()) {
        $session->message = ("Zadatak je uspješno promjenjen !!!");
        redirect_to('create.php');
    } else {
        //Neuspješno
        $message = join("<div class=alert alert-error>$racun->errors</div><br>");
    }
}

if (empty($_GET['id'])) {
    $session->message("Nije zaprimljen id računa");
    redirect_to('index.php');
}
$racun = Racun::find_by_id($_GET['id']);

include_layout_template('admin_header.php');

include_layout_template('sidebar.php');

?>
<div class="span12">
        <div class="form-horizontal">
            <?php include('formE.php'); ?>
        </div>
</div>