<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title>Previsioni meteo della provincia di Latina</title>
</head>

<body>
<?php

$xmlString = "";
foreach ( file("meteo.xml") as $node ) {
	$xmlString .= trim($node);
}

$doc = new DOMDocument();
if ($doc->loadXML($xmlString)) {
    if ($doc->schemaValidate("meteo.xsd")) {
        echo "<p>Il documento XML è valido secondo lo schema.</p>\n";
    } else {
        echo "<p>Errore: Il documento XML non è valido secondo lo schema.</p>\n";
    }
} else {
    echo "<p>Errore durante il parsing del documento XML.</p>\n";
}

$root = $doc->documentElement;
$elementi = $root->childNodes;

$giorno = $root->getElementsByTagName('giorno')->item(0);

$data = $giorno->getAttribute('data');
echo "<h2>Previsioni per il giorno $data</h2>";

$condizione = $giorno->getElementsByTagName('condizione')->item(0)->nodeValue;
$temperatura = $giorno->getElementsByTagName('temperatura')->item(0)->nodeValue;
$umidita = $giorno->getElementsByTagName('umidità')->item(0)->nodeValue;

echo "<p>Condizione: $condizione</p>";
echo "<p>Temperatura: $temperatura °C</p>";
echo "<p>Umidità: $umidita%</p>";

$vento = $giorno->getElementsByTagName('vento')->item(0);
$velocitaVento = $vento->getAttribute('velocità');
$direzioneVento = $vento->getAttribute('direzione');
echo "<p>Vento: $velocitaVento km/h, Direzione: $direzioneVento</p>";

$precipitazioni = $giorno->getElementsByTagName('precipitazioni')->item(0);
$probabilitaPrecipitazioni = $precipitazioni->getAttribute('probabilità');
$intensitaPrecipitazioni = $precipitazioni->getAttribute('intensità');
echo "<p>Precipitazioni: Probabilità $probabilitaPrecipitazioni%, Intensità $intensitaPrecipitazioni mm/h</p>";

$luna = $giorno->getElementsByTagName('luna')->item(0);
$faseLuna = $luna->getElementsByTagName('fase')->item(0)->nodeValue;
$illuminazioneLuna = $luna->getElementsByTagName('illuminazione')->item(0)->nodeValue;
$tendenzaLuna = $luna->getElementsByTagName('tendenza')->item(0)->nodeValue;
echo "<p>Luna: Fase $faseLuna, Illuminazione $illuminazioneLuna%, Tendenza $tendenzaLuna</p>";

echo "<hr>";


?>
</body> 
</html>