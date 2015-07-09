<?php
require_once('../../includes/initialize.php');

if (!$session->is_logged_in()) {
    redirect_to("login.php");
}

include_layout_template('admin_header.php');

include_layout_template('sidebar.php');

$zadaci = ZadatakKorisnik::find_by_id($_GET['id']);

?>
<div class="span12">
    <!--<div class="alert alert-error">
        <?php echo output_message($message); ?>
    </div>-->
    <table class="table">
        <?php
        $i = 0;
        if($zadaci){
        foreach ($zadaci as $zadatak) {
        if (!$i) { ?>
        <thead>
        <tr>
            <th><?php echo $zadatak->naziv ?></th>
        </tr>
        <tr>
            <th>Osoba/e na zadatku</th>
            <th>Kreiran</th>
            <th>Zadnja promjena</th>
            <th>Rok za završetak</th>
            <th>Riješen</th>
            <th>Akcije</th>
        </tr>
        </thead>
        <tbody>
        <?php }
        echo "<tr id=$zadatak->id><td>$zadatak->ime $zadatak->prezime</td>";
        echo "<td>$zadatak->created_at</td>";
        echo "<td>$zadatak->updated_at</td>";
        echo "<td>$zadatak->expire_at</td>";
        echo "<td><input type='checkbox' value='$zadatak->expire_at' disabled></td>";
        echo "<td><a href=edit.php?id={$zadatak->id}>E</a>";
        echo "<a id='delete' href=delete={$zadatak->id}>D</a></td></tr>";
        $komentari = ZadatakKorisnik::komentari($zadatak->id);
        if ($komentari) {
            echo "<tr><td>Komentari</td></tr>";
            foreach ($komentari as $komentar) {
                echo "<tr><td>$komentar->tekst</td><td>$komentar->ime $komentar->prezime</td></tr>";
            }
        }
        $i++;
        }
        }
        ?>
        </tbody>
    </table>
</div>

<script>
    var $rows = $('.table tbody tr');
    $('#search').keyup(function () {
        var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

        $rows.show().filter(function () {
            var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
            return !~text.indexOf(val);
        }).hide();
    });

    $('a#delete').click(function (e) {
        e.preventDefault();
        var parent = $(this).parent().parent();
        $.ajax({
            type: 'get',
            url: 'ajax.php',
            data: 'ajax=1&delete=' + parent.attr('id').replace(),
            beforeSend: function () {
                parent.animate({'backgroundColor': '#fb6c6c'}, 300);
            },
            success: function () {
                parent.slideUp(300, function () {
                    parent.remove();
                });
            }
        });
    });
</script>