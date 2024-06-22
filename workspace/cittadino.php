<!DOCTYPE html>
<html>
    <head>
        <meta charset='utf-8'>
        <meta http-equiv='X-UA-Compatible' content='IE=edge'>
        <title>Cittadini</title>
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
                        <option value="1">codice fiscale</option>
                        <option value="2">nome</option>
                        <option value="3">cognome</option>
                        <option value="4">data di nascita</option>
                        <option value="5">luogo di nascita</option>
                        <option value="6">indirizzo</option>
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

                $cf = $nome = $cognome = $dataNascita = $luogoNascita = $indirizzo = "";

                  //per prendere il valore dalle altre pagine ---------------------------------
                  if ($_SERVER["REQUEST_METHOD"] == "GET") {
                    if(isset($_GET['citt'])){
                        $cf = $_GET['citt'];
                    }
        ?>
                        
        <?php
                }
                //-----------------------------------------------------------------------------   

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $search = $_POST['search'];
                    $cerca = $_POST['cerca'];
            
                    switch ($search) {
                        case "1":
                            $cf = $cerca;
                            break;
                        case "2":
                            $nome = $cerca;
                            break;
                        case "3":
                            $cognome = $cerca;
                            break;
                        case "4":
                            $dataNascita = $cerca;
                            break;
                        case "5":
                            $luogoNascita = $cerca;
                            break;
                        case "6":
                            $indirizzo = $cerca;
                            break;
                    }
                }
          
                $sql = readPersoneFromDb ($cf, $nome, $cognome, $dataNascita, $luogoNascita ,$indirizzo);
            
                // Prepara la query per poi essere eseguita successivamente
                $statoPDO = $conn->prepare($sql);

                //per associare i valori al segnaposto (:cognome è un segnaposto usato nella query)
                if ($cf != "")
                    $statoPDO->bindValue(':cf', "%$cf%");
                if ($nome != "")
                    $statoPDO->bindValue(':nome', "%$nome%");
                if ($cognome != "")
                    $statoPDO->bindValue(':cognome', "%$cognome%");
                if ($dataNascita != "")
                    $statoPDO->bindValue(':dataNascita', "%$dataNascita%");
                if ($luogoNascita != "")
                    $statoPDO->bindValue(':luogoNascita', "%$luogoNascita%");
                if ($indirizzo != "")
                    $statoPDO->bindValue(':indirizzo', "%$indirizzo%");
        ?>
            <div class="scroll-table">
                <?php
                    // eseguo la query che era stata preparata in precedenza (prima di eseguire la query vanno passati i segnaposto)
                    $statoPDO->execute();
                    
                    if ($statoPDO->rowCount() > 0) {
                        echo "<table id='tabella'><tr><th>Nome</th><th>Cognome</th><th>Codice fiscale</th><th>Data di nascita</th><th>Luogo di nascita</th><th>Indirizzo</th><th>Ricoveri</th></tr>";
                        // stampa i dati di ogni riga
                        while($row = $statoPDO->fetch()) {
                            if($row["countRicoveri"] > 0) {
                                $countRicoveri = "<a id='riferimento' href='index.php?countRicoveri=".$row["countRicoveri"]."&codFiscale=".$row["codFiscale"]."'>trovati: ".$row["countRicoveri"]."</a>";
                            } else {
                                $countRicoveri = "no ricoveri";
                            }
                            echo "<tr><td>".$row["nome"]."</td><td>".$row["cognome"]."</td><td>".$row["codFiscale"]."</td><td>".$row["dataNascita"]."</td><td>".$row["nasLuogo"]."</td><td>".$row["indirizzo"]."</td><td>".$countRicoveri."</td></tr>";
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
    <script src='gestioneAzioni.js'></script>
</html>
