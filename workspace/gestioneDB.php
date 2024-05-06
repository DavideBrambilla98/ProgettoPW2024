<?php      
	function readPatologieFromDb ($cod, $nome, $criticità, $cronica, $mortale) : string {
        $sql = "SELECT Codice, Nome, Criticità, Cronica, Mortale FROM Patologie WHERE 1=1";
            
        if ($cod != "")
            $sql .= " AND Codice = :cod";
        if ($nome != "")
            $sql .= " AND Nome LIKE :nome";
        if ($criticità != "")
            $sql .= " AND Criticità = :criticità";
        if ($cronica != "")
            $sql .= " AND Cronica = :cronica";
        if ($mortale != "")
            $sql .= " AND Mortale = :mortale";

        return $sql;
    
	}
?>
