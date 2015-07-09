<?php
require_once('../../includes/initialize.php');

if (!$session->is_logged_in()) {
    redirect_to("login.php");
}

$zadatak = new Zadaci();

if (isset($_POST['submit'])) {
    $zadatak = Zadaci::find_by_id((int)$_POST['id']);
    $zadatak->id = (int)$_POST['id'];
    $zadatak->naziv = $_POST['naziv'];
    $zadatak->tekst = $_POST['tekst'];
    $zadatak->prioritet_id = $_POST['prioritet_id'];
    $zadatak->save();
    $korisnici = $_POST['korisnici'];


    $users_With_task = ZadatakKorisnik::find_by_task($zadatak->id);
    $id_user_With_task = array();
    foreach ($users_With_task as $user_With_task) {
        $id_user_With_task[] = $user_With_task->korisnik_id;
    }

    foreach ($korisnici as $key => $value) {
        if (in_array($value, $id_user_With_task)) {
            $key = array_search($value, $id_user_With_task);
            if (false !== $key) {
                unset($id_user_With_task[$key]);
            }
            ZadatakKorisnik::update2($zadatak->id, $value, date('YMd hms'));

        } else {
            $zadatakKorisnik = new ZadatakKorisnik();
            $zadatakKorisnik->zadatak_id = $zadatak->id;
            $zadatakKorisnik->korisnik_id = $value;
            $zadatakKorisnik->created_at = date('YMd hms');

            $zadatakKorisnik->save();

        }

    }
    if (!empty($id_user_With_task)) {
        foreach ($id_user_With_task as $key => $value) {
            ZadatakKorisnik::delete($zadatak->id, $value);
            unset($id_user_With_task[$key]);
        }

    }
    redirect_to('index.php');

}


if (empty($_GET['id'])) {
    $session->message("Nije zaprimljen id zadatka");
    redirect_to('index.php');
}
$zadatak = Zadaci::find_by_id($_GET['id']);
include_layout_template('admin_header.php');

include_layout_template('sidebar.php');

?>
<div class="span12">
    <?php echo output_message($message); ?>
    <form action="edit.php" method="POST">
        <div class="form-horizontal">

            <?php include('zadaci_form.php'); ?>
            <?php
            $users = User::find_all();
            $users_With_task = ZadatakKorisnik::find_users_by_task($zadatak->id);
            if ($users_With_task) {
                $id_users_With_task = array();
                foreach ($users_With_task as $user_With_task) {
                    $id_user_With_task[] = $user_With_task->id;
                }
            }
            ?>
            <select multiple name="korisnici[]">
                <?php foreach ($users as $user) { ?>
                    <option value=<?php echo $user->id;
                    if ($users_With_task && (in_array($user->id, $id_user_With_task))) echo " selected=\"selected\""; ?>><?php echo $user->ime . ' ' . $user->prezime ?></option>
                <?php } ?>
            </select>

            <input type="submit" name="submit" value="Uredi zadatak">
        </div>
    </form>
</div>
</div>
