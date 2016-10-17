<?php
session_start();
unset($_SESSION['desc']);
header("location: view.php");
?>