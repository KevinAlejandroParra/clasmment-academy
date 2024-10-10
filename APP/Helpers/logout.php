<?php
session_start();
session_destroy();
header("Location: ../../../../APP/Views/Crud/login.php");
exit();
?>