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
                <input id="cf" name="cf" type="text" placeholder="codice fiscale"/>
                <input id="nome" name="nome" type="text" placeholder="nome"/>
                <input id="cognome" name="cognome" type="text" placeholder="cognome"/>
                <input id="dataNascita" name="dataNascita" type="text" placeholder="data di nascita"/>
                <input id="luogoNascita" name="luogoNascita" type="text" placeholder="luogo di nascita"/>
                <input id="indirizzo" name="indirizzo" type="text" placeholder="indirizzo"/>
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

                $cf  = $_POST["cf"] ?? "";
                $nome = $_POST["nome"] ?? "";
                $cognome = $_POST["cognome"] ?? "";
                $dataNascita = $_POST["dataNascita"] ?? "";
                $luogoNascita  = $_POST["luogoNascita"] ?? "";
                $indirizzo  = $_POST["indirizzo"] ?? "";
            
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
                            echo "<table><tr><th>CF</th><th>Nome</th><th>Cognome</th><th>Data di nascita</th><th>Luogo di nascita</th><th>Indirizzo</th><th># Ricoveri</th></tr>";
                            // stampa i dati di ogni riga
                            while($row = $statoPDO->fetch()) {
                                echo "<tr><td>".$row["codFiscale"]."</td><td>".$row["nome"]."</td><td>".$row["cognome"]."</td><td>".$row["dataNascita"]."</td><td>".$row["nasLuogo"]."</td><td>".$row["indirizzo"]."</td><td>".$row["numRicoveri"]."</td></tr>";
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
