<?php
$xmlString = "";
foreach ( file("meteo.xml") as $node ) {
	$xmlString .= trim($node);
}

$doc = new DOMDocument();
if (!$doc->loadXML($xmlString)) {
    die ("Errore parsing del documento\n");
}
    
if (!$doc->schemaValidate("meteo.xsd")) {
    echo "<p>Errore: Il documento XML non è valido secondo lo schema.</p>\n";
}

$root = $doc->documentElement;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    $path=dirname(__FILE__) . "/meteo.xml";
    $doc->save($path);  
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

<div class="container">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

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
    <input type="number" id="illuminazione" name="illuminazione" min="0" max="100" required>

    <p>Tendenza lunare:</p>
    <select id="tendenza" name="tendenza" required>
        <option>Crescente</option>
        <option>Calante</option>
    </select> 


    <button type="submit">Invia</button>

    </form>
</div>
</body>
</html>