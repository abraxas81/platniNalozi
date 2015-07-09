<?php require_once('../../includes/initialize.php');

if (!$session->is_logged_in()) {
    redirect_to("login.php");
}

include_layout_template('admin_header.php');
include_layout_template('sidebar.php');

$predlosci = Predlozak::nalozi(0,'FALSE');
//$predlosci = Predlozak::nalozi($_SESSION['racun_id'],'FALSE');
?>
<div class="span12">
   <?php// echo output_message($message); ?>
    <table class="table" id="predlosci">
        <thead>
        <tr>
        <th hidden>Pid</th>
        <th>Naziv Predloška</th>
        <th>Račun odobrenja</th>
        <th>Račun zaduženja</th>
        <th hidden>Iznos</th>
        <th hidden>Namjena</th>
        <th hidden>Opis</th>
        <th><input type="text" class="search-query span2" placeholder="Pretraži" id="search"></th>
        </tr>
        </thead>
        <tbody id="pretrazi">
        <?php
            if($predlosci){
            foreach ($predlosci as $predlozak) {
            echo "<tr id='naziv'></tr><td id='xyz' hidden>$predlozak->pid</td>";
            echo "<td><a href=../nalozi/create.php?id={$predlozak->pid}>$predlozak->naziv_predloska</a></td>";
            echo "<td>$predlozak->platitelj : $predlozak->iban</td>";
            echo "<td>$predlozak->iban_primatelja</td>";
            echo "<td hidden>$predlozak->iznos</td>";
            echo "<td hidden>$predlozak->sifra</td>";
            echo "<td hidden>$predlozak->opis</td>";
            echo "<td id='$predlozak->pid'><a href=edit.php?id={$predlozak->pid}> <img src=../img/Edit.png> </a></a>";
            echo "<a id='delete' href=delete.php?id={$predlozak->pid}> <img src=../img/Remove.png> </a></a><a class='dodaj2' id=dodaj> <img src=../img/Add.png> </a></a></td></tr>";
        }
        }
        ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function () {

        <!-- premjesti u funkciju --?>
        $('a#delete').click(function (e) {
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

    });
    $('body').on('keyup', '#search', function(){
        var $rows = $('tbody#pretrazi tr');
        var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

        $rows.show().filter(function () {
            var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
            return !~text.indexOf(val);
        }).hide();
    });

</script>


