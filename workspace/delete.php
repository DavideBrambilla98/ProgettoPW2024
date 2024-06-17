<?php
    session_start();
    include 'gestioneDB.php';
    include 'ConnessioneDB.php';
if (isset($_POST['delete'])) {
        $codRicovero = $_POST['CodiceRicovero'];

        if (!empty($codRicovero)) {
            try {
                deleteRicoveriFromDb($codRicovero, 'Ricoveri', $conn);
                deletePatologiaRicoveroFromDb($codRicovero, 'PatologiaRicovero', $conn);
                $_SESSION['flash_message'] = 'Ricovero eliminato correttamente!';
                header('Location: index.php');
                exit;
            } catch (Exception $e) {
                // Log the error or handle it as appropriate
                echo "Errore durante la cancellazione del record: " . $e->getMessage();
            }
        } else {
            echo "Codice ricovero non fornito.";
        }
    } else {
        echo "Richiesta non valida.";
    }
?>