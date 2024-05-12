<DOCTYPE html>
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


<form action ="create.php" method ="POST">
    <input type = "text" id = "CodiceStruttura" name = "CodiceStruttura" placeholder ="Codice Struttura"><br>
    <input type = "text" id = "CodRic" name = "CodRic" placeholder ="Codice Ricovero"><br>
    <input type = "text" id = "Paziente" name = "Paziente" placeholder ="Paziente"><br>
    <input type = "text" id = "Data" name = "Data" placeholder ="Data"><br>
    <input type = "text" id = "Durata" name = "Durata" placeholder ="Durata"><br>
    <input type = "text" id = "Motivo" name = "Motivo" placeholder ="Motivo"><br>
    <input type = "text" id = "Costo" name = "Costo" placeholder ="Costo"><br>
    <input type = "submit" value = "INVIO">
</form>
<?php
    include 'ConnessioneDB.php';
    include 'gestioneDB.php';
    ?>
<?php
if ($_POST['CodRic']){
    $codosp = $_POST['CodiceStruttura'];
    $codric = $_POST['CodRic'];
    $paziente =  $_POST['Paziente'];
    $data = $_POST['Data'];
    $durata =  $_POST['Durata'];
    $motivo = $_POST['Motivo'];
    $costo =  $_POST['Costo'] ;
    $result = createRicoveriInDb($codosp,$codric, $paziente, $data, $durata, $motivo, $costo);

    if ($result) {
        echo "Nuovo record creato correttamente";
    } else {
        echo "Error: " . $conn->error;
    }
    header('Location: read.php ' );
    exit;

}
$conn = null;
?>
</html>
</body>