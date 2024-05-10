<!DOCTYPE html>
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
        <?php
            include 'header.html';	
            include 'navigation.html';
            include 'gestioneDB.php';
        ?>

        <div id="research">      
            <form name="researchForm" method="POST">
                <input id="codiceRicovero" name="codiceRicovero" type="text" placeholder="codice ricovero"/>
                <input id="codiceOspedale" name="codiceOspedale" type="text" placeholder="codice ospedale"/>
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
                $paziente = $_POST["paziente"] ?? "";
                $dataRic = $_POST["dataRicovero"] ?? "";
                $durata = $_POST["durata"] ?? "";
                $motivo = $_POST["motivo"] ?? "";
                $costo = $_POST["costo"] ?? "";
            
                $sql = readRicoveriFromDb ($codRicovero, $codOsp, $paziente, $dataRic, $durata, $motivo, $costo);
            
                // Prepara la query per poi essere eseguita successivamente
                $statoPDO = $conn->prepare($sql);

                //per associare i valori al segnaposto (:cod è un segnaposto usato nella query)
                if ($codRicovero != "")
                    $statoPDO->bindValue(':codiceRicovero', "%$codRicovero%");
                if ($codOsp != "")
                    $statoPDO->bindValue(':codiceOspedale', "%$codOsp%");
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
                    echo "<table><tr><th>CodiceRicovero</th><th>CodiceOspedale</th><th>Paziente</th><th>Data</th><th>Durata</th><th>Motivo</th><th>Costo</th></tr>";
                    // output data of each row
                    while($row = $statoPDO->fetch()) {
                        echo "<tr><td>".$row["CodiceRicovero"]."</td><td>".$row["CodOspedale"]."</td><td>".$row["Paziente"]."</td><td>".$row["Data"]."</td><td>".$row["Durata"]."</td><td>".$row["Motivo"]."</td><td>".$row["Costo"]."</td></tr>";
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
