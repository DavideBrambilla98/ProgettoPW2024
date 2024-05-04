<?php
$servername = "localhost";
$username = "davidebrambilla";
$dbname = "my_davidebrambilla";
$password = null;
$error = false;

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT Codice, Nome, Criticità FROM Patologie";
    $stmt = $conn->query($sql);

    if ($stmt->rowCount() > 0) {
        echo "<table><tr><th>Codice</th><th>Nome</th><th>Criticità</th></tr>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr><td>" . $row["Codice"] . "</td><td>" . $row["Nome"] . "</td><td>" . $row["Criticità"] . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "0 risultati";
    }

    $conn = null; // Chiudi la connessione
} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
?>


