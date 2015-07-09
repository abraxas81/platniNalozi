<?php
require_once('../../includes/initialize.php');

$zadatak = new Zadaci();

if (isset($_POST['submit'])) {

$errors = array(); //To store errors
$form_data = array(); //Pass back the data to `formE.php`

/* Validate the form on the server side */
if (empty($_POST['naziv'])) { //Name cannot be empty
$errors['name'] = 'Polje naziv ne smije biti prazno';
}
    if (empty($_POST['tekst'])) { //Name cannot be empty
        $errors['name'] = 'Polje tekst ne smije biti prazno';
    }

if (!empty($errors)) { //If errors in validation
$form_data['success'] = false;
$form_data['errors']  = $errors;
}
else { //If not, process the form, and return true on success
$form_data['success'] = true;
$zadatak->naziv = $_POST['naziv'];
$zadatak->tekst = $_POST['tekst'];
$zadatak->prioritet_id = $_POST['prioritet_id'];
$zadatak->created_at = date('YMd hms');
$zadatak->updated_at = date('YMd hms');

$zadatak->expire_at = date("YMd hms", strtotime($_POST['expire_at']));
$zadatak->created_by = $_SESSION['user_id'];

$zadatak->save();

$form_data['posted'] = 'Podaci su uspješno upisani u bazu';
}

//Return the data back to formE.php
echo json_encode($form_data);

}
if (isset($_POST['member'])) {

    $errors = array(); //To store errors
    $form_data = array(); //Pass back the data to `formE.php`

    for ($i = 0; $i < count($_POST['member']['k_id']); $i++) {
        $zadatakKorisnik = new ZadatakKorisnik();
        $zadatakKorisnik->korisnik_id = $_POST['member']['k_id'][$i];
        $zadatakKorisnik->zadatak_id = $_SESSION['last_inserted_id'];
        $zadatakKorisnik->created_at = date('YMd hms');
        $zadatakKorisnik->expire_at = date('YMd hms', strtotime($_POST['member']['expire_at'][$i]));
        $zadatakKorisnik->prioritet_id = $_POST['member']['prioritet_id'][$i];
        $zadatakKorisnik->save();

        $komentar = new Komentar();
        $komentar->korisnici_id = $_SESSION['user_id'];
        $komentar->zadatak_id = $_SESSION['last_inserted_id_zk'];
        $komentar->tekst = $_POST['member']['tekst'][$i];
        $komentar->created_at = date('YMd hms');
        $komentar->save();

        $form_data['posted'] = 'Podaci su uspješno upisani u bazu';
    }

//Return the data back to formE.php
        echo json_encode($form_data);
}
if(isset($_GET['delete'])) {
    ZadatakKorisnik::delete($_GET['delete']);
}
