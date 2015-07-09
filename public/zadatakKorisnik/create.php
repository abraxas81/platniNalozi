<?php
require_once('../../includes/initialize.php');

if (!$session->is_logged_in()) {
    redirect_to("login.php");
}

$zadatak = new Zadaci();

if (isset($_POST['submit'])) {
    $zadatak->naziv = $_POST['naziv'];
    $zadatak->tekst = $_POST['tekst'];
    $zadatak->prioritet_id = $_POST['prioritet_id'];
    $zadatak->created_at = date('YMd hms');
    $zadatak->updated_at = date('YMd hms');
    $zadatak->created_by = $_SESSION['user_id'];
    $korisnici = $_POST['korisnici'];
    if ($zadatak->save()) {
        $session->message = ("Zadatak je uspjesno dodan !!!");

        foreach ($korisnici as $value) {
            $zadatakKorisnik = new ZadatakKorisnik();
            $zadatakKorisnik->korisnik_id = $value;
            $zadatakKorisnik->zadatak_id = Zadaci::getInstance()->lastInsertId('zadaci_id_seq');;
            $zadatakKorisnik->created_at = $zadatak->created_at;
            $zadatakKorisnik->save();
        }
        redirect_to('index.php');
    } else {
        //Neuspje�no
        //$message = join("<div class="alert alert-error">$zadatak->errors</div>"<br>",);
    }
}

include_layout_template('admin_header.php');

include_layout_template('sidebar.php'); ?>

<div class="span12">
    <?php echo output_message($message); ?>
    <form action="../zadaci/create.php" method="POST">
        <div class="form-horizontal">
            <?php include('zadaci_form.php'); ?>
            <?php $users = User::find_all(); ?>
            <select multiple name="korisnici[]">
                <?php foreach ($users as $user) { ?>
                    <option value="<?php echo $user->id ?>"><?php echo $user->ime . ' ' . $user->prezime ?></option>
                <?php } ?>
            </select>
            <input type="submit" name="submit" value="Dodaj zadatak">
        </div>
    </form>
</div>
</div>



