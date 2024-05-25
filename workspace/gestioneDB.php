
<?php      
    include 'ConnessioneDB.php';

    function readPatologieFromDb ($cod, $nome, $criticita, $cronica, $mortale) : string {
        $sql = "SELECT Codice, Nome, Criticita, Cronica, Mortale
                FROM Patologie
                WHERE 1=1";

            
        if ($cod != "")
            $sql .= " AND Codice = :cod";
        if ($nome != "")
            $sql .= " AND Nome LIKE :nome";
        if ($criticita != "")
            $sql .= " AND Criticita = :criticita";
        if ($cronica != "")
            $sql .= " AND Cronica = :cronica";
        if ($mortale != "")
            $sql .= " AND Mortale = :mortale";

        return $sql;
	}


    function readOspedaliFromDb ($codStruttura, $nomeStruttura, $indirizzoStruttura, $comuneStruttura, $direttoreSanitario) : string {
        $sql = "SELECT Ospedali.CodiceStruttura, Ospedali.DenominazioneStruttura, Ospedali.Indirizzo, Ospedali.Comune, Ospedali.DirettoreSanitario, Persone.nome,Persone.cognome, COUNT(Ricoveri.CodiceRicovero) AS countRicoveri
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
            $sql .= " AND Persone.nome LIKE :direttoreSanitario OR Persone.cognome LIKE :direttoreSanitario ";

        $sql .= " GROUP BY Ospedali.CodiceStruttura";
        
        return $sql;
    }
 
    function readRicoveriFromDb ($nomOsp, $paziente, $nome, $cognome, $dataRic, $patologia) {
        $sql = "SELECT Ricoveri.CodiceRicovero, Ricoveri.CodOspedale, Ospedali.DenominazioneStruttura, Ricoveri.Paziente,Persone.nome,Persone.cognome, Ricoveri.Data, Ricoveri.Durata, Ricoveri.Motivo, Ricoveri.Costo,Patologie.Nome,Patologie.Codice AS codRicovero
                FROM Ricoveri
                JOIN Ospedali ON Ricoveri.CodOspedale = Ospedali.CodiceStruttura
                JOIN Persone ON Ricoveri.Paziente = Persone.codFiscale
                JOIN PatologiaRicovero ON Ricoveri.CodiceRicovero = PatologiaRicovero.CodiceRicovero
                JOIN Patologie ON PatologiaRicovero.CodPatologia = Patologie.codice
                WHERE 1=1";
    
        if ($nomOsp!= "") {
            $sql.= " AND Ospedali.DenominazioneStruttura LIKE :DenominazioneStruttura";
        }
        if ($paziente!= "") {
            $sql.= " AND Ricoveri.Paziente LIKE :Paziente";
        }
        if ($nome!= "") {
            $sql.= " AND Persone.nome LIKE :nome";
        }
        if ($cognome!= "") {
            $sql.= " AND Persone.cognome LIKE :cognome";
        }
        if ($dataRic!= "") {
            $sql.= " AND Ricoveri.Data LIKE :dataRic";
        }
        if ($patologia!= "") {
            $sql.= " AND PatologiaRicovero.CodPatologia LIKE :patologia";
        }
        return $sql;
    }

        function createRicoveriInDb($codStruttura, $codRicovero, $paziente, $data, $durata, $motivo, $costo){
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
      
    function updateRicoveriInDb($codRicovero, $paziente, $data, $durata, $motivo, $costo, $conn) {
        try {
            $sql = "UPDATE Ricoveri SET Paziente = ?, Data = ?, Durata = ?, Motivo = ?, Costo = ? WHERE CodiceRicovero = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$paziente, $data, $durata, $motivo, $costo, $codRicovero]);
        } catch (PDOException $e) {
            die("DB Error: " . $e->getMessage());
        }
    }
    
    

    function deleteRicoveriFromDb($codRicovero, $tableName, $conn) {

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


    function readPersoneFromDb ($cf, $nome, $cognome, $dataNascita, $luogoNascita ,$indirizzo) : string {
        $sql = "SELECT  codFiscale,nome, cognome, dataNascita, nasLuogo, indirizzo, COUNT(Ricoveri.CodiceRicovero) AS countRicoveri
                FROM Persone
                LEFT JOIN Ricoveri ON Persone.codFiscale= Ricoveri.Paziente
                WHERE 1=1";
                
        if ($cf != "")
            $sql .= " AND codFiscale LIKE :cf";
        if ($nome != "")
            $sql .= " AND nome LIKE :nome";
        if ($cognome != "")
            $sql .= " AND cognome LIKE :cognome";
        if ($dataNascita != "")
            $sql .= " AND dataNascita LIKE :dataNascita";
        if ($luogoNascita != "")
            $sql .= " AND nasLuogo LIKE :luogoNascita";
        if ($indirizzo != "")
            $sql .= " AND indirizzo LIKE :indirizzo";
    
        $sql .= " GROUP BY Persone.codFiscale";
    
        return $sql;
    }
    

    //per definire la chiave primaria della tabella
    function singlePrimaryKeyTable($nomeTabella,$nomeColonna){
        $sql = "ALTER TABLE $nomeTabella
        ADD PRIMARY KEY ($nomeColonna)";
    return $sql;
    }

    //per definire la chiave primaria della tabella come coppia di colonne
     function doublePrimaryKeyTable($nomeTabella,$nomeColonna1,$nomeColonna2){
        $sql = "ALTER TABLE $tableName
        ADD PRIMARY KEY ($nomeColonna1,$nomeColonna2)";
    return $sql;
    }

    //per definire la foreign-key della tabella
    function foreignKeyTable($nomeTabella,$nomeColonna){
        $sql = "ALTER TABLE $nomeTabella
        ADD FOREIGN KEY ($nomeColonna)";
    return $sql;
    }

    //per definire una colonna senza duplicati
    function uniqueIndex($nomeTabella,$nomeColonna){
        $sql = "ALTER TABLE $nomeTabella
        ADD UNIQUE ($nomeColonna)";
    return $sql;
    }

?>
