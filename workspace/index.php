<!DOCTYPE html>
<html>
    <head>
        <meta charset='utf-8'>
        <meta http-equiv='X-UA-Compatible' content='IE=edge'>
        <title>Page Title</title>
        <link rel="stylesheet" href="style.css">
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <link rel='stylesheet' type='text/css' media='screen' href='main.css'>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <script src='main.js'></script>
    </head>

    <body>
        <?php
            include 'header.html';	
            include 'navigation.html';
            include 'dbManager.php';
        ?>

        <div id="research">      
            <form name="researchForm" method="POST">
                <input id="paziente" name="paziente" type="text" placeholder="CSSN"/>
                <input id="ospedale" name="ospedale" type="text" placeholder="nome ospedale"/>
                <input id="codiceRicovero" name="codiceRicovero" type="text" placeholder="codice ricovero"/>
                <button type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
        <div id="results">
            
    <?php	
        include 'ConnessioneDB.php';
        include 'footer.html';
    ?>
</html>
