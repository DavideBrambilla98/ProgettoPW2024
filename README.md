# Servizio Sanitario

Repository del progetto di *programmazione web*.

## Consegna:
### Base di dati di una Regione per la gestione delle informazioni sui ricoveri ospedalieri

I cittadini sono noti a priori alla Regione, in quanto hanno il codice del Servizio Sanitario Nazionale.  
Quindi, un cittadino è identificato dal codice suddetto ed è caratterizzato dagli usuali dati anagrafici.  
Anche gli ospedali sono noti a priori alla Regione: un codice li identifica e sono poi caratterizzati dal nome, dalla città, dall’indirizzo e dal nome del Direttore Sanitario.  
Una persona può essere al massimo Direttore Sanitario di un solo ospedale.  
La Regione riceve le informazioni sui ricoveri: il ricovero è identificato da un codice univoco per l’ospedale nel quale viene effettuato,
ed è caratterizzato dalla data di inizio, dai giorni, dal motivo e dal costo, nonché dal cittadino (paziente) ricoverato.  
I ricoveri avvengono per curare una o più patologie, che sono note a propri: ogni patologia è identificata dal codice,
ed è caratterizzata dal nome, e da un livello di criticità (tipicamente, un numero).  
In particolare, si vogliono gestire due sottoinsiemi: quello delle patologie mortali e quello delle patologie croniche
(i due sottoinsiemi potrebbero essere non disgiunti e sicuramente non sono esaustivi).

### Gestione delle molteplicità

Le query di ricerca su una tabella devono prevedere diversi criteri (dipende dalla tabella). In generale:  
- se una tabella A è legata da una relazione (0:1) o (1:1) ad un’altra tabella B sarebbe il caso di mostrare insieme alla riga di A i dati caratteristici di B (oltre all’ID).
  Es.: nel DB1, insieme ai dati di un’ Opera mostrare il nome dell’ Autore
- se una tabella A è legata da una reazione (0:n) o (1:n) ad un’altra tabella B allora mostrate il numero di entità di B legate ad ogni riga di A.
  Es.: nel DB1, insieme ai dati di un Autore mostrare il numero di Opere
  Fate le vostre considerazioni caso per caso

## Struttura del DB:

<img width="649" alt="StrutturaDB1" src="https://github.com/DavideBrambilla98/ProgettoPW2024/assets/145765934/2f42509f-1882-4c23-8095-6af598d8211c">


<img width="728" alt="StrutturaDB2" src="https://github.com/DavideBrambilla98/ProgettoPW2024/assets/145765934/8223b76f-e04c-4c91-b0a6-4134f29f9231">

## Struttura interfaccia:

<img width="729" alt="Interfaccia" src="https://github.com/DavideBrambilla98/ProgettoPW2024/assets/145765934/9b0e086a-49de-4b85-bae4-1d17890baaea">

## Dettagli:

- Progetto: 124
- DB di riferimento: Servizio Sanitario (Ex 3)
- Tabella per CRUD: Ricovero
- Interfaccia: Interfaccia 4
- Palette: Arancione
