<?php require_once('../../includes/initialize.php');

if (!$session->is_logged_in()) {
    redirect_to("login.php");
}

$racun = new Racun();
if (isset($_POST['submit'])) {
    $racun->id = $_POST['id'];
    $racun->naziv = $_POST['naziv'];
    $racun->iban = $_POST['iban'];
    /*$racun->created_at = date('YMd hms');
    $racun->updated_at = date('YMd hms');*/
    $racun->korisnik_id = $_SESSION['user_id'];
    if ($racun->save()) {
        $session->message = ("Zadatak je uspješno promjenjen !!!");
        redirect_to('create.php');
    } else {
        //Neuspješno
        $message = join("<div class=alert alert-error>$racun->errors</div><br>");
    }
}

if (empty($_GET['id'])) {
    $session->message("Nije zaprimljen id računa");
    redirect_to('index.php');
}
$zbrojniNalog = ZbrojniNalog::find_by_id($_GET['id']);
include('../predlosci/index.php');
?>
<div id="sucess"></div>
<div class="span15">
    <div id="responsecontainer" align="center"></div>
    <table class="table" id="dodaj">
        <thead>
        <tr>
            <th>
                <input type="hidden" value="<?php echo $zbrojniNalog->id?>" id="znid">
                Naziv datoteke: <?php echo $zbrojniNalog->naziv ?><a id="show"> +Novi nalog</a></th>

        </tr>
            <tr>
            <th>Račun platitelja</th>
            <th>Račun primatelja</th>
            <th>Namjena</th>
            <th>Iznos</th>
            <th><input type="text" id="search2" class="search-query span2" placeholder="Pretraži"></th>
        </tr>
        </thead>
        <tbody id="pretraga">
        <?php $nalozi = ZbrojniNalog::nalozi($zbrojniNalog->id);
        $sum = 0;
        if($nalozi){
        foreach ($nalozi as $nalog){
        ?>
        <tr id="<?php echo $nalog->id?>">
            <td><?php echo $nalog->platitelj.' : '.$nalog->iban?></td>
            <td><?php echo $nalog->iban_primatelja?></td>
            <td><?php echo $nalog->naziv_sifre?></td>
           <!-- <td><?php /*echo $nalog->opis*/?></td>-->
            <td class="price"><?php echo $nalog->iznos?></td>
            <td id=<?php echo $nalog->id ?>><a class="dodaj"> <img src=../img/Edit.png> </a><a id='delete' href=delete.php?id=<?php echo $nalog->id ?>> <img src=../img/Remove.png> </a></td>
        </tr>
        <?php
            }
        }?>
        </tbody>
        <tr><td  colspan="3">Ukupno</td><td id="rezultat"></td></tr>
    </table>
</div>
<script>
     $(document).ready(function () {

        $('table#predlosci').hide();

        $("a#show").click(function() {
            $('table#predlosci').show();
        });
        /*$('form#naloziForm').submit(function (event) {
            event.preventDefault(); //Prevent the default submit
            $.ajax({
                type: 'POST',
                url: 'ajax.php',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (data) {
                        $('#success').fadeIn(1000).append('<p>' + data.posted + '</p>'); //If successful, than throw a success message
                        alert(data.platitelj_id);
                        console.log(data.platitelj_id);
                        $('#dodaj_zadatak').hide();
                        $(this).hide();
                    }
                });
            });*/

        $('body').on('click', 'a#ukloni', function() {
            $(this).closest("tr").remove();
        });

        $('body').on('focus',".datepicker", function(){
            $(this).datetimepicker({
                lang: 'hr',
                format: 'd.m.Y'
            });
        });

         $('body').on('keyup', '#search2', function(){
         var $rows2 = $('table#dodaj tbody#pretraga tr');
             var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

             $rows2.show().filter(function() {
                 var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                 return !~text.indexOf(val);
             }).hide();
         });


    });

    $(document).on('click','.dodaj2', function(){
        $('table#predlosci').hide();
        var id = $(this).closest('td').attr('id').replace();
        $.ajax({    //create an ajax request to load_page.php
            type: "GET",
            url: "display2.php?id="+id,
            dataType: "html",   //expect html to be returned
            success: function(response){
                $("#responsecontainer").html(response);
                $('html, body').animate({ scrollTop: $('#responsecontainer').offset().top }, 'slow');
                //alert(response);
            }
        });
    });
    $(document).on('click','.dodaj', function(){
            $('table#predlosci').hide();

            var id = $(this).closest('td').attr('id').replace();
            $.ajax({    //create an ajax request to load_page.php
                type: "GET",
                url: "display.php?id="+id,
                dataType: "html",   //expect html to be returned
                success: function(response){
                    $("#responsecontainer").html(response);
                    $('html, body').animate({ scrollTop: $('#responsecontainer').offset().top }, 'slow');
                    //alert(response);
                }
            });
        });



    //ne radi vjerojatno zato jer je u bazi ovo polje tipa money
    $(calculateSum);

    function calculateSum() {

        var sum = 0;
//iterate through each td based on class and add the values
        $(".price").each(function () {

            var value = $(this).text();
            // add only if the value is number
            value = Number(value.replace(/[.,kn]+/g, ""));
            value = parseFloat(value);
            if (!isNaN(value) && value.length != 0) {
                sum += parseFloat(value);
            }
        });
        sum = sum/100;
        sum = currencyFormatDE(sum);
        $('#rezultat').text(sum);

    }

     function currencyFormatDE (num) {
         return num
                 .toFixed(2) // always two decimal digits
                 .replace(".", ",") // replace decimal point character with ,
                 .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") + " kn" // use . as a separator
     }
</script>