<?php
include_once 'gestioneDB.php';

function getOspedali($conn) {
    $sql = readOspedaliCrud("", "");
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$ospedali = getOspedali($conn);
echo json_encode($ospedali);
?>