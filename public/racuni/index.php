<?php require_once('../../includes/initialize.php');

if (!$session->is_logged_in()) {
    redirect_to("login.php");
}

include_layout_template('admin_header.php');
include_layout_template('sidebar.php');
$racuni = Racun::all();
?>
<div class="span12">
    <?php echo output_message($message); ?>
    <table class="table">
        <thead>
        <tr>
        <th>Naziv Racuna  <a href="../racuni/create.php">+Novi</a></th>
        <th>Iban</th>
        <th>Korisnik</th>
        <th>Akcije</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($racuni as $racun) {
            echo "<tr id=$racun->id><td><a href=show.php?id={$racun->id}>$racun->naziv</a></td>";
            echo "<td>$racun->iban</td>";
            echo "<td>$racun->korisnik_id</td>";
            echo "<td><a href=edit.php?id={$racun->id}>E</a>";
            echo "<a id='delete' href=delete.php?id={$racun->id}>D</a></td></tr>";
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

    $('a#delete').click(function (e) {
        if (!confirm('Are you sure you wish to delete this?')) {
            e.preventDefault();
        } else {
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
                calculateSum();
                parent.slideUp(300, function () {
                    parent.remove();
                });
            }
        });
        }
    });
</script>


