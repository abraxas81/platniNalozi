<div id="footer">
    <div class="container">
        <p class="muted credit">Copyright <?php echo date("Y", time()); ?></a></p>
    </div>
</div>
</body>
</html>
<?php if(isset($database)) { $database->close_connection(); } ?>