<?php      
	    function readRicoveriFromDb ($codRicovero, $codOsp, $paziente, $dataRic, $durata, $motivo, $costo) : string {
            $sql = "SELECT CodiceRicovero, CodOspedale, Paziente, Data, Durata, Motivo, Costo FROM Ricoveri WHERE 1=1";
                
            if ($codRicovero != "")
                $sql .= " AND CodiceRicovero LIKE  :codiceRicovero";
            if ($codOsp != "")
                $sql .= " AND CodOspedale LIKE :codiceOspedale";
            if ($paziente != "")
                $sql .= " AND Paziente LIKE  :paziente";
            if ($dataRic != "")
                $sql .= " AND Data LIKE  :dataRic";
            if ($durata != "")
                $sql .= " AND Durata LIKE  :durata";
            if ($motivo != "")
                $sql .= " AND Motivo LIKE  :motivo";
            if ($costo != "")
                $sql .= " AND Costo LIKE  :costo";
    
            return $sql;
        }
    
    function readPatologieFromDb ($cod, $nome, $criticita, $cronica, $mortale) : string {
        $sql = "SELECT Codice, Nome, Criticita, Cronica, Mortale FROM Patologie WHERE 1=1";
            
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
        $sql = "SELECT CodiceStruttura, DenominazioneStruttura, Indirizzo, Comune, DirettoreSanitario FROM Ospedali WHERE 1=1";
            
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
        $sql = "SELECT  codFiscale,nome, cognome, dataNascita, nasLuogo, indirizzo FROM Persone WHERE 1=1";
            
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
