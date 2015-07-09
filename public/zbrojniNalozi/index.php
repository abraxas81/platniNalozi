<?php require_once('../../includes/initialize.php');

if (!$session->is_logged_in()) {
    redirect_to("login.php");
}

include_layout_template('admin_header.php');
include_layout_template('sidebar.php');
$zbrojniNalozi = ZbrojniNalog::grouped();
?>
<div class="span12">
    <?php echo output_message($message); ?>
    <table class="table">
        <thead>
        <tr>
        <th>Naziv datoteke <a id="novaDatoteka" href="create.php">  +Nova datoteka</a></th>
        <th>Broj naloga unutar datoteke</th>
        <th>Ukupni iznos</th>
        <th></th>
        <th></th>
        <th></th>
        </tr>
        </thead>
        <tbody>
        <?php
            if ($zbrojniNalozi){
            foreach ($zbrojniNalozi as $zbrojniNalog) {
                echo "<tr id=$zbrojniNalog->id><td><a href=show.php?id={$zbrojniNalog->id}>$zbrojniNalog->naziv</a></td>";
                echo "<td>$zbrojniNalog->brojnaloga</td>";
                echo "<td>$zbrojniNalog->iznos</td>";

            }
        }
        ?>
        </tbody>
    </table>
</div>

<script>
    var $rows = $('.table tbody#pretrazi tr');
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


