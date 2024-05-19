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
                <input id="codicePatologia" name="codicePatologia" type="text" placeholder="codice patologia"/>
                <input id="nomePatologia" name="nomePatologia" type="text" placeholder="nome patologia"/>
                <input id="criticita" name="criticita" type="text" placeholder="criticità"/>
                <input id="cronica" name="cronica" type="text" placeholder="cronica = X"/>
                <input id="mortale" name="mortale" type="text" placeholder="mortale = X"/>
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

                $cod = $_POST["codicePatologia"] ?? "";
                $nome = $_POST["nomePatologia"] ?? "";
                $criticita  = $_POST["criticita"] ?? "";
                $cronica = $_POST["cronica"] ?? "";
                $mortale  = $_POST["mortale"] ?? "";
            
                $sql = readPatologieFromDb ($cod, $nome, $criticita, $cronica, $mortale);
            
                // Prepara la query per poi essere eseguita successivamente
                $statoPDO = $conn->prepare($sql);

                //per associare i valori al segnaposto (:cod è un segnaposto usato nella query)
                if ($cod != "")
                    $statoPDO->bindValue(':cod', $cod);
                if ($nome != "")
                    $statoPDO->bindValue(':nome', "%$nome%");
                if ($criticita != "")
                    $statoPDO->bindValue(':criticita', $criticita);
                if ($cronica != "")
                    $statoPDO->bindValue(':cronica', $cronica);
                if ($mortale != "")
                    $statoPDO->bindValue(':mortale', $mortale);
        ?>
        <div class="scroll-table">
            <?php
                    // eseguo la query che era stata preparata in precedenza (prima di eseguire la query vanno passati i segnaposto)
                    $statoPDO->execute();
                    
                    if ($statoPDO->rowCount() > 0) {
                        echo "<table id='tabella'><tr><th>Codice</th><th>Nome</th><th>Criticità</th><th>Cronica</th><th>Mortale</th></tr>";
                        // output data of each row
                        while($row = $statoPDO->fetch()) {
                            echo "<tr><td>".$row["Codice"]."</td><td>".$row["Nome"]."</td><td>".$row["Criticita"]."</td><td>".$row["Cronica"]."</td><td>".$row["Mortale"]."</td></tr>";
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
