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

        <div id="research">      
            <form name="researchForm" method="POST">
                <div class="select-wrapper">
                    <select id="search" name="search" >
                        <option value="1">codice ricovero</option>
                        <option value="2">codice ospedale</option>
                        <option value="3">nome ospedale</option>
                        <option value="4">paziente(CF)</option>
                        <option value="5">data</option>
                    </select>
                    <i id="pulsDiscesa" class="fa-solid fa-caret-down"></i>
                </div>
                    <input id="cerca" name="cerca" type="text" placeholder="cerca"/>
                    <button type="submit">
                        <i id="pulsRicerca" class="fa-solid fa-magnifying-glass"></i>
                    </button>
            </form>
        </div>

        <?php
            //stabilisce la connessione con il DB
            include 'ConnessioneDB.php';

            // Controlla connessione
            if ($conn->connect_error) {
                die("Connessione fallita: " . $conn->connect_error);
            }
            try {

                $codRicovero = $codOsp = $nomOsp = $paziente = $dataRic = "";

                //per prendere il valore dalle altre pagine ---------------------------------
                if ($_SERVER["REQUEST_METHOD"] == "GET") {
                    if(isset($_GET['codiceStruttura'])){
                        $codOsp = $_GET['codiceStruttura'];
                    }
                    if(isset($_GET['codFiscale'])){
                        $paziente = $_GET['codFiscale'];
                    }
        ?>
                        <script>
                            if (window.history.replaceState) {
                                var url = window.location.href;
                                var cleanedUrl = url.split("?")[0];
                                window.history.replaceState({}, document.title, cleanedUrl);
                            }
                        </script>
        <?php
                }
                //-----------------------------------------------------------------------------

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $search = $_POST['search'];
                    $cerca = $_POST['cerca'];
            
                    switch ($search) {
                        case "1":
                            $codRicovero = $cerca;
                            break;
                        case "2":
                            $codOsp = $cerca;
                            break;
                        case "3":
                            $nomOsp = $cerca;
                            break;
                        case "4":
                            $paziente = $cerca;
                            break;
                        case "5":
                            $dataRic = $cerca;
                            break;
                    }
                }
 
                $sql = readRicoveriFromDb ($codRicovero, $codOsp, $nomOsp, $paziente, $dataRic, $durata, $motivo, $costo);
            
                // Prepara la query per poi essere eseguita successivamente
                $statoPDO = $conn->prepare($sql);

                //per associare i valori al segnaposto (:cod è un segnaposto usato nella query)
                if ($codRicovero != "")
                    $statoPDO->bindValue(':codiceRicovero', "%$codRicovero%");
                if ($codOsp != "")
                    $statoPDO->bindValue(':codiceOspedale', "%$codOsp%");
                if ($nomOsp != "")
                    $statoPDO->bindValue(':nomeOspedale', "%$nomOsp%");
                if ($paziente != "")
                    $statoPDO->bindValue(':paziente', "%$paziente%");
                if ($dataRic != "")
                    $statoPDO->bindValue(':dataRic', "%$dataRic%");
        ?>
        <div class="scroll-table">
            <?php
                // eseguo la query che era stata preparata in precedenza (prima di eseguire la query vanno passati i segnaposto)
                $statoPDO->execute();
            
                if ($statoPDO->rowCount() > 0) {
                    echo "<table id='tabella'><tr><th>Codice ricovero</th><th>Nome ospedale</th><th>Paziente</th><th>Nome</th><th>Cognome</th><th>Data</th><th>Durata</th><th>Motivo</th><th>Costo</th></tr>";
                    // output data of each row
                    while($row = $statoPDO->fetch()) {
                        // tra le quadre ci va il nome della colonna del DB dal quale prendere il campo
                        echo "<tr><td>".$row["CodiceRicovero"]."</td><td>".$row["DenominazioneStruttura"]."</td><td>".$row["Paziente"]."</td><td>".$row["nome"]."</td><td>".$row["cognome"]."</td><td>".$row["Data"]."</td><td>".$row["Durata"]."</td><td>".$row["Motivo"]."</td><td>".$row["Costo"]."</td></tr>";
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
    </body>
    <?php	
        include 'footer.html';
    ?>
    <script src='gestioneAzioni.js'></script>
</html>