<?php
// logout.php
include 'config.php';

// Unisti sesiju
session_destroy();

// Preusmeri na login stranicu
header("Location: login.php");
exit();
?>