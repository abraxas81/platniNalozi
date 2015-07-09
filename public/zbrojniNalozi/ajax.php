<?php
require_once('../../includes/initialize.php');

$zbrojniNalog = new ZbrojniNalog();

if (isset($_POST['Edit'])) {

    $errors = array(); //To store errors
    $form_data = array(); //Pass back the data to `formE.php`
    $nalozi = array();
    //
    $placanje = new Placanje();
    //$placanje = Placanje::find_by_id($_POST['id']);
    $placanje->id = $_POST['id'];
    $placanje->predlozak_nalog = 1;
    $placanje->predlozak_id = $_POST['prid'];
    $placanje->iznos = $_POST['iznos'];
    $placanje->sifra_namjene_id = $_POST['sifra_namjene_id'];
    $placanje->opis = $_POST['opis_placanja'];
    $placanje->datum_izvrsenja = $_POST['datum_izvrsenja'];
    $placanje->sifra_valute_id = $_POST['valuta'];
    $placanje->zbrojni_nalog_id = $_POST['znid'];
    $placanje->save();
    $form_data['success'] = true;
    $form_data['novo'] = false;
    $form_data['nalozi'] =  ZbrojniNalog::nalog($_POST['id']);
    $form_data['posted'] = 'Podaci su uspjesno upisani u bazu';

//Return the data back to formE.php
    echo json_encode($form_data);
}

if (isset($_POST['New'])) {

    $errors = array(); //To store errors
    $form_data = array(); //Pass back the data to `formE.php`
    $nalozi = array();
    $new = array();
    //
    $placanje = new Placanje();
    $placanje->predlozak_nalog = 1;
    $placanje->predlozak_id = $_POST['id'];
    $placanje->iznos = $_POST['iznos'];
    $placanje->sifra_namjene_id = $_POST['sifra_namjene_id'];
    $placanje->opis = $_POST['opis_placanja'];
    $placanje->datum_izvrsenja = $_POST['datum_izvrsenja'];
    $placanje->sifra_valute_id = $_POST['valuta'];
    $placanje->zbrojni_nalog_id = $_POST['znid'];
    $placanje->save();
    $form_data['success'] = true;
    $form_data['novo'] = true;
    $form_data['nalozi'] =  ZbrojniNalog::nalog($_SESSION['last_inserted_id']);
    $form_data['posted'] = 'Podaci su uspjesno upisani u bazu';

//Return the data back to formE.php
    echo json_encode($form_data);
}

if(isset($_GET['delete'])) {
    Placanje::delete($_GET['delete']);
}
