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
                <input id="codiceRicovero" name="codiceRicovero" type="text" placeholder="codice ricovero"/>
                <input id="codiceOspedale" name="codiceOspedale" type="text" placeholder="codice ospedale"/>
                <input id="nomeOspedale" name="nomeOspedale" type="text" placeholder="nome ospedale"/>
                <input id="paziente" name="paziente" type="text" placeholder="paziente(CF)"/>
                <input id="dataRicovero" name="dataRicovero" type="text" placeholder="data"/>
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

                $codRicovero = $_POST["codiceRicovero"] ?? "";
                $codOsp = $_POST["codiceOspedale"] ?? "";
                $nomOsp = $_POST["nomeOspedale"] ?? "";
                $paziente = $_POST["paziente"] ?? "";
                $dataRic = $_POST["dataRicovero"] ?? "";
                $durata = $_POST["durata"] ?? "";
                $motivo = $_POST["motivo"] ?? "";
                $costo = $_POST["costo"] ?? "";
                $nomePaziente = $_POST["nomePaziente"] ?? "";
            
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
                if ($durata != "")
                    $statoPDO->bindValue(':durata', "%$durata%");
                if ($motivo != "")
                    $statoPDO->bindValue(':motivo', "%$motivo%");
                if ($costo != "")
                    $statoPDO->bindValue(':costo', "%$costo%");

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

    <?php	
        include 'footer.html';
    ?>
    <script src='gestioneAzioni.js'></script>
</html>
