
<?php      
	function readRicoveriFromDb ($codRicovero, $codOsp,$nomOsp, $paziente, $dataRic, $durata, $motivo, $costo) : string {
        $sql = "SELECT Ricoveri.CodiceRicovero, Ricoveri.CodOspedale, Ospedali.DenominazioneStruttura, Ricoveri.Paziente,Persone.nome,Persone.cognome, Ricoveri.Data, Ricoveri.Durata, Ricoveri.Motivo, Ricoveri.Costo
                FROM Ricoveri
                JOIN Ospedali ON Ricoveri.CodOspedale = Ospedali.CodiceStruttura
                JOIN Persone ON Ricoveri.Paziente = Persone.codFiscale
                WHERE 1=1";
                
        if ($codRicovero != "")
            $sql .= " AND Ricoveri.CodiceRicovero LIKE  :codiceRicovero";
        if ($codOsp != "")
            $sql .= " AND Ricoveri.CodOspedale LIKE :codiceOspedale";
        if ($nomOsp != "")
            $sql .= " AND Ospedali.DenominazioneStruttura LIKE :nomeOspedale";
        if ($paziente != "")
            $sql .= " AND Ricoveri.Paziente LIKE  :paziente";
        if ($dataRic != "")
             $sql .= " AND Ricoveri.Data LIKE  :dataRic";
        if ($durata != "")
            $sql .= " AND Ricoveri.Durata LIKE  :durata";
        if ($motivo != "")
            $sql .= " AND Ricoveri.Motivo LIKE  :motivo";
         if ($costo != "")
            $sql .= " AND Ricoveri.Costo LIKE  :costo";

        return $sql;
    }
    
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
            $sql .= " AND DirettoreSanitario LIKE :direttoreSanitario";

        $sql .= " GROUP BY Ospedali.CodiceStruttura";
        
        return $sql;
    }
    
    function readRicoveriFromDb ($codStruttura, $codRicovero, $paziente, $data, $durata, $motivo, $costo) : string {
        $sql = "SELECT CodiceStruttura, CodRic, Paziente, Data, Durata, Motivo, Costo FROM Ricoveri WHERE 1=1";

        if ($codStruttura != "")
            $sql .= " AND CodiceStruttura LIKE :fCodOsp";
        if ($codRicovero != "")
            $sql .= " AND CodRic LIKE :fCodRic";
        if ($paziente != "")
            $sql .= " AND Paziente LIKE :fpaziente";
        if ($data != "")
            $sql .= " AND Data LIKE :fdata";
        if ($durata != "")
            $sql .= " AND Durata LIKE :fDurata";
        if ($motivo != "")
            $sql .= " AND Motivo LIKE :fMotivo";
        if ($costo != "")
            $sql .= " AND Costo LIKE :fCosto";
            
            return $sql;
        }



        function createRicoveriInDb($codStruttura, $codRicovero, $paziente, $data, $durata, $motivo, $costo){
            global $conn;
            $sql = 'INSERT INTO Ricoveri (CodiceStruttura, CodRic, Paziente, Data, Durata, Motivo, Costo) VALUES (:CodiceStruttura, :CodRic, :Paziente, :Data, :Durata, :Motivo, :Costo)';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':CodiceStruttura', $codStruttura);
            $stmt->bindParam(':CodRic', $codRicovero);
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
            $sql = "UPDATE Ricoveri SET Paziente = ?, Data = ?, Durata = ?, Motivo = ?, Costo = ? WHERE CodRic = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$paziente, $data, $durata, $motivo, $costo, $codRicovero]);
        } catch (PDOException $e) {
            die("DB Error: " . $e->getMessage());
        }
    }
    
    

    function deleteRicoveriFromDb($codRicovero, $tableName, $conn) {
        $sql = "DELETE FROM Ricoveri WHERE CodRic = :fCodRic";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':fCodRic', $codRicovero);
        $stmt->execute();
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
