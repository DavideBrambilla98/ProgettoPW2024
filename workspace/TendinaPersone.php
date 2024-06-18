<?php
include_once 'gestioneDB.php';

function getPersone($conn) {
    $sql = readPersoneCrud("", "","");
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$persone = getPersone($conn);
echo json_encode($persone);
?>