
// funzione per l'autocompletamento di ospedali e patologie

function autocompleteOspedaliPatologie(ospedali, patologie) {
    // Autocomplete per Ospedale
    $("#Ospedale").autocomplete({
        source: ospedali,
        minLength: 0,
        select: function(event, ui) {
            $("#Ospedale").val(ui.item.label);
            $("#CodOspedale").val(ui.item.value);
            return false;
        }
    }).focus(function() {
        $(this).autocomplete("search", "");
    });

    // Autocomplete per Motivo
    $("#MotivoDescrizione").autocomplete({
        source: patologie,
        minLength: 0,
        select: function(event, ui) {
            $("#MotivoDescrizione").val(ui.item.label);
            $("#Codice").val(ui.item.value);
            return false;
        }
    }).focus(function() {
        $(this).autocomplete("search", "");
    });
}

// funzione per l'autocompletamento di ospedali patologie e paziente

function autocompleteOspedaliPatologiePaziente(ospedali, patologie, paziente) {
    // Autocomplete per Ospedale
    $("#Ospedale").autocomplete({
        source: ospedali,
        minLength: 0,
        select: function(event, ui) {
            $("#Ospedale").val(ui.item.label);
            $("#CodOspedale").val(ui.item.value);
            return false;
        }
    }).focus(function() {
        $(this).autocomplete("search", "");
    });

    // Autocomplete per Motivo
    $("#MotivoDescrizione").autocomplete({
        source: patologie,
        minLength: 0,
        select: function(event, ui) {
            $("#MotivoDescrizione").val(ui.item.label);
            $("#Codice").val(ui.item.value);
            return false;
        }
    }).focus(function() {
        $(this).autocomplete("search", "");
    });

    // Autocomplete per Paziente
    $("#Paziente").autocomplete({
        source: paziente,
        minLength: 0,
        select: function(event, ui) {
            $("#Paziente").val(ui.item.label);
            $("#CodiceFiscale").val(ui.item.value);
            return false;
        }
    }).focus(function() {
        $(this).autocomplete("search", "");
    });
}

// funzione per verificare che i campi inseriti siano corretti di update

function verificaCampiUpdate() {
    var codOspedale = document.getElementById("CodOspedale").value;
    var paziente = document.getElementById("Paziente").value;
    var data = document.getElementById("Data").value;
    var durata = document.getElementById("Durata").value;
    var motivo = document.getElementById("Motivo").value;
    var costo = document.getElementById("Costo").value;
    var codice = document.getElementById("Codice").value;

    if (codOspedale === "" || paziente === "" || data === "" || durata === "" || motivo === "" || costo === "" || codice === "") {
        alert("Hai lasciato campi vuoti o inserito un valore di ospedale o patologia non corretto");
        return false;
    }

    if (isNaN(costo)) {
        alert("Valore non riconosciuto! usa il punto come separatore di cifre decimali");
        return false;
    }

    if (durata.includes(',') || durata.includes('.')) {
        alert('La durata deve essere un numero intero!');
        return false;
    }
    return true;
}

// funzione per verificare che i campi inseriti siano corretti di create

function verificaCampiCreate() {
    var codOspedale1 = document.getElementById("CodOspedale").value;
    var paziente1 = document.getElementById("Paziente").value;
    var data1 = document.getElementById("Data").value;
    var durata1 = document.getElementById("Durata").value;
    var motivo1 = document.getElementById("Motivo").value;
    var costo1 = document.getElementById("Costo").value;
    var codice1 = document.getElementById("Codice").value;

    if (codOspedale1 === "" || paziente1 === "" || data1 === "" || durata1 === "" || motivo1 === "" || costo1 === "" || codice1 === "") {
        alert("Hai lasciato campi vuoti o inserito un valore di paziente o ospedale o patologia non corretto");
        return false;
    }

    if (isNaN(costo1)) {
        alert("Valore non riconosciuto! usa il punto come separatore di cifre decimali");
        return false;
    }

    if (durata1.includes(',') || durata1.includes('.')) {
        alert('La durata deve essere un numero intero!');
        return false;
    }
    return true;
}

// funzione per confermare la cancellazione del record

function confirmDelete(codiceRicovero) {
    if (confirm("Sei sicuro di voler cancellare questo record?")) {
        document.getElementById('delete-form-' + codiceRicovero).submit();
    }
}