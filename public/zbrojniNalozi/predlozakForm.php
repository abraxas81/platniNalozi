<?php if (!$session->is_logged_in()) {
redirect_to("login.php");
}

$placanje = new Placanje();
if (isset($_POST['submit'])) {
$predlozak = Predlozak::find_by_id2($_POST['id']);
$predlozak->id = $_POST['id'];
$predlozak->naziv = $_POST['naziv'];
$predlozak->platitelj_id = $_POST['platitelj_id'];
$predlozak->model_odobrenja_id = $_POST['model_odobrenja_id'];
$predlozak->broj_odobrenja = $_POST['broj_odobrenja'];
$predlozak->iban_primatelja = $_POST['iban_prefix'].$_POST['iban'];
//$predlozak->primatelj_id = $_POST['primatelj_id'];
$predlozak->model_zaduzenja_id = $_POST['model_zaduzenja_id'];
$predlozak->broj_zaduzenja = $_POST['broj_zaduzenja'];
//$predlozak->korisnik_id = $_SESSION['user_id'];

if ($predlozak->save()) {
$session->message = ("Predložak je uspješno dodan !!!");
$placanje = Placanje::find_by_id2($predlozak->id);
$placanje->iznos = $_POST['iznos'];
$placanje->sifra_valute_id = $_POST['valuta'];
$placanje->sifra_namjene_id = $_POST['sifra_namjene_id'];
$placanje->opis = $_POST['opis_placanja'];
$placanje->datum_izvrsenja = $_POST['datum_izvrsenja'];
// 0 je predlo�ak 1 je nalog
$placanje->predlozak_nalog = 0;
//$predlozak->potpisivanje = $_POST['potpisivanje'];
/*$predlozak->created_at = date('YMd hms');
$predlozak->updated_at = date('YMd hms');*/
if ($placanje->save()) {
$session->message = ("Placanje je uspješno dodano !!!");
redirect_to('create.php');
} else {
//Neuspje�no
$message = join("<div class=alert alert-error>$placanje->errors</div><br>");
}
} else {
//Neuspje�no
$message = join("<div class=alert alert-error>$predlozak->errors</div><br>");
}
}

if (empty($_GET['id'])) {
$session->message("Nije zaprimljen id predloška");
redirect_to('index.php');
} ?>
            <?php include('formE.php'); ?>

<script>

    $(document).ready(function () {

        $('form#predlozakForm').submit(function (event) {
            event.preventDefault(); //Prevent the default submit
            var iban = $('#iban_prefix').val() + $('#iban').val();
            if (IBAN.isValid(iban)) {
                $.ajax({
                    type: 'POST',
                    url: 'ajax.php',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function (data) {
                        $('#success').fadeOut(5000).append('<p>' + data.posted + '</p>'); //If successful, than throw a success message
                        var id = data.nalozi[0].id;
                        console.log(data);
                        if(!data.novo){
                            $('tr#'+id).replaceWith("<tr><td>"+data.nalozi[0].platitelj + data.nalozi[0].iban+"</td><td>"+data.nalozi[0].iban_primatelja+"</td><td>"+data.nalozi[0].naziv_sifre+"</td><td class=price>"+data.nalozi[0].iznos+"</td><td id="+data.nalozi[0].id+"><a class=dodaj> <img src=../img/Edit.png> </a> <a id='delete' href=delete.php?id="+data.nalozi[0].id+"> <img src=../img/Remove.png> </a></td></tr>");
                        } else {
                            $('tbody#pretraga').append("<tr><td>"+data.nalozi[0].platitelj + data.nalozi[0].iban+"</td><td>"+data.nalozi[0].iban_primatelja+"</td><td>"+data.nalozi[0].naziv_sifre+"</td><td class=price>"+data.nalozi[0].iznos+"</td><td id="+data.nalozi[0].id+"><a class=dodaj> <img src=../img/Edit.png> </a> <a id='delete' href=delete.php?id="+data.nalozi[0].id+"> <img src=../img/Remove.png> </a></td></tr>");
                        }
                        $('#predlozakForm').hide();
                        $('#nalozi').show();
                        $(calculateSum);
                    }
                });
                return true;
            } else {
            event.preventDefault(); //Prevent the default submit
            $("#errmsg").html("Ispravite").show();
            }
        });

        $(".odustani").click(function (){
            $('#predlozakForm').hide();
            $('#nalozi').show();
        })
    });
</script>