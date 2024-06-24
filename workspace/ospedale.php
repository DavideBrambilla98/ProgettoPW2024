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
            <div class="select-wrapper">
                <select id="search" name="search">
                    <!-- <option value="1">codice ospedale</option> -->
                    <option value="2">nome ospedale</option>
                    <!-- <option value="3">indirizzo ospedale</option> -->
                    <option value="4">comune ospedale</option>
                    <option value="5">direttore sanitario</option>
                </select>
                <i id="pulsDiscesa" class="fa-solid fa-caret-down"></i>
            </div>
            <input id="cerca" name="cerca" type="text" placeholder="cerca" />
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

        $codStruttura = $nomeStruttura = $indirizzoStruttura = $pazicomuneStrutturaente = $direttoreSanitario = "";

        //per prendere il valore dalle altre pagine ---------------------------------
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            if (isset($_GET['osp'])) {
                $codStruttura = $_GET['osp'];
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
                    $codStruttura = $cerca;
                    break;
                case "2":
                    $nomeStruttura = $cerca;
                    break;
                case "3":
                    $indirizzoStruttura = $cerca;
                    break;
                case "4":
                    $comuneStruttura = $cerca;
                    break;
                case "5":
                    $direttoreSanitario = $cerca;
                    break;
            }
        }

        $sql = readOspedaliFromDb($codStruttura, $nomeStruttura, $indirizzoStruttura, $comuneStruttura, $direttoreSanitario);

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
            echo "<table id='tabella'><tr><th>Nome</th><th>Indirizzo</th><th>Comune</th><th>Direttore sanitario</th><th>Ricoveri</th></tr>";
            // stampa i dati di ogni riga
            while ($row = $statoPDO->fetch()) {
                if ($row["countRicoveri"] > 0) {
                    $countRicoveri = "<a id='riferimento' href='index.php?countRicoveri=" . $row["countRicoveri"] . "&codiceStruttura=" . $row["CodiceStruttura"] . "'>trovati: " . $row["countRicoveri"] . "</a>";
                } else {
                    $countRicoveri = "no ricoveri";
                }
                $direttore = "<a href='cittadino.php?citt=" . $row["DirettoreSanitario"] . "'>" . $row["nome"] . " " . $row["cognome"] . "</a>";
                echo "<tr><td>" . $row["DenominazioneStruttura"] . "</td><td>" . $row["Indirizzo"] . "</td><td>" . $row["Comune"] . "</td><td>" . $direttore . "</td><td>" . $countRicoveri . "</td></tr>";
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