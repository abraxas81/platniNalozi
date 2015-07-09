<?php require_once('../../includes/initialize.php');

if (!$session->is_logged_in()) {
    redirect_to("login.php");
}

$zadatak = new Zadaci();

include_layout_template('admin_header.php');

include_layout_template('sidebar.php'); ?>

<div class="span12">
    <form name="postForm" id="zadatakForm" method="POST">
        <div class="form-horizontal">
            <?php include('zadaci_form.php'); ?>
            <input type="submit" name="submit" id="dodaj_zadatak" value="Dodaj zadatak">
        </div>
    </form>
    <div id="success"></div>
    <div id="zaduziKorisnika">
        <?php $users = User::all(); ?>
        <select multiple id="korisnici" name="korisnici[]">
            <?php foreach ($users as $user) { ?>
                <option value="<?php echo $user->id ?>"><?php echo $user->ime . ' ' . $user->prezime ?></option>
            <?php } ?>
        </select>

        <p id="dodaj"><a href="#">Dodaj</a></p>
    </div>
    <div id="korisnici" hidden>
        <form id="ostaloForm" name="ostaloForm" method="POST">
            <table id="dodaj">
                <th>Ime i prezime</th>
                <th>Rok izvršenja</th>
                <th>Komentar</th>
                <th>Prioritet</th>
                <th>Akcije</th>
                <th>Akcija 2</th>
            </table>
            <input type="submit" value="Dodaj">
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {

        $('#zaduziKorisnika').hide();

        $('form#zadatakForm').submit(function (event) { //Trigger on form submit
            $('#naziv + .throw_error').empty(); //Clear the messages first
            $('#success').empty();

            //Validate fields if required using jQuery

            var postForm = { //Fetch form data
                'naziv': $('input[name=naziv]').val(), //Store name fields value
                'tekst': $('input[name=tekst]').val(), //Store name fields value
                'expire_at': $('input[name=expire_at]').val(), //Store name fields value
                'prioritet_id': $('select[name=prioritet_id]').val(), //Store name fields value
                'submit': $('input[name=submit]').val() //Store name fields value
            };

            $.ajax({ //Process the form using $.ajax()
                type: 'POST', //Method type
                url: 'ajax.php', //Your form processing file URL
                data: postForm, //Forms name
                dataType: 'json',
                success: function (data) {

                    if (!data.success) { //If fails
                        if (data.errors.name) { //Returned if any error from process.php
                            $('.throw_error').fadeIn(1000).html(data.errors.name); //Throw relevant error
                        }
                    } else {
                        $('#success').fadeIn(1000).append('<p>' + data.posted + '</p>'); //If successful, than throw a success message
                        $('#dodaj_zadatak').hide();
                        $('#zaduziKorisnika').show();
                    }
                }
            });
            event.preventDefault(); //Prevent the default submit
        });

        $('form#ostaloForm').submit(function (event) {
            event.preventDefault(); //Prevent the default submit
            $.ajax({
                type: 'POST',
                url: 'ajax.php',
                data: $(this).serialize(),
                success: function (data) {
                    $('#success').fadeIn(1000).append('<p>' + data.posted + '</p>'); //If successful, than throw a success message
                    $('#zaduziKorisnika').hide();
                }
            });
        });

        $(document).on('click', 'span', function () {
            var ajde = $(this).parent('td').attr('id');
            $("select#korisnici")
                .append($("<option></option>")
                    .attr("value", this.id)
                    .text(ajde));
            $(this).parent().parent().remove();
        });

        $("p#dodaj").click(function () {
            $('select#korisnici :selected').each(function (i, selected) {
                $('table#dodaj').append('<tr><input type="hidden" name="member[k_id][]" id="k_id" value=' + $(selected).val() + '><td>' + $(selected).text() + '</td><td><input type="datetime" name="member[expire_at][]" id="expire_at"></td><td><input type="text" name="member[tekst][]" required"></td><td><select name="member[prioritet_id][]" required><?php foreach ($prioriteti as $prioritet) {?><option value=<?php echo $prioritet->id; ?>><?php echo $prioritet->naziv ?></option><?php } ?></select></td><td id="' + $(selected).text() + '"><span id="korisnici"><a>Remove</a></span></td></tr>');
                $('span#korisnici').attr('id', $(selected).val());
                $('span#korisnici').attr('data', $(selected).text());
            }),
                $('select#korisnici :selected').remove();
            $('div#korisnici').show();
        });

        $('.datetimepicker').datetimepicker({
            lang: 'hr',
            format: 'd.m.Y H:i:s'
        })
    });
</script>




