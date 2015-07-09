<?php

require_once('../../includes/initialize.php');

if (!$session->is_logged_in()) { redirect_to("login.php"); }
?>

<?php include_layout_template('admin_header.php');

/*  $user = new User();
    $user->ime = "Novi";
    $user->prezime = "Novi";
    $user->username = "1234";
    $user->password = "pass";
    $user->save();*/

    $user = User::find_by_id(6);
    //$user->password = "123";
    $user->delete();
    echo $user->ime.' je obrisan';

include_layout_template('admin_footer.php'); ?>