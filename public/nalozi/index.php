<?php require_once('../../includes/initialize.php');

if (!$session->is_logged_in()) {
    redirect_to("login.php");
}

include_layout_template('admin_header.php');
include_layout_template('sidebar.php');
//$predlosci = Predlozak::nalozi($_SESSION['racun_id']);
$predlosci = Predlozak::nalozi(0,'TRUE');
?>
<div class="span12">
    <?php echo output_message($message);?>
    <table class="table">
        <thead>
        <tr>
        <th>Račun odobrenja</th>
        <th>Račun zaduženja</th>
        <th>Iznos</th>
        <th>Namjena</th>
        <th><input type="text" class="search-query span2" placeholder="Pretraži" id="search"></th>
        </tr>
        </thead>
        <tbody>
        <?php
            if($predlosci){
            foreach ($predlosci as $predlozak) {
            echo "<tr id=$predlozak->pid><td><a href=create.php?id={$predlozak->pid}>$predlozak->platitelj $predlozak->iban</a></td>";
                echo "<td>$predlozak->iban_primatelja</td>";
            echo "<td>$predlozak->iznos</td>";
            echo "<td>$predlozak->naziv_sifre</td>";
            echo "<td id=$predlozak->pid><a href=edit.php?id=$predlozak->pid><img src=../img/Edit.png>        </a><a id='delete' href=delete.php?id=$predlozak->pid>   <img src=../img/Remove.png> </a></td>";
        }
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

    <!-- premjesti u funkciju --?>
    $(document).on('click', '#delete', function(e){
        if (!confirm('Jeste li sigurni da želite ovo obrisati')) {
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
                    parent.slideUp(300, function () {
                        parent.remove();
                    });
                }
            });
        }
    });
</script>


