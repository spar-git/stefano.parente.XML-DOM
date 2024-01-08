<?php
$xmlString = "";
foreach ( file("meteo.xml") as $node ) {
	$xmlString .= trim($node);
}

$doc = new DOMDocument();
$doc->loadXML($xmlString);
    
if (!$doc->schemaValidate("meteo.xsd")) {
    echo "<p>Errore: Il documento XML non è valido secondo lo schema.</p>\n";
}
$root = $doc->documentElement;
$elements = $root->childNodes;
$total_elements = $elements->length;

$current_item=($total_elements-7);
if (isset($_GET['elem']))
    $current_item = $_GET['elem'];

$giorno = $root->getElementsByTagName('giorno')->item($current_item);
$condizione = $giorno->getElementsByTagName('condizione')->item(0)->nodeValue;
if ($condizione == 'Soleggiato'){
    $img_src= "solegg.jpg";
    $img_alt= "Soleggiato";
}
if ($condizione == 'Parzialmente nuvoloso'){
    $img_src= "parz_nuvol.jpg";
    $img_alt= "Parzialmente nuvoloso";
}
if ($condizione == 'Pioggia e schiarite'){
    $img_src= "piog_schiar.jpg";
    $img_alt= "Pioggia e schiarite";
}
if ($condizione == 'Pioggia'){
    $img_src= "piogg.jpg";
    $img_alt= "Pioggia";
}
if ($condizione == 'Temporale'){
    $img_src= "tempor.jpg";
    $img_alt= "Temporale";
}

?>

<?xml version="1.0" encoding="ISO-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title>Previsioni meteo della provincia di Latina</title>
    <link rel="stylesheet" href="stilimeteo.css">
</head>

<body>

    <div class="town-container">
    <?php echo "<img src=\"$img_src\" alt=\"img_alt\">"; ?>
        <p class="town">METEO Latina</p>
    </div>

<table class="date-table">
  <tr>
    <?php for ($i=($total_elements-7); $i<$total_elements; $i++) {
        if ($i==$current_item)
            echo "<td style=\"background-color: blue;\">";
        else
            echo "<td>";
        $giorno = $root->getElementsByTagName('giorno')->item($i);
        $data = $giorno->getAttribute('data');
        echo "<a href=\"previsioniMeteo.php?elem=$i\">$data</a>";
    echo "</td>";
    } ?>
  </tr>
</table>

    <div class="box3">
    <?php

    $giorno = $root->getElementsByTagName('giorno')->item($current_item);
    $data = $giorno->getAttribute('data');
    echo "<h2>Previsioni per il giorno $data</h2>";
    $condizione = $giorno->getElementsByTagName('condizione')->item(0)->nodeValue;
    $temperatura = $giorno->getElementsByTagName('temperatura')->item(0)->nodeValue;
    $umidita = $giorno->getElementsByTagName('umidita')->item(0)->nodeValue;
    echo "<p>Condizione: $condizione</p>";
    echo "<p>Temperatura: $temperatura °C</p>";
    echo "<p>Umidità: $umidita%</p>";
    $vento = $giorno->getElementsByTagName('vento')->item(0);
    $velocitaVento = $vento->getAttribute('velocita');
    $direzioneVento = $vento->getAttribute('direzione');
    echo "<p>Vento: $velocitaVento km/h, Direzione: $direzioneVento</p>";
    $precipitazioni = $giorno->getElementsByTagName('precipitazioni')->item(0);
    $probabilitaPrecipitazioni = $precipitazioni->getAttribute('probabilita');
    $intensitaPrecipitazioni = $precipitazioni->getAttribute('intensita');
    echo "<p>Precipitazioni: Probabilità $probabilitaPrecipitazioni%, Intensità $intensitaPrecipitazioni mm</p>";
    $luna = $giorno->getElementsByTagName('luna')->item(0);
    $faseLuna = $luna->getElementsByTagName('fase')->item(0)->nodeValue;
    $illuminazioneLuna = $luna->getElementsByTagName('illuminazione')->item(0)->nodeValue;
    $tendenzaLuna = $luna->getElementsByTagName('tendenza')->item(0)->nodeValue;
    echo "<p>Luna: Fase $faseLuna, Illuminazione $illuminazioneLuna%, Tendenza $tendenzaLuna</p>";

    ?>
    </div>

</body> 
</html>