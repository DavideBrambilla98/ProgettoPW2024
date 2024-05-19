<?php
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
        <meta charset='utf-8'>
        <meta http-equiv='X-UA-Compatible' content='IE=edge'>
        <title>Gestionale</title>
        <link rel="stylesheet" href="style.css">
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <link rel='stylesheet' type='text/css' media='screen' href='main.css'>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <script src='main.js'></script>
</head>
<body>


<h1> CREATE</h1>
<p> CRUD LAME, Crea record</p>



<?php
    include 'ConnessioneDB.php';
    include 'gestioneDB.php';
    ?>
<?php
$codric = uniqid('RIC-');
if ($_POST){
    $codosp = $_POST['CodOspedale'];
    $paziente =  $_POST['Paziente'];
    $data = $_POST['Data'];
    $durata =  $_POST['Durata'];
    $motivo = $_POST['Motivo'];
    $costo =  $_POST['Costo'] ;
    
    $result = createRicoveriInDb($codosp,$codric, $paziente, $data, $durata, $motivo, $costo);

    if ($result) {
        $_SESSION['flash_message'] = 'Nuovo record creato correttamente';    } else {
        $_SESSION['flash_message'] = 'Errore nella creazione del record: ' . $conn->error;
        }
    header('Location: index.php' );
    exit;

}
$conn = null;
?>
<form action ="create.php" method ="POST" onsubmit="return verificaCampi()">
    <input type = "text" id = "CodOspedale" name = "CodOspedale" placeholder ="Codice Struttura"><br>
    <input type="text" id="CodiceRicovero" name="CodiceRicovero" placeholder="Codice Ricovero" value="<?php echo $codric; ?>" readonly><br>
    <input type = "text" id = "Paziente" name = "Paziente" placeholder ="Paziente"><br>
    <input type = "text" id = "Data" name = "Data" placeholder ="Data"><br>
    <input type = "text" id = "Durata" name = "Durata" placeholder ="Durata"><br>
    <input type = "text" id = "Motivo" name = "Motivo" placeholder ="Motivo"><br>
    <input type = "text" id = "Costo" name = "Costo" placeholder ="Costo"><br>
    <input type = "submit" value = "INVIO">
</form>
<script>
function verificaCampi() {
    var codOspedale = document.getElementById("CodOspedale").value;
    var paziente = document.getElementById("Paziente").value;
    var data = document.getElementById("Data").value;
    var durata = document.getElementById("Durata").value;
    var motivo = document.getElementById("Motivo").value;
    var costo = document.getElementById("Costo").value;

    if (codOspedale === "" || paziente === "" || data === "" || durata === "" || motivo === "" || costo === "") {
        alert("Tutti i campi sono obbligatori!");
        return false;
    }

    // Puoi aggiungere altri controlli qui, ad esempio per il formato della data o la validità del costo

    return true;
}
</script>
</html>
</body>