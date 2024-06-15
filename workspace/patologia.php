<!DOCTYPE html>
<html>
    <head>
        <meta charset='utf-8'>
        <meta http-equiv='X-UA-Compatible' content='IE=edge'>
        <title>Patologie</title>
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
                        <option value="1">codice patologia</option>
                        <option value="2">nome patologia</option>
                        <option value="3">criticità</option>
                        <option value="4">cronica</option>
                        <option value="5">mortale</option>
                        <option value="6">cronica e mortale</option>
                        <option value="7">nè cronica nè mortale</option>
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

                $cod = $codRico= $nome = $criticita = $cronica = $mortale = "";

                  //per prendere il valore dalle altre pagine ---------------------------------
                  if ($_SERVER["REQUEST_METHOD"] == "GET") {
                    if(isset($_GET['pat'])){
                        $codRico = $_GET['pat'];
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
                            $cod = $cerca;
                            break;
                        case "2":
                            $nome = $cerca;
                            break;
                        case "3":
                            $criticita = $cerca;
                            break;
                        case "4":
                            $cronica = 1;
                            $mortale = 0;
                            break;
                        case "5":
                            $cronica = 0;
                            $mortale = 1;
                            break;
                        case "6":
                            $cronica = 1;
                            $mortale = 1;
                            break;
                        case "7":
                            $cronica = 0;
                            $mortale = 0;
                            break;
                    }
                }
            
                $sql = readPatologieFromDb ($cod, $nome, $criticita, $cronica, $mortale,$codRico);
            
                // Prepara la query per poi essere eseguita successivamente
                $statoPDO = $conn->prepare($sql);

                //per associare i valori al segnaposto (:cod è un segnaposto usato nella query)
                if ($cod != "")
                    $statoPDO->bindValue(':cod', "$cod");
                if ($nome != "")
                    $statoPDO->bindValue(':nome', "%$nome%");
                if ($criticita != "")
                    $statoPDO->bindValue(':criticita', "%$criticita%");
                if ($cronica != "")
                    $statoPDO->bindValue(':cronica', $cronica);
                if ($mortale != "")
                    $statoPDO->bindValue(':mortale', $mortale);
                if($codRico!="")
                    $statoPDO->bindValue(':codRico', $codRico);

        ?>
        <div class="scroll-table">
            <?php
                    // eseguo la query che era stata preparata in precedenza (prima di eseguire la query vanno passati i segnaposto)
                    $statoPDO->execute();
                    $type = $tipoPat = "";
                    if ($statoPDO->rowCount() > 0) {

                        $type = "<a href='cittadino.php?citt=".$row["Paziente"]."'> ".$row["Paziente"]."</a>";


                        echo "<table id='tabella'><tr><th>Codice</th><th>Nome</th><th>Criticità</th><th>Tipo</th><th>Ricoveri</th></tr>";
                        // output data of each row
                        while($row = $statoPDO->fetch()) {

                            if($row["countRicoveri"] > 0) {
                                $countRicoveri = "<a id='riferimento' href='index.php?codPat=".$row["Codice"]."'>trovate: ".$row["countRicoveri"]."</a>";
                            } else {
                                $countRicoveri = "no ricoveri";
                            }

                            if($row["Cronica"] == 0 && $row["Mortale"] == 0)
                                $type = 1;
                            if($row["Cronica"] == 0 && $row["Mortale"] == 1)
                                $type = 3;
                            if($row["Cronica"] == 1 && $row["Mortale"] == 0)
                                $type = 2;
                            if($row["Cronica"] == 1 && $row["Mortale"] == 1)
                                $type = 4;

                            switch ($type) {
                                case "1":
                                    $tipoPat = "Non cronica e non mortale";
                                    break;
                                case "2":
                                    $tipoPat = "Cronica";
                                    break;
                                case "3":
                                    $tipoPat = "Mortale";
                                    break;
                                case "4":
                                    $tipoPat = "Cronica e mortale";
                                    break;
                            }
                            echo "<tr><td>".$row["Codice"]."</td><td>".$row["Nome"]."</td><td>".$row["Criticita"]."</td><td>".$tipoPat."</td><td>".$countRicoveri."</td></tr>";
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
    <?php	
        include 'footer.html';
    ?>
    </body>
    <script src='gestioneAzioni.js'></script>
    <script>document.addEventListener('DOMContentLoaded', cercaSelezionata());</script>
</html>
