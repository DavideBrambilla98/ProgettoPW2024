<?php
    $servername = "localhost";
    $username = "davidebrambilla";
    $dbname = "my_davidebrambilla";
    $password = null;
    $error = false;

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //$conn = null; // Chiudi la connessione
    } catch (PDOException $e) {
        die("DB Error: " . $e->getMessage());
    }
?>


