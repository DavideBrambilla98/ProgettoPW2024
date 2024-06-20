<?php
include_once 'gestioneDB.php';


function getPatologia($conn) {
    $sql = readPatologieCrud("", "");
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$patologie = getPatologia($conn);
//echo json_encode($motivi);
?>