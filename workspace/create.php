<?php
include 'ConnessioneDB.php';
include 'gestioneDB.php';
include 'TendinaOspedali.php';
include 'TendinaPatologie.php';
include 'TendinaPersone.php';

    session_start();
    $ospedali = getOspedali($conn);
    $patologie = getPatologia($conn);
    $persone = getPersone($conn);
    $paziente_data = array_map(function($persona) {
        return ["label" => $persona["nomecognome"], "value" => $persona["codFiscale"]];
    }, $persone);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
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
        }, $ospedali));?>;

        var patologie = <?php echo json_encode(array_map(function($patologia) {
            return ["label" => $patologia["Nome"], "value" => $patologia["Codice"]];
        }, $patologie));?>;
        var paziente = <?php echo json_encode($paziente_data); ?>;

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

        // Autocomplete per Paziente
        $("#Paziente").autocomplete({
            source: paziente,
            minLength: 0,
            select: function(event, ui) {
                $("#Paziente").val(ui.item.label);
                $("#CodiceFiscale").val(ui.item.value);
                return false;
            }
        }).focus(function() {
            $(this).autocomplete("search", "");
        });
    });

    function verificaCampi() {
        var codOspedale = document.getElementById("CodOspedale").value;
        var paziente = document.getElementById("Paziente").value;
        var data = document.getElementById("Data").value;
        var durata = document.getElementById("Durata").value;
        var motivo = document.getElementById("Motivo").value;
        var costo = document.getElementById("Costo").value;
        var codice = document.getElementById("Codice").value;

        if (codOspedale === "" || paziente === "" || data === "" || durata === "" || motivo === "" || costo === "" || codice === "") {
            alert("Tutti i campi sono obbligatori!");
            return false;
        }

        // Puoi aggiungere altri controlli qui, ad esempio per il formato della data o la validità del costo

        return true;
    }
    </script>
</head>
<body>

<h1> CREATE</h1>
<p> CRUD LAME, Crea record</p>

<?php
$codric = uniqid('RIC-');
if ($_POST) {
    $codosp = $_POST['CodOspedale'];
    $codfisc = $_POST['CodiceFiscale'];
    $data = $_POST['Data'];
    $durata = $_POST['Durata'];
    $motivo = $_POST['Motivo'];
    $costo = $_POST['Costo'];
    $codPatologia = $_POST['Codice'];

    try {
        createRicoveriInDb($codosp, $codric, $codfisc, $data, $durata, $motivo, $costo);
        createPatologiaRicoveroInDb($codosp, $codric, $codPatologia);

        echo '<script>alert("Nuovo record creato correttamente!"); window.location.href = "index.php";</script>';
        exit;
    } catch (PDOException $e) {
        die("DB Error: ". $e->getMessage());
    } catch (Exception $e) {
        die("Error: ". $e->getMessage());
    }
}
$conn = null;
?>
<form action ="create.php" method ="POST" onsubmit="return verificaCampi()">
    <div>
        <input type="text" id="CodiceRicovero" name="CodiceRicovero" value="<?php echo $codric; ?>" readonly>
    </div>
    <div>
        <input type="hidden" id="CodiceFiscale" name="CodiceFiscale">
        <input type="text" id="Paziente" name="Paziente" placeholder="Paziente" value="<?php echo isset($row["Paziente"]) ? $row["Paziente"] : ""; ?>">
    </div>
    <div>
        <input type="hidden" id="CodOspedale" name="CodOspedale" value="<?php echo isset($row["CodOspedale"]) ? $row["CodOspedale"] : ""; ?>">
        <input type="text" id="Ospedale" name="Ospedale" placeholder="Ospedale" value="<?php echo isset($row["DenominazioneStruttura"])? $row["DenominazioneStruttura"] : "";?>">
    </div>
    <div>
        <input type="hidden" id="Codice" name="Codice" value="<?php echo isset($row["CodPatologia"])? $row["CodPatologia"] : "";?>">
        <input type="text" id="MotivoDescrizione" name="MotivoDescrizione" placeholder="Patologia" value="<?php echo isset($row["Nome"])? $row["Nome"] : "";?>">
    </div>
    <div>
        <input type="date" id="Data" name="Data" value="<?php echo isset($row["Data"])? date("Y-m-d", strtotime($row["Data"])) : '';?>">
    </div>
    <div>
        <input type="text" id="Durata" name="Durata" placeholder="Durata" value="<?php echo isset($row["Durata"])? $row["Durata"] : "";?>">
    </div>
    <div>
        <input type="text" id="Motivo" name="Motivo" placeholder="Motivo" value="<?php echo isset($row["Motivo"])? $row["Motivo"] : "";?>">
    </div>
    <div>
        <input type="text" id="Costo" name="Costo" placeholder="Costo" value="<?php echo isset($row["Costo"])? $row["Costo"] : "";?>">
    </div>

    <input type = "submit" value = "INVIO">
</form>

</body>
</html>
