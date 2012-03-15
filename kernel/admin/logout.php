<?php
logout();
unset($_SESSION['REQUEST_URI']);
http_redirect('/admin/login.php');
?>