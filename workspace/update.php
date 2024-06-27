<?php
ob_start();
session_start();
include 'gestioneDB.php';
include 'TendinaOspedali.php';
include 'TendinaPatologie.php';

// Ottenere i dati
$ospedali = getOspedali($conn);
$patologie = getPatologia($conn);

$codRicovero = $_GET["CodiceRicovero"] ?? "";

if ($codRicovero != "") {
    try {
        $sql = "SELECT R.*, P.CodPatologia, O.DenominazioneStruttura, PA.Nome, 
                (SELECT CONCAT(Persone.nome, ' ', Persone.cognome) 
                 FROM Persone 
                 WHERE Persone.codFiscale = R.Paziente) AS PazienteNomeCognome
                FROM Ricoveri R 
                JOIN PatologiaRicovero P ON R.CodiceRicovero = P.CodiceRicovero 
                JOIN Ospedali O ON R.CodOspedale = O.CodiceStruttura 
                JOIN Patologie PA ON P.CodPatologia = PA.Codice
                WHERE R.CodiceRicovero = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$codRicovero]);
        $row = $stmt->fetch();
    } catch (PDOException $e) {
        die("DB Error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Ricoveri</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="styleCrud.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="azioniCRUD.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            autocompleteOspedaliPatologie(
            <?php echo json_encode(array_map(function ($ospedale) {
                return ["label" => $ospedale["DenominazioneStruttura"], "value" => $ospedale["CodiceStruttura"]];
            }, $ospedali)); ?>,
            <?php echo json_encode(array_map(function ($patologia) {
                return ["label" => $patologia["Nome"], "value" => $patologia["Codice"]];
            }, $patologie)); ?>
        );
        });
    </script>
</head>

<body>
    <?php
    include 'header.html';
    include 'footer.html';

    ?>

    <form name="updateForm" method="POST" onsubmit="return verificaCampiUpdate()">

        <div>
            <div class="testo">Codice ricovero:</div>
            <input type="text" id="CodiceRicovero" name="CodiceRicovero" value="<?php echo isset($row["CodiceRicovero"]) ? $row["CodiceRicovero"] : ""; ?>" readonly>
        </div>
        <div>
            <div class="testo">Paziente:</div>
            <input type="text" id="Paziente" name="Paziente" placeholder="Paziente" value="<?php echo isset($row["PazienteNomeCognome"]) ? $row["PazienteNomeCognome"] : ""; ?>" readonly>
        </div>
        <div>
            <div class="testo">Nome ospedale:</div>
            <input type="hidden" id="CodOspedale" name="CodOspedale" value="<?php echo isset($row["CodOspedale"]) ? $row["CodOspedale"] : ""; ?>">
            <input type="text" id="Ospedale" name="Ospedale" placeholder="Nome Ospedale" value="<?php echo isset($row["DenominazioneStruttura"]) ? $row["DenominazioneStruttura"] : ""; ?>">
        </div>
        <div>
            <div class="testo">Patologia:</div>
            <input type="hidden" id="Codice" name="Codice" value="<?php echo isset($row["CodPatologia"]) ? $row["CodPatologia"] : ""; ?>">
            <input type="text" id="MotivoDescrizione" name="MotivoDescrizione" placeholder="patologia" value="<?php echo isset($row["Nome"]) ? $row["Nome"] : ""; ?>">
        </div>
        <div>
            <div class="testo">Data inizio ricovero:</div>
            <input type="date" id="Data" name="Data" value="<?php echo isset($row["Data"]) ? date("Y-m-d", strtotime(convertiData($row["Data"]))) : ''; ?>">
        </div>
        <div>
            <div class="testo">Durata (giorni):</div>
            <input type="text" id="Durata" name="Durata" placeholder="Durata (giorni)" value="<?php echo isset($row["Durata"]) ? $row["Durata"] : ""; ?>">
        </div>
        <div>
            <div class="testo">Motivo:</div>
            <input type="text" id="Motivo" name="Motivo" placeholder="Motivo" value="<?php echo isset($row["Motivo"]) ? $row["Motivo"] : ""; ?>">
        </div>
        <div>
            <div class="testo">Costo (€):</div>
            <input type="text" id="Costo" name="Costo" placeholder="Costo" value="<?php echo isset($row["Costo"]) ? $row["Costo"] : ""; ?>">
        </div>
        <button type="submit" name="submit">

            <i class="fa-solid fa-pen"></i>
        </button>
    </form>

    <?php

    function convertiData($data)
    {
        $parti = explode("/", $data);
        if (count($parti) == 3 && $parti[0] > 12) {
            return $parti[2] . "-" . $parti[1] . "-" . $parti[0];
        }
        return  $data;
    }

    if (isset($_POST["submit"])) {
        $codRicovero = $_POST["CodiceRicovero"];
        $data = $_POST["Data"];
        $durata = $_POST["Durata"];
        $motivo = $_POST["Motivo"];
        $costo = $_POST["Costo"];
        $codOspedale = $_POST["CodOspedale"];
        $codPatologia = $_POST["Codice"];

        try {
            // Update Ricoveri
            updateRicoveriInDb($codRicovero, $codOspedale, $data, $durata, $motivo, $costo, $conn);

            // Update PatologiaRicovero
            updatePatologiaRicoveroInDb($codRicovero, $codPatologia, $codOspedale, $conn);

            // Redirect only after successful updates
            $_SESSION['flash_message'] = 'Ricovero modificato correttamente!';
            header('Location:index.php');
            exit;
        } catch (PDOException $e) {
            die("DB Error: " . $e->getMessage());
        }
    }

    ob_end_flush();
    ?>
</body>

</html>