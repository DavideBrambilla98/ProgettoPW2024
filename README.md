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
