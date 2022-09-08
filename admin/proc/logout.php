<?php
session_start();
unset($_SESSION['user']);
header("Location: /phonebook/index.php");
?>
