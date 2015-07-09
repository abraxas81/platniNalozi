<?php require_once('../../includes/initialize.php');

if (!$session->is_logged_in()) {
    redirect_to("login.php");
}

include_layout_template('admin_header.php');
include_layout_template('sidebar.php');
$zadaci = ZadatakKorisnik::groupByZadatakId();
?>
<div class="span12">
    <?php echo output_message($message); ?>
    <table class="table">
        <thead>
        <tr>
        <th>Naziv Zadatka</th>
        <th>Broj zadataka</th>
        <th>Datum Kreiranja</th>
        <th>Rok za završetak</th>
        <th>Akcije</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($zadaci as $zadatak) {
            echo "<tr><td><a href=show.php?id={$zadatak->id}>$zadatak->naziv</a></td>";
            echo "<td>$zadatak->brojzadataka</td>";
            echo "<td>$zadatak->created_at</td>";
            echo "<td>$zadatak->expire_at</td>";
            echo "<td><a href=edit.php?id={$zadatak->id}>E</a>";
            echo "<a href=delete.php?id={$zadatak->id}>D</a></td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<script>
    var $rows = $('.table tbody tr');
    $('#search').keyup(function() {
        var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

        $rows.show().filter(function() {
            var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
            return !~text.indexOf(val);
        }).hide();
    });
</script>


