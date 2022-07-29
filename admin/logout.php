<?php session_start();
unset($_SESSION['login']);
session_destroy();
header('Location: /admin/login.php?m=23');

?>