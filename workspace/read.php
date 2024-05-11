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
<h1> READ</h1>
<p> CRUD LAME, LEGGI record</p>
<?php
    include 'header.html';	
    include 'navigation.html';
    include 'gestioneDB.php';
?>
<div id="research">      
    <form name="researchForm" method="POST">
            <input type="text" id="fCodOsp" name="fCodOsp" placeholder="CodOspedale"><br>
            <input type="text" id="fCodRic" name="fCodRic" placeholder="CodRicovero"><br>
            <input type="text" id="fPaziente" name="fPaziente" placeholder="Paziente"><br>
            <input type="text" id="fData" name="fData" placeholder="Data"><br>
            <input type="text" id="fDurata" name="fDurata" placeholder="Durata"><br>
            <input type="text" id="fMotivo" name="fMotivo" placeholder="Motivo"><br>
            <input type="text" id="fCosto" name="fCosto" placeholder="Costo"><br>
        <button type="submit">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
    </form>
<div id="results">
       
<?php
  //stabilisce la connessione con il DB
  include 'ConnessioneDB.php';

  // Controlla connessione
  if ($conn->connect_error) {
      die("Connessione fallita: " . $conn->connect_error);
  }

  try {

      $codStruttura = $_POST["fCodOsp"] ?? "";
      $codRicovero = $_POST["fCodRic"] ?? "";
      $paziente  = $_POST["fPaziente"] ?? "";
      $data = $_POST["fdata"] ?? "";
      $durata  = $_POST["fDurata"] ?? "";
      $motivo  = $_POST["fMotivo"] ?? "";
      $costo  = $_POST["fCosto"] ?? "";

      $sql = readRicoveriFromDb ($codStruttura, $codRicovero, $paziente, $data, $durata, $motivo, $costo);
     
      // Prepara la query per poi essere eseguita successivamente
      $statoPDO = $conn->prepare($sql);

      //per associare i valori al segnaposto (:codStruttura è un segnaposto usato nella query)
      if ($codStruttura != "")
          $statoPDO->bindValue(':fCodOsp', "%$codStruttura%");
      if ($codRicovero != "")
          $statoPDO->bindValue(':fCodRic', "%$codRicovero%");
      if ($paziente != "")
          $statoPDO->bindValue(':fpaziente', "%$paziente%");
      if ($data != "")
          $statoPDO->bindValue(':fdata', "%$data%");
      if ($durata != "")
          $statoPDO->bindValue(':fDurata', "%$durata%");
      if ($motivo != "")
          $statoPDO->bindValue(':fMotivo', "%$motivo%");
      if ($costo != "")
          $statoPDO->bindValue(':fCosto', "%$costo%");
  } catch (Exception $e) {

    error_log("Error: ". $e->getMessage());
    echo "An error occurred. Please try again later.";

}
    
?>
<div class="scroll-table">

<?php
// eseguo la query che era stata preparata in precedenza (prima di eseguire la query vanno passati i segnaposto)
$statoPDO->execute();
try {
    if ($statoPDO->rowCount() > 0) {
        echo "<table><tr><th>Codice struttura</th><th>Codice ricovero</th><th>Paziente</th><th>Data</th><th>Durata</th><th>Motivo</th><th>Costo</th></tr>";
        // stampa i dati di ogni riga
        while($row = $statoPDO->fetch()) {
            echo "<tr><td>".$row["CodiceStruttura"]."</td>
            <td>".$row["CodRic"]."</td>
            <td>".$row["Paziente"]."</td>
            <td>".$row["Data"]."</td>
            <td>".$row["Durata"]."</td>
            <td>".$row["Motivo"]."</td>
            <td>".$row["Costo"]."</td>
            <td> 
            <form action='' method='post'>
                <input type='hidden' name='codRicovero' value='".$row["CodRic"]."'>
                <button type='submit' name='delete'><i class='fa-solid fa-trash'></button>
            </form>
            <form action='update.php' method='get'>
                <input type='hidden' name='codRicovero' value='".$row["CodRic"]."'>
                <button type='submit' name='update'><i class='fa-solid fa-pen'></i></button>
            </form>
            </td>
            </tr>";
        }
        echo "</table>";
        if (isset($_POST['delete'])) {
            $codRicovero = $_POST['codRicovero'];
            deleteRicoveriFromDb($codRicovero, 'Ricoveri', $conn);
            header('Location: ' . $_SERVER['PHP_SELF']); 
            exit;
        
        }
    } else {
        echo "0 results";
    }
} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
?>
</div>
<form name="createForm" method="GET" action="create.php">
    <button type="submit">
        <i class="fa-solid fa-plus"></i>
    </button>
    </form>
</body>
</html>