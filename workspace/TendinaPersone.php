<?php
include_once 'gestioneDB.php';

function getPersone($conn)
{
    $crud = readPersoneCrud();
    $stmt = $conn->prepare($crud["sql"]);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$persone = getPersone($conn);
//echo json_encode($persone);
