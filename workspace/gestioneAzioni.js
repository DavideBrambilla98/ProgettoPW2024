// Script per mantenere evidenziata la NAV in funzione della pagina attiva -------------------------------------------------

// Ottieni l'URL della pagina corrente
var url = window.location.href;

// Estrai il nome della pagina dal URL (aggiunto +1 altrimenti restituirebbe anche / dell'URL)
var pagina = url.substring(url.lastIndexOf('/') + 1);

// Ottieni il pulsante tramite il suo ID
var pulsanteHome = document.getElementById("homeNav");
var pulsantePersona = document.getElementById("persNav");
var pulsanteOspedale = document.getElementById("ospNav");
var pulsanteVirus = document.getElementById("patoNav");

// Cambia il colore del pulsante in base alla pagina
switch(pagina) {
    case "index.php":
        pulsanteHome.style.color = "#0047AB";
        break;
    case "cittadino.php":
        pulsantePersona.style.color = "#0047AB";
        break;
    case "ospedale.php":
        pulsanteOspedale.style.color = "#0047AB";
        break;
    case "patologia.php":
        pulsanteVirus.style.color = "#0047AB";
        break;
    default:
        pulsanteHome.style.color = "#fff";
        pulsantePersona.style.color = "#fff";
        pulsanteOspedale.style.color = "#fff";
        pulsanteVirus.style.color = "#fff";

}

// Script per evidenziare le righe della tabella -------------------------------------------------------------------------

// Aggiungi l'effetto hover alle righe della tabella
document.querySelectorAll('#tabella tr').forEach(function(row) {
    row.addEventListener('mouseover', function() {
      this.classList.add('hovered');
    });
    row.addEventListener('mouseout', function() {
      this.classList.remove('hovered');
    });
    
    // Modifica l'effetto di selezione al clic sulla riga
    row.addEventListener('click', function() {
      // Verifica se la riga ha già la classe 'selected'
      if (this.classList.contains('selected')) {
        // Se sì, rimuovi la classe 'selected'
        this.classList.remove('selected');
      } else {
        // Altrimenti, rimuovi la classe 'selected' da tutte le altre righe
        document.querySelectorAll('.selected').forEach(function(selectedRow) {
          selectedRow.classList.remove('selected');
        });
        // E aggiungi la classe 'selected' alla riga cliccata
        this.classList.add('selected');
      }
    });
  });
  