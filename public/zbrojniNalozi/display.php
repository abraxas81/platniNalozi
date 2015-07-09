<?php require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) {
    redirect_to("login.php");
}


$predlozak = new Predlozak();
$predlozak = Predlozak::find_by_id($_GET['id']);?>
<div id="success"></div>
<form name="postForm" id="predlozakForm" method="POST">
    <div class="form-horizontal">
        <table>
            <tr>
                <th>Promjeni podatke u nalogu</th>
            </tr>
            <tbody>
<?php include('predlozakForm.php'); ?>
<tr><td><tr><td>
            </tbody>
        </table>
        <input type="hidden" name="Edit" value="edit">
        <input type="submit" name="submit" value="Promjeni"> <input class="odustani" type="button" value="Zatvori">
    </div>
</form>
</div>
