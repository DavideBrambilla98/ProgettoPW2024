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
                        <option value="4">cronica(X)</option>
                        <option value="5">mortale(X)</option>
                    </select>
                    <i id="pulsDiscesa" class="fa-solid fa-caret-down"></i>
                </div>
                    <input id="cerca" name="cerca" type="text" placeholder="cerca"/>
                    <button type="submit">
                        <i id="pulsRicerca" class="fa-solid fa-magnifying-glass"></i>
                    </button>
            </form>
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

                $cod = $nome = $criticita = $cronica = $mortale = "";
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
                            $cronica = $cerca;
                            break;
                        case "5":
                            $mortale = $cerca;
                            break;
                    }
                }
            
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
