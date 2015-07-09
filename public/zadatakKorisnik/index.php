<?php require_once('../../includes/initialize.php');

if (!$session->is_logged_in()) {
    redirect_to("login.php");
}

include_layout_template('admin_header.php');
include_layout_template('sidebar.php');
$zadaci = ZadatakKorisnik::all();
?>
<div class="span12">
    <?php echo output_message($message); ?>
    <table class="table">
        <th>Naziv Zadatka</th>
        <th>Datum kreiranja</th>
        <th>Datum zadnje promjene</th>
        <th>Datum kad ističe</th>
        <th>Osobe na zadatku</th>
        <?php foreach ($zadaci as $zadatak) {
            echo "<tr><td><a href='show.php?id=$zadatak->id'>$zadatak->naziv</a></td>";
            echo "<td>$zadatak->created_at</td>";
            echo "<td>$zadatak->updated_at</td>";
            echo "<td>$zadatak->expire_at</td>";
            echo "<td>$zadatak->ime $zadatak->prezime</td>";
            echo "<td>$zadatak->expire_at</td>";
        }
        ?>
    </table>
</div>


