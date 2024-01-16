<?php
session_start();

//Impedisce l'accesso tramite url
if (isset($_SESSION['accesso_consentito'])) {
    if ($_SESSION['accesso_consentito']==false) {
        header("Location: admin_login.php");
        exit();
    }
} else {
    header("Location: previsioniMeteo.php");
    exit();
}

$message="";

$xmlString = "";
// Itera attraverso ogni riga del file "meteo.xml" rimuovendo spazi vuoti
foreach ( file("meteo.xml") as $node ) {
	$xmlString .= trim($node);
}

//Crea un nuovo oggetto DOMDocument e carica il contenuto XML nella DOMDocument
$doc = new DOMDocument();
if (!$doc->loadXML($xmlString)) {
    die ("Errore parsing del documento\n");
}

// Verifica se il documento XML non è valido secondo lo schema "meteo.xsd"
if (!$doc->schemaValidate("meteo.xsd")) {
    echo "<p>Errore: Il documento XML non è valido secondo lo schema.</p>\n";
}

$root = $doc->documentElement;

//Riceve tutti i dati inseriti dall'utente per comporre un nuovo elemento "giorno"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['invia'])) {
        $data = $_POST["data"];
        $condizione = $_POST["condizione"];
        $temperatura = $_POST["temperatura"];
        $umidita = $_POST["umidita"];
        $velocita = $_POST["velocita"];
        $direzione = $_POST["direzione"];
        $probabilita = $_POST["probabilita"];
        $intensita = $_POST["intensita"];
        $fase = $_POST["fase"];
        $illuminazione = $_POST["illuminazione"];
        $tendenza = $_POST["tendenza"];

        $newGiorno= $doc->createElement("giorno");
        $newGiorno->setAttribute("data", "$data");

        $newCondizione = $doc->createElement("condizione", "$condizione");
        
        $newTemperatura = $doc->createElement("temperatura", "$temperatura");

        $newUmidita = $doc->createElement("umidita", "$umidita");

        $newVento= $doc->createElement("vento");
        $newVento->setAttribute("velocita", "$velocita");
        $newVento->setAttribute("direzione", "$direzione");

        $newPrecipitazioni = $doc->createElement("precipitazioni");
        $newPrecipitazioni->setAttribute("probabilita", "$probabilita");
        $newPrecipitazioni->setAttribute("intensita", "$intensita");

        $newLuna = $doc->createElement("luna");
        $newFase = $doc->createElement("fase", "$fase");
        $newIlluminazione = $doc->createElement("illuminazione", "$illuminazione");
        $newTendenza = $doc->createElement("tendenza", "$tendenza");
        
        $newLuna->appendChild($newFase);
        $newLuna->appendChild($newIlluminazione);
        $newLuna->appendChild($newTendenza);

        $newGiorno->appendChild($newCondizione);
        $newGiorno->appendChild($newTemperatura);
        $newGiorno->appendChild($newUmidita);
        $newGiorno->appendChild($newVento);
        $newGiorno->appendChild($newPrecipitazioni);
        $newGiorno->appendChild($newLuna);

        $root->appendChild($newGiorno);
        
        //Percorso completo del file XML, incluso il nome del file ("meteo.xml")
        $path=dirname(__FILE__) . "/meteo.xml";
        // Salva il documento XML nell'indirizzo specificato da $path
        $doc->save($path);  

        $message="Le previsioni per il giorno " . $data . "<br>sono state caricate correttamente nel doc XML";
    }
    
    $elements = $root->childNodes;
    $total_elements = $elements->length;

    //Il controllo "$total_elements>7" può sembrare ridondante in quanto già effettuato nella riga 226 (se gli elementi sono <= 7 non compare il tasto "elimina"). 
    //E' servito ripeterlo qui in quanto, in caso di refresh della pagina, l'eliminazione si reiterava sugli elementi successivi, ignorando il controllo della riga 226
    //e di conseguenza causando problemi alla corretta visualizzazione delle date nel menù in "previsioniMeteo.php"
    if (isset($_POST['elimina'])&&$total_elements>7) {
        $ultimo = $root->lastChild;
        $root->removeChild($ultimo);

        $path=dirname(__FILE__) . "/meteo.xml";
        $doc->save($path);  

        $message="Eliminazione avvenuta con successo!";

    }
}


?>

<?xml version="1.0" encoding="ISO-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title>Area riservata</title>
    <link rel="stylesheet" href="stili_login.css">
</head>
<body>
<div>
    <a href="admin_login.php"><img src="img/back.png" alt="Back"></a>
</div>
<div class="container">
    <?php 
        //messaggio di avvenuto inserimento
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['invia'])) {
                echo '<h4 style="color:green; text-align: center;">' . $message . '</h4>';
                unset($_POST);
            }
        }
    ?>
    <form class="reserved-area" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <h2 style>Inserisci previsioni meteo</h2>
    
    <!-- I seguenti campi di input implementano regole restrittive affinchè non ci siano ambiguità ed errori di validazione del doc XML-->
    
    <p>Data (gg-mm-aaaa):</p>
    <input type="text" id="data" name="data" pattern="\d{2}-\d{2}-\d{4}" required>

    <p>Condizione:</p>
    <select id="condizione" name="condizione" required>
        <option>Soleggiato</option>
        <option>Parzialmente nuvoloso</option>
        <option>Pioggia e schiarite</option>
        <option>Pioggia</option>
        <option>Temporale</option>
    </select>

    <p>Temperatura (°C):</p>
    <input type="number" id="temperatura" name="temperatura" min="-90" max="60" required>

    <p>Umidità (%):</p>
    <input type="number" id="umidita" name="umidita" min="0" max="100" required>

    <p>Velocità vento (Km/h):</p>
    <input type="number" id="velocita" name="velocita" min="0" required>

    <p>Direzione vento:</p>
    <select id="direzione" name="direzione" required>
        <option>N</option>
        <option>NE</option>
        <option>E</option>
        <option>SE</option>
        <option>S</option>
        <option>SW</option>
        <option>W</option>
        <option>NW</option>
    </select>    

    <p>Probabilità pioggia (%):</p>
    <input type="number" id="probabilita" name="probabilita" min="0" max="100" required>

    <p>Intensità pioggia (mm):</p>
    <input type="number" id="intensita" name="intensita" step="0.1" min="0" required>
    
    <p>Fase lunare:</p>
    <select id="fase" name="fase" required>
        <option>Nuova Luna</option>
        <option>Primo crescente</option>
        <option>Primo quarto</option>
        <option>Gibbosa crescente</option>
        <option>Luna piena</option>
        <option>Gibbosa calante</option>
        <option>Ultimo calante</option>
    </select> 

    <p>Illuminazione lunare (%):</p>
    <input type="number" id="illuminazione" name="illuminazione" min="0" max="100" step="25" required>

    <p>Tendenza lunare:</p>
    <select id="tendenza" name="tendenza" required>
        <option>Crescente</option>
        <option>Calante</option>
    </select> 


    <button type="submit" name="invia">Invia</button>

    </form>
</div>

<div class="container">
    <?php 
        //Messaggio di avvenuta eliminazione
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['elimina'])) {
                echo '<h4 style="color:green; text-align: center;">' . $message . '</h4>';
                unset($_POST);
            }
        }
    ?>
    <form class="reserved-area" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <h2 style>Elimina ultima previsione meteo</h2>
        <p>Ultima data:</p>
        <input type="text" id="data" name="data" pattern="\d{2}-\d{2}-\d{4}" required readonly value="<?php $ultimo = $root->lastChild; $attributoData = $ultimo->getAttribute("data"); echo $attributoData?>">
        <?php 
        $elements = $root->childNodes;
        $total_elements = $elements->length;
        //Se nel doc XML ci sono meno di 8 elementi, non è possibile eliminare altri elementi, causa problemi alla corretta visualizzazione delle 7 date nel menù in "previsioniMeteo.php"
        if ($total_elements<=7)
            echo '<p style="color:red;">Devi aggiungere dei giorni prima di poterli eliminare <br>(numero di giorni minimo = 7)</p>';
        else
            echo '<button type="submit" name="elimina">Elimina</button>';
        ?>
    </form>
</div>

</body>
</html>