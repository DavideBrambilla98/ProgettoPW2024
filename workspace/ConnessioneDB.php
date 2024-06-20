<?php
    $servername = "localhost";
    $username = "marcoalgisi";
    $dbname = "my_marcoalgisi";
    $password = null;
    $error = false;
    $conn = null;
    try {
      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $conn->setAttribute(PDO::ATTR_AUTOCOMMIT, true); 
   
        } catch (PDOException $e) {
            die("DB Error: " . $e->getMessage());
        }
  ?>


