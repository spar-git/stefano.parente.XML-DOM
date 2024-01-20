Homework XML-DOM di Stefano Parente matricola 1574482. L'indirizzo del mio repository Github è 
https://github.com/spar-git/stefano.parente.XML-DOM

Questo homework rappresenta la simulazione di un servizio metereologico online. In particolare, si tratta di un'interfaccia web dalla quale gli utenti possono
ottenere informazioni sui dati meteorologici dell'intera settimana. Tali informazioni sono estratte dal file "meteo.xml" il quale segue le regole restrittive 
stabilite dalla sua DTD o dal suo Schema. Oltre all'interfaccia principale pubblica, è presente un'area riservata accessibile esclusivamente all'amministratore. 
Quest'ultimo ha la possibilità di inserire nuovi dati XML ed eliminarne altri tramite apposite form. Queste funzionalità permettono di semplificare le 
operazioni di gestione senza la necessità di accedere direttamente al codice sorgente XML. Sono state implementate diverse regole di stile al fine di migliorare 
l'impaginazione e rendere l'interfaccia più gradevole visivamente. Di seguito sono riportate le informazioni più specifiche dei singoli file presenti nella 
directory, ma è anche possibile visualizzare i commenti presenti nei vari codici sorgente.

- meteo.xml. Questo file XML rappresenta un documento strutturato per le previsioni meteorologiche, con una radice denominata <previsioni> appartenente allo 
spazio dei nomi http://www.xmldom.it/spazidinomi/meteo/. All'interno di questa radice, sono presenti gli elementi <giorno>, ciascuno rappresentante una 
previsione per un giorno specifico. Ogni elemento <giorno> è caratterizzato da un attributo "data" che indica la data nel formato "DD-MM-YYYY". All'interno di 
ciascun giorno, sono inclusi diversi sottoelementi che forniscono informazioni dettagliate sulla condizione meteorologica: <condizione>, che descrive la 
condizione meteorologica prevista per il giorno, <temperatura>, che Indica la temperatura prevista, <umidita>, che rappresenta il livello di umidità atteso,
<vento>, che contiene informazioni sulla velocità e direzione del vento con i suoi attributi "velocita" e "direzione", <precipitazioni>, che fornisce dettagli 
sulla probabilità e l'intensità ddelle precipitazioni con i suoi attributi "probabilita" e "intensita" ed infine <luna>, che contiene informazioni sulla fase,
l'illiminazione e la tendenza lunare con i suoi elementi figli <fase>, <illuminazione> e <tendenza>.

-meteo.dtd. La DTD (Document Type Definition) fornita definisce la struttura e le regole che il documento XML di previsioni meteorologiche deve seguire.
Questa è generalmente considerata meno espressiva e meno potente rispetto agli XML Schema in quanto non permette la dichiarazione vincoli avanzati. D'altronde
è stato comunque possibile stabilire la non opzionalità degli attributi (#REQUIRED), stabilirne il tipo di contenuto (PCDATA) e l'enumerazione
per la direzione del vento.

- meteo.xsd. Questo XML Schema utilizza i namespace per evitare conflitti di nomi tra gli elementi o attributi di documenti XML diversi. Il namespace 
personalizzato "wth" è definito come "http://www.xmldom.it/spazidinomi/meteo/". Questo file XSD stabilisce una struttura standard per la rappresentazione di 
dati meteorologici in formato XML ed introduce tipi di dati complessi e semplici con restrizioni per garantire la coerenza e la validità dei dati. Ad esempio, 
vengono specificati limiti per i valori di temperatura, umidità, velocità del vento, probabilità e intensità di precipitazioni, e vengono definiti tipi enumerati
per rappresentare la direzione del vento, la fase, l'illuminazione e la tendenza lunare. La validazione del file XML basato su questo schema sarà trattata nel 
codice php di "previsioniMeteo.php", in cui il parser XML analizzerà il documento XML, lo confronterà con lo schema specificato e segnalerà eventuali errori di 
non conformità.

- previsioniMeteo.php. E' la pagina pubblica, accessibile a chiunque, e presenta un'immagine di intestazione che dinamicamente rappresenta la condizione 
meteorologica della data selezionata. Per impostazione predefinita, la data selezionata è la prima, ipotizzata come giorno corrente, ma gli utenti possono 
navigare nel menu delle date per accedere alle previsioni dei giorni successivi. Le date visualizzate corrispondono alle ultime sette nel file XML e ogni 
nuovo inserimento da parte dell'amministratore farà avanzare questa lista di una posizione. Nella sezione sottostante sono visibili i dati effettivi relativi a: 
condizioni meteorologiche, temperatura, umidità, vento, precipitazioni e luna. Nella pagina è presente anche un pulsante di accesso all'area riservata,
dalla quale l'amministratore può gestire il contenuto della pagina pubblica, inserendo o eliminando elementi XML.

- admin_login.php e reserved_area.php. Accedendo all'area riservata verranno richieste le credenziali di accesso, affinchè solo l'amministratore possa accedervi.
Il controllo è stato riservato al codice php, tuttavia, in un contesto non simulato, sarebbe opportuno affidare questa gestione a un database, poiché rappresenta
una pratica più sicura e scalabile. Una volta all'interno dell'area riservata, sono presenti due form: una dedicata all'inserimento di nuove previsioni 
meteorologiche e l'altra per l'eliminazione di previsioni esistenti. Per quanto riguarda l'inserimento, sono stati aggiunti attributi html alla form al fine di 
prevenire l'inserimento di valori non conformi alle regole stabilite dalla DTD o dall'XML Schema. Nella form di eliminazione è stato necessario implementare il 
controllo dei giorni rimanenti. Se si tentasse di eliminare troppe previsioni meteorologiche, riducendone il numero nel file XML a meno di 7, si verificherebbe
un errore nella pagina "previsioniMeteo.php", poiché è prevista la visualizzazione di 7 date nel menu. Per questo motivo, raggiunto il minimo, apparià un avviso
in sostituzione del pulsante di eliminazione di nuovi elementi.   Per accedere come admin: >>> username: stefano | password: pass123 <<<  
