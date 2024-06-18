<?php
    session_start();

    if (isset($_SESSION['flash_message'])) {
        echo "<script>alert(\"" . $_SESSION['flash_message'] . "\");</script>";
        unset($_SESSION['flash_message']);
    }
?>
<!DOCTYPE html>
<html>
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

    <body>
        <?php
            include 'header.html';	
            include 'navigation.html';
            include 'gestioneDB.php';
            
        ?>

<div id="ricerca">
            <div id="research">      
                <form name="researchForm" method="POST">

                    <div class="select-wrapper">
                        <select id="search" name="search" >
                            <option value="1">nome paziente</option>
                            <option value="2">cognome paziente</option>
                            <option value="3">paziente(CF)</option>
                            <option value="4">nome ospedale</option>
                            <option value="5">data</option>
                            <option value="6">patologia</option>
                            <!-- <option value="6">patologia</option> -->
                        </select>
                        <i id="pulsDiscesa" class="fa-solid fa-caret-down"></i>
                    </div>
                        <input id="cerca" name="cerca" type="text" placeholder="cerca"/>
                        <button type="submit">
                            <i id="pulsRicerca" class="fa-solid fa-magnifying-glass"></i>
                        </button>
                </form>
                <div id="crudCreate">
                <form id='pulsCreate' name="createForm" method="GET" action="create.php">
                    <button type="submit" title="inserisci ricovero">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </form>
                </div>
            </div>
        </div>
    <div id="results">
        <?php
       
            //stabilisce la connessione con il DB
            include 'ConnessioneDB.php';

            // Controlla connessione
            if ($conn->connect_error) {
                die("Connessione fallita: " . $conn->connect_error);
            }
           
                try {
                

                $codOsp = $_POST["CodOspedale"] ?? "";
                $codRicovero = $_POST["CodiceRicovero"] ?? "";
                $nomOsp = $_POST["DenominazioneStruttura"] ?? "";
                $paziente = $_POST["Paziente"] ?? "";
                $dataRic = $_POST["Data"] ?? "";
                $durata = $_POST["durata"] ?? "";
                $motivo = $_POST["motivo"] ?? "";
                $costo = $_POST["costo"] ?? "";
                $nomePaziente = $_POST["nome"] ?? "";
                $cognomePaziente = $_POST["cognome"] ?? "";
                list($sql, $params) = readRicoveriFromDb ($codOsp, $nomOsp, $codRicovero, $paziente, $nome, $cognome, $dataRic, $durata, $motivo, $costo);

                $statoPDO = $conn->prepare($sql);
                
                foreach ($params as $key=> $value) {
                    $statoPDO->bindValue($key, $value);
                }
                
                $statoPDO->execute();
              /*  $sql = readRicoveriFromDb ($codOsp, $nomOsp, $codRicovero, $paziente, $nomePaziente,$cognomePaziente, $dataRic, $durata, $motivo, $costo);
            
                // Prepara la query per poi essere eseguita successivamente
                $statoPDO = $conn->prepare($sql);

                //per associare i valori al segnaposto (:cod è un segnaposto usato nella query)
                if ($codOsp != "")
                    $statoPDO->bindValue(':CodOspedale', "%$codOsp%");
                else
                    $statoPDO->bindValue(':CodOspedale', "%" );
                if ($codRicovero != "")
                    $statoPDO->bindValue(':CodiceRicovero', "%$codRicovero%");
                else
                    $statoPDO->bindValue(':CodiceRicovero', "%" );
                if ($nomOsp != "")
                    $statoPDO->bindValue(':DenominazioneStruttura', "%$nomOsp%");
                    else
                    $statoPDO->bindValue(':DenominazioneStruttura', "%" );
                if ($paziente != "")
                    $statoPDO->bindValue(':Paziente', "%$paziente%");
                    else
                    $statoPDO->bindValue(':Paziente', "%" );
                if ($nomePaziente != "")
                    $statoPDO->bindValue(':nome', "%$nomePaziente%");
                    else
                    $statoPDO->bindValue(':nome', "%" );
                if ($cognomePaziente != "")
                    $statoPDO->bindValue(':cognome', "%$cognomePaziente%");
                    else
                    $statoPDO->bindValue(':cognome', "%" );
                if ($dataRic != "")
                    $statoPDO->bindValue(':Data', "%$dataRic%");
                    else
                    $statoPDO->bindValue(':Data', "%" );
                if ($durata != "")
                    $statoPDO->bindValue(':durata', "%$durata%");
                    else
                    $statoPDO->bindValue(':durata', "%" );
                if ($motivo != "")
                    $statoPDO->bindValue(':motivo', "%$motivo%");
                    else
                    $statoPDO->bindValue(':motivo', "%" );
                if ($costo != "")
                    $statoPDO->bindValue(':costo', "%$costo%");
                    else
                    $statoPDO->bindValue(':costo', "%" );*/
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
                    echo "<table><tr><th>Nome struttura</th><th>Codice ricovero</th><th>Nome paziente</th><th>Cognome paziente</th><th>Data</th><th>Durata</th><th>Motivo</th><th>Costo</th></tr>";
                    // stampa i dati di ogni riga
                    while($row = $statoPDO->fetch()) {

                        $paz = "<a href='cittadino.php?citt=".$row["Paziente"]."'> ".$row["Paziente"]."</a>";
                        $osp = "<a href='ospedale.php?osp=".$row["CodOspedale"]."'> ".$row["DenominazioneStruttura"]."</a>";
                        $patolog = "<a href='patologia.php?pat=".$row["CodiceRicovero"]."'>trovate: ".$row["numPatol"]."</a>";
                        // tra le quadre ci va il nome della colonna del DB dal quale prendere il campo
                        $dateString = $row["Data"];
                        $date = strtotime($dateString);
                        if ($date !== false) {
                            $formattedDate = date('d/m/Y', $date);
                        } else {
                            $formattedDate = $dateString; 
                        }
      
                        echo 
                        "<tr>
                        <td>".$row["nome"]." ".$row["cognome"]."</td>
                        <td>".$paz."</td>
                        <td>".$osp."</td>
                        <td>".$patolog."</td>
                        <td>".$row["Motivo"]."</td>
                        <td>".$formattedDate."</td>
                        <td>".$row["Durata"]."</td>
                        <td>".$row["Costo"]."</td>
                        <td> 
                        <form class= 'pulsCrud' action='update.php' method='get'>
                            <input type='hidden' name='CodiceRicovero' value='".$row["CodiceRicovero"]."'>
                            <button type='submit'  title='modifica' name='update'><i class='fa-solid fa-pen'></i></button>
                        </form>
                        </td>
                        <td>
                        <form class= 'pulsCrud' id='delete-form-{$row["CodiceRicovero"]}' action='delete.php' method='post'>
                        <input type='hidden' name='CodiceRicovero' value='{$row["CodiceRicovero"]}'>
                        <input type='hidden' name='delete' value='1'>
                        <button type='button' title='elimina' onclick='confirmDelete(\"{$row["CodiceRicovero"]}\")'><i class='fa-solid fa-trash'></i></button>
                        </form>
                        </td>
                        </tr>";
                     
                    }
                    echo "</table>";
                    
                } else {
                    echo "0 results";
                }
            } catch (PDOException $e) {
                die("DB Error: " . $e->getMessage());
            }
        ?>

     
    </div>
<script>
    function confirmDelete(codiceRicovero) {
        if (confirm("Sei sicuro di voler cancellare questo record?")) {
            document.getElementById('delete-form-' + codiceRicovero).submit();
        }
    }
</script>

   
</body>

    <?php	
        include 'footer.html';
    ?>
    
</html>
