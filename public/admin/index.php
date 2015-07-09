<?php

require_once('../../includes/initialize.php');

if (!$session->is_logged_in()) { redirect_to("login.php"); }
$racuni = Racun::korisnik($_SESSION['user_id']);
?>

<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('sidebar.php'); ?>

<div class="span12">
        <table class="table">
                <thead>
                <tr>
                        <th>Odaberi račun<a href="../racuni/create.php">  + Novi</a></th>
                        <th>IBAN</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if ($racuni){
                        foreach ($racuni as $racun) {
                                echo "<tr></tr><td><a href='odabirRacuna.php?id=$racun->id'>$racun->naziv</a></td>";
                                echo "<td>$racun->iban</td></tr>";
                        }
                }
                ?>
                </tbody>
        </table>
</div>
šđčćž
</div>

<?php include_layout_template('admin_footer.php'); ?>

