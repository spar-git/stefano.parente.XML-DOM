<?php
$error_temp="";
$error_hum="";
$xmlString = "";
foreach ( file("meteo.xml") as $node ) {
	$xmlString .= trim($node);
}

$doc = new DOMDocument();
$doc->loadXML($xmlString);
    
if (!$doc->schemaValidate("meteo.xsd")) {
    echo "<p>Errore: Il documento XML non è valido secondo lo schema.</p>\n";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = $_POST["data"];
    $condizione = $_POST["condizione"];
    $temperatura = $_POST["temperatura"];
    $umidita = $_POST["umidita"];

    if ($temperatura < -90 || $temperatura > 60) {
        $error_temp = "Errore: La temperatura deve essere compresa tra -90 e 60 gradi Celsius.";
    } elseif ($umidita < 0 || $umidita > 100) {
        $error_hum = "Errore: L'umidità deve essere compresa tra 0% e 100%.";
    } elseif {

    }
    
    
    else {
        $newGiorno= $doc->createElement("giorno");
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
        $newLuna->appendChild($fase);
        $newLuna->appendChild($illuminazione);
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
        <option value="soleggiato">Soleggiato</option>
        <option value="parzialmente_nuvoloso">Parzialmente nuvoloso</option>
        <option value="pioggia_schiarite">Pioggia e schiarite</option>
        <option value="pioggia">Pioggia</option>
        <option value="temporale">Temporale</option>
    </select>

    <p>Temperatura (°C):</p>
    <input type="number" id="temperatura" name="temperatura" required>
    <?php echo '<span style= color:red;>' . $error_temp . '</span>'; ?>

    <p>Umidità (%):</p>
    <input type="number" id="umidita" name="umidita" required>
    <?php echo '<span style= color:red;>' . $error_hum . '</span>' ; ?>

    <p>Velocità vento (Km/h):</p>
    <input type="number" id="velocita" name="velocita" required>
    <?php echo '<span style= color:red;>' . $error_speed . '</span>' ; ?>

    <p>Direzione vento:</p>
    <select id="direzione" name="direzione" required>
        <option value="soleggiato">N</option>
        <option value="parzialmente_nuvoloso">NE</option>
        <option value="pioggia_schiarite">E</option>
        <option value="pioggia">SE</option>
        <option value="temporale">S</option>
        <option value="parzialmente_nuvoloso">SW</option>
        <option value="pioggia_schiarite">W</option>
        <option value="pioggia">NW</option>
    </select>    
    <?php echo '<span style= color:red;>' . $error_hum . '</span>' ; ?>

    <p>Probabilità pioggia (%):</p>
    <input type="number" id="probabilita" name="probabilita" required>
    <?php echo '<span style= color:red;>' . $error_speed . '</span>' ; ?>

    <p>Intensità pioggia (%):</p>
    <input type="number" id="intensita" name="intensita" step="0.1" min="0" required>
    <?php echo '<span style= color:red;>' . $error_intens . '</span>' ; ?>
    
    <button type="submit">Invia</button>

    </form>
</div>
</body>
</html>