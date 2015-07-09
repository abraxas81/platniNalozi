<?php

require_once("../../includes/initialize.php");

if($session->is_logged_in()) {redirect_to("index.php");}

//Napomena: obrascu za login treba dati ime submit
if(isset($_POST['submit'])) { //ako je obrazac poslan, tj kliknuto na submit gumb

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $pronadjiKorisnika = User::authenticate($username,$password);

    //provjera da li ova kombinacija usernamea i passworda postoji u bazi
    if ($pronadjiKorisnika) {
        $session->login($pronadjiKorisnika);
        redirect_to("index.php");
    } else {
        $message = "Netočna kombinacija lozinke i korisničkog imena";
    }
} else { //obrazac nije poslan tako da je kliknuto na gumb submit

    $username = "";
    $password = "";
}
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('sidebar.php'); ?>

<div class="span9">
    <div class="hero-unit">
<p>Login za korisnike</p>
		<?php echo output_message($message); ?>
<form action="login.php" method="post">
    <table>
        <tr>
            <td>Username:</td>
            <td>
                <input type="text" name="username" maxlength="30" value="<?php echo htmlentities($username); ?>" />
            </td>
        </tr>
        <tr>
            <td>Password:</td>
            <td>
                <input type="password" name="password" maxlength="30" value="<?php echo htmlentities($password); ?>" />
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" name="submit" value="Login" />
            </td>
        </tr>
    </table>
</form>
    </div>

</div>
<?php include_layout_template('admin_footer.php'); ?>




