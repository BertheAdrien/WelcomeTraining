<?php
session_start(); 

session_destroy();

header('Location: ../pages/Login.php'); 
exit();
?>