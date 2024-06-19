
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
    
    function readPersoneCrud() : array {
        $sql = "SELECT codFiscale, CONCAT(nome, ', ', cognome) AS nomecognome
                FROM Persone";
        return array("sql" => $sql, "params" => array());
    }
    function readPatologieCrud($cod,$nome) : string {
        $sql = "SELECT Codice, Nome
        FROM Patologie
        WHERE 1=1";

        if ($cod != "")
            $sql .= " AND Codice = :cod";
        if ($nome != "")
            $sql .= " AND Nome LIKE :nome";
        return $sql;
    }
    function readOspedaliCrud($codStruttura, $nomeStruttura) : string {
        $sql = "SELECT CodiceStruttura, DenominazioneStruttura
                FROM Ospedali
                WHERE 1=1";

        if ($codStruttura != "")
            $sql .= " AND CodiceStruttura LIKE :codStruttura";
        if ($nomeStruttura != "")
            $sql .= " AND DenominazioneStruttura LIKE :nomeStruttura";
        
        
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
            $sql .= " AND DirettoreSanitario LIKE :direttoreSanitario";

        $sql .= " GROUP BY Ospedali.CodiceStruttura";
        
        return $sql;
    }
    function readRicoveriFromDb ($codOsp,$nomOsp,$codRicovero,  $paziente,$nome,$cognome, $dataRic, $durata, $motivo, $costo) : array {
        $sql = "SELECT Ricoveri.CodiceRicovero, Ricoveri.CodOspedale, Ospedali.DenominazioneStruttura, Ricoveri.Paziente,Persone.nome,Persone.cognome, Ricoveri.Data, Ricoveri.Durata, Ricoveri.Motivo, Ricoveri.Costo
                FROM Ricoveri
                JOIN Ospedali ON Ricoveri.CodOspedale = Ospedali.CodiceStruttura
                JOIN Persone ON Ricoveri.Paziente = Persone.codFiscale
                WHERE 1=1";
    
        $params = array();
    
        if ($codOsp!= "") {
            $sql.= " AND Ricoveri.CodOspedale LIKE :codiceOspedale";
            $params[':codiceOspedale'] = "%$codOsp%";
        }
        if ($nomOsp!= "") {
            $sql.= " AND Ospedali.DenominazioneStruttura LIKE :nomeOspedale";
            $params[':nomeOspedale'] = "%$nomOsp%";
        }
        if ($codRicovero!= "") {
            $sql.= " AND Ricoveri.CodiceRicovero LIKE :codiceRicovero";
            $params[':codiceRicovero'] = "%$codRicovero%";
        }
        if ($paziente!= "") {
            $sql.= " AND Ricoveri.Paziente LIKE :paziente";
            $params[':paziente'] = "%$paziente%";
        }
        if ($nome!= "") {
            $sql.= " AND Persone.nome LIKE :nome";
            $params[':nome'] = "%$nome%";
        }
        if ($cognome!= "") {
            $sql.= " AND Persone.cognome LIKE :cognome";
            $params[':cognome'] = "%$cognome%";
        }
        if ($dataRic!= "") {
            $sql.= " AND Ricoveri.Data LIKE :dataRic";
            $params[':dataRic'] = "%$dataRic%";
        }
        if ($durata!= "") {
            $sql.= " AND Ricoveri.Durata LIKE :durata";
            $params[':durata'] = "%$durata%";
        }
        if ($motivo!= "") {
            $sql.= " AND Ricoveri.Motivo LIKE :motivo";
            $params[':motivo'] = "%$motivo%";
        }
        if ($costo!= "") {
            $sql.= " AND Ricoveri.Costo LIKE :costo";
            $params[':costo'] = "%$costo%";
        }
    
        return array($sql, $params);
    }
    function createPatologiaRicoveroInDb($codOspedale, $codiceRicovero, $codPatologia) {
        global $conn;
        $sql = 'INSERT INTO PatologiaRicovero (CodOspedale, CodiceRicovero, CodPatologia) VALUES (:CodOspedale, :CodiceRicovero, :CodPatologia)';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':CodOspedale', $codOspedale);
        $stmt->bindParam(':CodiceRicovero', $codiceRicovero);
        $stmt->bindParam(':CodPatologia', $codPatologia);
        $stmt->execute();
        return $stmt->rowCount(); 
    }
    function updatePatologiaRicoveroInDb($codRicovero, $codPatologia, $codOspedale, $conn) {
        try {
            $sql = "UPDATE PatologiaRicovero SET CodPatologia = ?, CodOspedale = ? WHERE CodiceRicovero = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$codPatologia, $codOspedale, $codRicovero]);
            echo "Updated PatologiaRicovero: CodPatologia=$codPatologia, CodOspedale=$codOspedale, CodiceRicovero=$codRicovero"; // Debug
        } catch (PDOException $e) {
            die("DB Error: " . $e->getMessage());
        }
    }
    
    function deletePatologiaRicoveroFromDb($codRicovero, $tableName, $conn) {
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
      
        function updateRicoveriInDb($codRicovero, $codOspedale, $data, $durata, $motivo, $costo, $conn) {
            try {
                $sql = "UPDATE Ricoveri SET CodOspedale = ?, Data = ?, Durata = ?, Motivo = ?, Costo = ? WHERE CodiceRicovero = ?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$codOspedale, $data, $durata, $motivo, $costo, $codRicovero]);
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
        $sql = "SELECT  codFiscale,nome, cognome, dataNascita, nasLuogo, indirizzo, COUNT(Ricoveri.CodiceRicovero) AS numRicoveri
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
