<?php require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) {
    redirect_to("login.php");
}
$predlozak = new Predlozak();
$predlozak = Predlozak::find_by_id3($_GET['id']); ?>
<div id="success"></div>
<form name="postForm" id="predlozakForm" method="POST">
    <div class="form-horizontal">
        <table>
            <tr>
                <th>Dodaj nalog u datoteku</th>
            </tr>
            <tbody>
<?php include('predlozakForm.php'); ?>
<input type="hidden" name="New" value="new">
<tr><td><input type="submit" name="submit" value="Dodaj"> <input class="odustani" type="button" value="Odustani"><tr><td>
            </tbody>
        </table>
    </div>
</form>
</div>
