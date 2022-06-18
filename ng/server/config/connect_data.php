<?php

  $servername = "localhost";
  $dbname = "cerotics_store";
  $username = "root";
  $password = "";
  $connected = false;

  try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;",$username,$password);
    $conn -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $connected = true;

  } catch (PDOException $e) {
    $connected = false;

  }

?>
