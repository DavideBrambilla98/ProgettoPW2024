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
        $sql = "SELECT R.*, P.CodPatologia, O.DenominazioneStruttura, PA.Nome 
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    
    <script>
        $(function() {
            var ospedali = <?php echo json_encode(array_map(function($ospedale) {
                return ["label" => $ospedale["DenominazioneStruttura"], "value" => $ospedale["CodiceStruttura"]];
            }, $ospedali)); ?>;
            
            var patologie = <?php echo json_encode(array_map(function($patologia) {
                return ["label" => $patologia["Nome"], "value" => $patologia["Codice"]];
            }, $patologie)); ?>;

            // Autocomplete per Ospedale
            $("#Ospedale").autocomplete({
                source: ospedali,
                minLength: 0,
                select: function(event, ui) {
                    $("#Ospedale").val(ui.item.label);
                    $("#CodOspedale").val(ui.item.value);
                    return false;
                }
            }).focus(function() {
                $(this).autocomplete("search", "");
            });

            // Autocomplete per Motivo
            $("#MotivoDescrizione").autocomplete({
                source: patologie,
                minLength: 0,
                select: function(event, ui) {
                    $("#MotivoDescrizione").val(ui.item.label);
                    $("#Codice").val(ui.item.value);
                    return false;
                }
            }).focus(function() {
                $(this).autocomplete("search", "");
            });
        });
    </script>
</head>
<body>
<form name="updateForm" method="POST">

    <div>
        <input type="text" id="CodiceRicovero" name="CodiceRicovero" value="<?php echo isset($row["CodiceRicovero"]) ? $row["CodiceRicovero"] : ""; ?>" readonly>
    </div>
    <div>
        <input type="text" id="Paziente" name="Paziente" placeholder="Paziente" value="<?php echo isset($row["Paziente"]) ? $row["Paziente"] : ""; ?>" readonly>
    </div>
    <div>
        <input type="hidden" id="CodOspedale" name="CodOspedale" value="<?php echo isset($row["CodOspedale"]) ? $row["CodOspedale"] : ""; ?>">
        <input type="text" id="Ospedale" name="Ospedale" placeholder="Ospedale" value="<?php echo isset($row["DenominazioneStruttura"]) ? $row["DenominazioneStruttura"] : ""; ?>">
    </div>
    <div>
        <input type="hidden" id="Codice" name="Codice" value="<?php echo isset($row["CodPatologia"]) ? $row["CodPatologia"] : ""; ?>">
        <input type="text" id="MotivoDescrizione" name="MotivoDescrizione" placeholder="patologia" value="<?php echo isset($row["Nome"]) ? $row["Nome"] : ""; ?>">
    </div>
    <div>
        <input type="date" id="Data" name="Data" value="<?php echo isset($row["Data"]) ? date("Y-m-d", strtotime(convertiData($row["Data"]))) : ''; ?>">
    </div>
    <div>
        <input type="text" id="Durata" name="Durata" placeholder="Durata" value="<?php echo isset($row["Durata"]) ? $row["Durata"] : ""; ?>">
    </div>
    <div>
        <input type="text" id="Motivo" name="Motivo" placeholder="Motivo" value="<?php echo isset($row["Motivo"]) ? $row["Motivo"] : ""; ?>">
    </div>
    <div>
        <input type="text" id="Costo" name="Costo" placeholder="Costo" value="<?php echo isset($row["Costo"]) ? $row["Costo"] : ""; ?>">
    </div>
    <button type="submit" name ="submit">

        <i class="fa-solid fa-pen"></i>
    </button>
</form>

<?php

function convertiData($data) {
    $parti = explode("/", $data);
    if (count($parti) == 3 && $parti[0] > 12) {
        return $parti[2] . "-" . $parti[1] . "-" . $parti[0];
    }
    return  $data;
}

if (isset($_POST["submit"]))  {
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
