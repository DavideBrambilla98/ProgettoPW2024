<?php
include 'gestioneDB.php';
?>

<?php
    $codRicovero = $_GET["codRicovero"] ?? "";

    if ($codRicovero != "") {
        // Retrieve the current record's data from the database
        try {
            $sql = "SELECT * FROM Ricoveri WHERE CodRic = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$codRicovero]);
            $row = $stmt->fetch();
        } catch (PDOException $e) {
            die("DB Error: " . $e->getMessage());
        }
    }
?>

<form name="updateForm" method="POST">
    <input type="hidden" id="codRicovero" name="codRicovero" value="<?php echo $row["CodRic"] ?? ""; ?>">
    <input type="text" id="paziente" name="paziente" placeholder="Paziente" value="<?php echo $row["Paziente"] ?? ""; ?>"><br>
    <input type="text" id="data" name="data" placeholder="Data" value="<?php echo $row["Data"] ?? ""; ?>"><br>
    <input type="text" id="durata" name="durata" placeholder="Durata" value="<?php echo $row["Durata"] ?? ""; ?>"><br>
    <input type="text" id="motivo" name="motivo" placeholder="Motivo" value="<?php echo $row["Motivo"] ?? ""; ?>"><br>
    <input type="text" id="costo" name="costo" placeholder="Costo" value="<?php echo $row["Costo"] ?? ""; ?>"><br>
    <button type="submit">
        <i class="fa-solid fa-pen"></i>
    </button>
</form>

<?php
if (isset($_POST["codRicovero"])) {
    $codRicovero = $_POST["codRicovero"];
    $paziente = $_POST["paziente"];
    $data = $_POST["data"];
    $durata = $_POST["durata"];
    $motivo = $_POST["motivo"];
    $costo = $_POST["costo"];
    
    updateRicoveriInDb($codRicovero, $paziente, $data, $durata, $motivo, $costo, $conn);


    // Redirect back to the read page
    header('Location: read.php');
    exit;
}
?>
</body>
</html>