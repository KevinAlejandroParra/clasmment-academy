<?php
// declaro variables
$hostname = "localhost";
$username = "root";
$password = "";
$database = "classment-academy";

// creo conexión
$conn = mysqli_connect($hostname, $username, $password, $database);

// verifico conexión
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
