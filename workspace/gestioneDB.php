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
        $sql = "SELECT Ospedali.CodiceStruttura, Ospedali.DenominazioneStruttura, Ospedali.Indirizzo, Ospedali.Comune, Ospedali.DirettoreSanitario, Persone.nome,Persone.cognome
                FROM Ospedali
                JOIN Persone ON Persone.codFiscale = Ospedali.Direttoresanitario
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
    
        return $sql;
    }

    function readPersoneFromDb ($cf, $nome, $cognome, $dataNascita, $luogoNascita ,$indirizzo) : string {
        $sql = "SELECT  codFiscale,nome, cognome, dataNascita, nasLuogo, indirizzo
                FROM Persone
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
    
        return $sql;
    }
    
?>
