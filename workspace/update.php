<?php
    session_start();
include 'gestioneDB.php';
?>
  <head>
        <meta charset='utf-8'>
        <meta http-equiv='X-UA-Compatible' content='IE=edge'>
        <title>Ricoveri</title>
        <link rel="stylesheet" href="style.css">
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <link rel='stylesheet' type='text/css' media='screen' href='main.css'>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <script src='main.js'></script>
    </head>
<?php
    $codRicovero = $_GET["CodiceRicovero"] ?? "";

    if ($codRicovero != "") {
        try {
            $sql = "SELECT * FROM Ricoveri WHERE CodiceRicovero = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$codRicovero]);
            $row = $stmt->fetch();
        } catch (PDOException $e) {
            die("DB Error: " . $e->getMessage());
        }
    }
?>

<form name="updateForm" method="POST">
    <input type="hidden" id="CodiceRicovero" name="CodiceRicovero" value="<?php echo $row["CodiceRicovero"] ?? ""; ?>">
    <input type="text" id="Paziente" name="Paziente" placeholder="Paziente" value="<?php echo $row["Paziente"] ?? ""; ?>"><br>
    <input type="text" id="Data" name="Data" placeholder="Data" value="<?php echo $row["Data"] ?? ""; ?>"><br>
    <input type="text" id="Durata" name="Durata" placeholder="Durata" value="<?php echo $row["Durata"] ?? ""; ?>"><br>
    <input type="text" id="Motivo" name="Motivo" placeholder="Motivo" value="<?php echo $row["Motivo"] ?? ""; ?>"><br>
    <input type="text" id="Costo" name="Costo" placeholder="Costo" value="<?php echo $row["Costo"] ?? ""; ?>"><br>
    <button type="submit">
        <i class="fa-solid fa-pen"></i>
    </button>
</form>

<?php
if (isset($_POST["CodiceRicovero"])) {
    $codRicovero = $_POST["CodiceRicovero"];
    $paziente = $_POST["Paziente"];
    $data = $_POST["Data"];
    $durata = $_POST["Durata"];
    $motivo = $_POST["Motivo"];
    $costo = $_POST["Costo"];
    
    updateRicoveriInDb($codRicovero, $paziente, $data, $durata, $motivo, $costo, $conn);

    $_SESSION['flash_message'] = 'Ricovero modificato correttamente!';
    header('Location: index.php');
    exit;
}
?>
</body>
</html>