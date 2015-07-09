<?php
require_once('../../includes/initialize.php');

if (!$session->is_logged_in()) {
    redirect_to("login.php");
}

include_layout_template('admin_header.php');

include_layout_template('sidebar.php');

$zadatak = ZadatakKorisnik::find_by_id($_GET['id']);

?>
<div class="span12">
    <table class="table">
        <th>Naziv Zadatka</th>
        <th>Datum kreiranja</th>
        <th>Datum zadnje promjene</th>
        <th>Datum kad istièe</th>
        <th>Osobe na zadatku</th>
        <?php
        echo "<tr><td>$zadatak->naziv</td>";
        echo "<td>$zadatak->created_at</td>";
        echo "<td>$zadatak->updated_at</td>";
        echo "<td>$zadatak->expire_at</td>";
        echo "<td>$zadatak->ime $zadatak->prezime</td>";
        echo "<td>$zadatak->expire_at</td>";
        ?>
    </table>
</div>

