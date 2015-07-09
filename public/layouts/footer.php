<div id="footer">
    <div class="container">
        <p class="muted credit">Copyright <?php echo date("Y", time()); ?></a></p>
    </div>
</div>

<script src="http://code.jquery.com/jquery.js"></script>
<script src="../js/bootstrap.min.js"></script>

</body>
</html>


<?php if(isset($database)) { $database->close_connection(); } ?>