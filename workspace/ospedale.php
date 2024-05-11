<!DOCTYPE html>
<html>
    <head>
        <meta charset='utf-8'>
        <meta http-equiv='X-UA-Compatible' content='IE=edge'>
        <title>Ospedali</title>
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
                <input id="codStruttura" name="codStruttura" type="text" placeholder="codice"/>
                <input id="nomeStruttura" name="nomeStruttura" type="text" placeholder="nome"/>
                <input id="indirizzo" name="indirizzo" type="text" placeholder="indirizzo"/>
                <input id="comuneStruttura" name="comuneStruttura" type="text" placeholder="comune"/>
                <input id="direttoreSanitario" name="direttoreSanitario" type="text" placeholder="direttore sanitario(CF)"/>
                <button type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
        <div id="results">

        <?php

            //stabilisce la connessione con il DB
            include 'connessioneDB.php';

            // Controlla connessione
            if ($conn->connect_error) {
                die("Connessione fallita: " . $conn->connect_error);
            }
            try {

                $codStruttura = $_POST["codStruttura"] ?? "";
                $nomeStruttura = $_POST["nomeStruttura"] ?? "";
                $indirizzoStruttura  = $_POST["indirizzo"] ?? "";
                $comuneStruttura = $_POST["comuneStruttura"] ?? "";
                $direttoreSanitario  = $_POST["direttoreSanitario"] ?? "";
            
                $sql = readOspedaliFromDb ($codStruttura, $nomeStruttura, $indirizzoStruttura, $comuneStruttura, $direttoreSanitario);
            
                // Prepara la query per poi essere eseguita successivamente
                $statoPDO = $conn->prepare($sql);

                //per associare i valori al segnaposto (:codStruttura è un segnaposto usato nella query)
                if ($codStruttura != "")
                    $statoPDO->bindValue(':codStruttura', "%$codStruttura%");
                if ($nomeStruttura != "")
                    $statoPDO->bindValue(':nomeStruttura', "%$nomeStruttura%");
                if ($indirizzoStruttura != "")
                    $statoPDO->bindValue(':indirizzoStruttura', "%$indirizzoStruttura%");
                if ($comuneStruttura != "")
                    $statoPDO->bindValue(':comuneStruttura', "%$comuneStruttura%");
                if ($direttoreSanitario != "")
                    $statoPDO->bindValue(':direttoreSanitario', "%$direttoreSanitario%");

        ?>
        <div class="scroll-table">
            <?php
                    // eseguo la query che era stata preparata in precedenza (prima di eseguire la query vanno passati i segnaposto)
                    $statoPDO->execute();
                    
                        if ($statoPDO->rowCount() > 0) {
                            echo "<table><tr><th>Codice struttura</th><th>Nome</th><th>Indirizzo</th><th>Comune</th><th>Direttore sanitario</th><th>Nome direttore</th><th>Cognome direttore</th><th># Ricoveri</th></tr>";
                            // stampa i dati di ogni riga
                            while($row = $statoPDO->fetch()) {
                                echo "<tr><td>".$row["CodiceStruttura"]."</td><td>".$row["DenominazioneStruttura"]."</td><td>".$row["Indirizzo"]."</td><td>".$row["Comune"]."</td><td>".$row["DirettoreSanitario"]."</td><td>".$row["nome"]."</td><td>".$row["cognome"]."</td><td>".$row["countRicoveri"]."</td></tr>";
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
    
</html>
