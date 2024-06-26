
<?php
include 'ConnessioneDB.php';

function readPatologieFromDb($cod, $nome, $criticita, $cronica, $mortale, $codRico): string
{
    $sql = "SELECT Patologie.Codice, Patologie.Nome, Patologie.Criticita, Patologie.Cronica, Patologie.Mortale ,  SubQuery.countRicoveri
                FROM Patologie
                LEFT JOIN (
                    SELECT PatologiaRicovero.CodPatologia, COUNT(*) AS countRicoveri
                    FROM PatologiaRicovero
                    LEFT JOIN Ricoveri ON Ricoveri.CodiceRicovero = PatologiaRicovero.CodiceRicovero
                    GROUP BY PatologiaRicovero.CodPatologia
                ) AS SubQuery ON Patologie.Codice = SubQuery.CodPatologia
                WHERE 1=1";

    if ($cod != "")
        $sql .= " AND Patologie.Codice LIKE :cod";
    if ($codRico != "")
        $sql .= " AND EXISTS (
                SELECT 1 FROM PatologiaRicovero
                INNER JOIN Ricoveri ON Ricoveri.CodiceRicovero = PatologiaRicovero.CodiceRicovero
                WHERE PatologiaRicovero.CodPatologia = Patologie.Codice AND Ricoveri.CodiceRicovero LIKE :codRico
            )";
    if ($nome != "")
        $sql .= " AND Patologie.Nome LIKE :nome";
    if ($criticita != "")
        $sql .= " AND Patologie.Criticita LIKE :criticita";
    if ($cronica != "")
        $sql .= " AND Patologie.Cronica = :cronica";
    if ($mortale != "")
        $sql .= " AND Patologie.Mortale = :mortale";

    $sql .= " ORDER BY Patologie.Nome";
    return $sql;
}

function readPersoneCrud(): array
{
    $sql = "SELECT codFiscale, CONCAT(nome, ' ', cognome) AS nomecognome
                FROM Persone";
    return array("sql" => $sql, "params" => array());
}

function readPatologieCrud($cod, $nome): string
{
    $sql = "SELECT Codice, Nome
        FROM Patologie
        WHERE 1=1";


    if ($cod != "")
        $sql .= " AND Codice = :cod";
    if ($nome != "")
        $sql .= " AND Nome LIKE :nome";
    return $sql;
}

function readOspedaliCrud($codStruttura, $nomeStruttura): string
{
    $sql = "SELECT CodiceStruttura, DenominazioneStruttura
                FROM Ospedali
                WHERE 1=1";

    if ($codStruttura != "")
        $sql .= " AND CodiceStruttura LIKE :codStruttura";
    if ($nomeStruttura != "")
        $sql .= " AND DenominazioneStruttura LIKE :nomeStruttura";


    return $sql;
}

function readOspedaliFromDb($codStruttura, $nomeStruttura, $indirizzoStruttura, $comuneStruttura, $direttoreSanitario): string
{
    $sql = "SELECT Ospedali.CodiceStruttura, Ospedali.DenominazioneStruttura, Ospedali.Indirizzo, Ospedali.Comune, Ospedali.DirettoreSanitario, Persone.nome,Persone.cognome, COUNT(*) AS countRicoveri
                FROM Ospedali
                JOIN Persone ON Persone.codFiscale = Ospedali.Direttoresanitario
                LEFT JOIN Ricoveri ON Ospedali.CodiceStruttura = Ricoveri.CodOspedale
                WHERE 1=1";

    if ($codStruttura != "")
        $sql .= " AND CodiceStruttura LIKE :codStruttura";
    if ($nomeStruttura != "")
        $sql .= " AND DenominazioneStruttura LIKE :nomeStruttura";
    if ($indirizzoStruttura != "")
        $sql .= " AND Indirizzo LIKE :indirizzoStruttura";
    if ($comuneStruttura != "")
        $sql .= " AND Comune LIKE :comuneStruttura";
    if ($direttoreSanitario != "") 
        $sql .= " AND CONCAT(Persone.nome, ' ', Persone.cognome) LIKE :direttoreSanitario";

    $sql .= " GROUP BY Ospedali.CodiceStruttura";
    $sql .= " ORDER BY Ospedali.DenominazioneStruttura";

    return $sql;
}



function readRicoveriFromDb($nomOsp, $paziente, $nome, $cognome, $dataRic, $patologia, $codOsp, $cr)
{
    $sql = "SELECT Ricoveri.CodiceRicovero, Ricoveri.CodOspedale, Ospedali.DenominazioneStruttura, Ricoveri.Paziente, Persone.nome, Persone.cognome, Ricoveri.Data, Ricoveri.Durata, Ricoveri.Motivo, Ricoveri.Costo, Patologie.Nome, Patologie.Codice AS codRicovero, SubQuery.numPatol

                FROM Ricoveri
                JOIN Ospedali ON Ricoveri.CodOspedale = Ospedali.CodiceStruttura
                JOIN Persone ON Ricoveri.Paziente = Persone.codFiscale
                JOIN (
                    SELECT PatologiaRicovero.CodiceRicovero, COUNT(*) AS numPatol
                    FROM PatologiaRicovero
                    GROUP BY PatologiaRicovero.CodiceRicovero
                ) AS SubQuery ON Ricoveri.CodiceRicovero = SubQuery.CodiceRicovero
                LEFT JOIN PatologiaRicovero ON Ricoveri.CodiceRicovero = PatologiaRicovero.CodiceRicovero
                LEFT JOIN Patologie ON PatologiaRicovero.CodPatologia = Patologie.codice
                WHERE 1=1";


    if ($nomOsp != "") {
        $sql .= " AND Ospedali.DenominazioneStruttura LIKE :DenominazioneStruttura";
    }
    if ($codOsp != "") {
        $sql .= " AND Ospedali.CodiceStruttura LIKE :CodOspedale";
    }
    if ($paziente != "") {
        $sql .= " AND Ricoveri.Paziente LIKE :Paziente";
    }
    if ($nome != "") {
        $sql .= " AND CONCAT(Persone.nome, ' ', Persone.cognome) LIKE :nome";
    }
    if ($cognome != "") {
        $sql .= " AND Persone.cognome LIKE :cognome";
    }
    if ($dataRic != "") {
        $sql .= " AND Ricoveri.Data LIKE :dataRic";
    }
    if ($patologia != "") {
        $sql .= " AND EXISTS (
                SELECT 1 FROM PatologiaRicovero
                WHERE PatologiaRicovero.CodiceRicovero = Ricoveri.CodiceRicovero AND PatologiaRicovero.CodPatologia LIKE :patologia
            )";
    }
    if ($cr != "") {
        $sql .= " AND Ricoveri.CodiceRicovero LIKE :codR";
    }

    $sql .= " GROUP BY Ricoveri.CodiceRicovero";
    $sql .= " ORDER BY Persone.nome";
    return $sql;
}

function createPatologiaRicoveroInDb($codOspedale, $codiceRicovero, $codPatologia)
{
    global $conn;
    $sql = 'INSERT INTO PatologiaRicovero (CodOspedale, CodiceRicovero, CodPatologia) VALUES (:CodOspedale, :CodiceRicovero, :CodPatologia)';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':CodOspedale', $codOspedale);
    $stmt->bindParam(':CodiceRicovero', $codiceRicovero);
    $stmt->bindParam(':CodPatologia', $codPatologia);
    $stmt->execute();
    return $stmt->rowCount();
}

function updatePatologiaRicoveroInDb($codRicovero, $codPatologia, $codOspedale, $conn)
{
    try {
        $sql = "UPDATE PatologiaRicovero SET CodPatologia = ?, CodOspedale = ? WHERE CodiceRicovero = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$codPatologia, $codOspedale, $codRicovero]);
        echo "Updated PatologiaRicovero: CodPatologia=$codPatologia, CodOspedale=$codOspedale, CodiceRicovero=$codRicovero"; // Debug
    } catch (PDOException $e) {
        die("DB Error: " . $e->getMessage());
    }
}

function deletePatologiaRicoveroFromDb($codRicovero, $tableName, $conn)
{
    $sql = "DELETE FROM PatologiaRicovero WHERE CodiceRicovero = :CodiceRicovero";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':CodiceRicovero', $codRicovero);
    try {
        $stmt->execute();
        echo "Record deleted successfully.";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

function createRicoveriInDb($codStruttura, $codRicovero, $paziente, $data, $durata, $motivo, $costo)
{

    global $conn;
    $sql = 'INSERT INTO Ricoveri (CodOspedale, CodiceRicovero, Paziente, Data, Durata, Motivo, Costo) VALUES (:CodOspedale, :CodiceRicovero, :Paziente, :Data, :Durata, :Motivo, :Costo)';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':CodOspedale', $codStruttura);
    $stmt->bindParam(':CodiceRicovero', $codRicovero);
    $stmt->bindParam(':Paziente', $paziente);
    $stmt->bindParam(':Data', $data);
    $stmt->bindParam(':Durata', $durata);
    $stmt->bindParam(':Motivo', $motivo);
    $stmt->bindParam(':Costo', $costo);
    $stmt->execute();
    return $stmt->rowCount();
}


function updateRicoveriInDb($codRicovero, $codOspedale, $data, $durata, $motivo, $costo, $conn)
{
    try {
        $sql = "UPDATE Ricoveri SET CodOspedale = ?, Data = ?, Durata = ?, Motivo = ?, Costo = ? WHERE CodiceRicovero = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$codOspedale, $data, $durata, $motivo, $costo, $codRicovero]);
    } catch (PDOException $e) {
        die("DB Error: " . $e->getMessage());
    }
}

function deleteRicoveriFromDb($codRicovero, $tableName, $conn)
{

    $sql = "DELETE FROM Ricoveri WHERE CodiceRicovero = :CodiceRicovero";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':CodiceRicovero', $codRicovero);
    try {
        $stmt->execute();
        echo "Record deleted successfully.";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

function readPersoneFromDb($cf, $nome, $cognome, $dataNascita, $luogoNascita, $indirizzo): string
{
    $sql = "SELECT  codFiscale,nome, cognome, dataNascita, nasLuogo, indirizzo, COUNT(Ricoveri.CodiceRicovero) AS countRicoveri
                FROM Persone
                LEFT JOIN Ricoveri ON Persone.codFiscale= Ricoveri.Paziente
                WHERE 1=1";

    if ($cf != "")
        $sql .= " AND codFiscale LIKE :cf";
    if ($nome != "") 
        $sql .= " AND CONCAT(Persone.nome, ' ', Persone.cognome) LIKE :nome";
    if ($dataNascita != "")
        $sql .= " AND dataNascita LIKE :dataNascita";
    if ($luogoNascita != "")
        $sql .= " AND nasLuogo LIKE :luogoNascita";
    if ($indirizzo != "")
        $sql .= " AND indirizzo LIKE :indirizzo";

    $sql .= " GROUP BY Persone.codFiscale";
    $sql .= " ORDER BY Persone.nome";

    return $sql;
}


//per definire la chiave primaria della tabella
function singlePrimaryKeyTable($nomeTabella, $nomeColonna)
{
    $sql = "ALTER TABLE $nomeTabella
        ADD PRIMARY KEY ($nomeColonna)";
    return $sql;
}

//per definire la chiave primaria della tabella come coppia di colonne
function doublePrimaryKeyTable($nomeTabella, $nomeColonna1, $nomeColonna2)
{
    $sql = "ALTER TABLE $tableName
        ADD PRIMARY KEY ($nomeColonna1,$nomeColonna2)";
    return $sql;
}

//per definire la foreign-key della tabella
function foreignKeyTable($nomeTabella, $nomeColonna)
{
    $sql = "ALTER TABLE $nomeTabella
        ADD FOREIGN KEY ($nomeColonna)";
    return $sql;
}

//per definire una colonna senza duplicati
function uniqueIndex($nomeTabella, $nomeColonna)
{
    $sql = "ALTER TABLE $nomeTabella
        ADD UNIQUE ($nomeColonna)";
    return $sql;
}

?>
