<?php
//necessario per impedire l'accesso all'area riservata tramite url
session_start(); 
$_SESSION['accesso_consentito'] = false;

$xmlString = "";
// Itera attraverso ogni riga del file "meteo.xml" rimuovendo spazi vuoti
foreach ( file("meteo.xml") as $node ) {
	$xmlString .= trim($node);
}

//Crea un nuovo oggetto DOMDocument e carica il contenuto XML nella DOMDocument
$doc = new DOMDocument();
$doc->loadXML($xmlString);

// Verifica se il documento XML non è valido secondo lo schema "meteo.xsd"
if (!$doc->schemaValidate("meteo.xsd")) {
    echo "<p>Errore: Il documento XML non è valido secondo lo schema.</p>\n";
}

//Viene presa nota del numero di elementi "giorno" presenti nel fil xml, ci servirà dopo
$root = $doc->documentElement;
$elements = $root->childNodes;
$total_elements = $elements->length;

$current_item=($total_elements-7);      //E' la prima data, selezionata per dafault...
if (isset($_GET['elem']))               
    $current_item = $_GET['elem'];      //...a meno che l'utente non ne abbia selezionata un'altra (query string riga 84)

//In base alla condizione meteo del giorno selezionato, cambia l'immagine di intestazione
$giorno = $root->getElementsByTagName('giorno')->item($current_item);
$condizione = $giorno->getElementsByTagName('condizione')->item(0)->nodeValue;
if ($condizione == 'Soleggiato'){
    $img_src= "img/solegg.jpg";
    $img_alt= "Soleggiato";
}
if ($condizione == 'Parzialmente nuvoloso'){
    $img_src= "img/parz_nuvol.jpg";
    $img_alt= "Parzialmente nuvoloso";
}
if ($condizione == 'Pioggia e schiarite'){
    $img_src= "img/piog_schiar.jpg";
    $img_alt= "Pioggia e schiarite";
}
if ($condizione == 'Pioggia'){
    $img_src= "img/piogg.jpg";
    $img_alt= "Pioggia";
}
if ($condizione == 'Temporale'){
    $img_src= "img/tempor.jpg";
    $img_alt= "Temporale";
}

?>

<?xml version="1.0" encoding="ISO-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title>Previsioni meteo della provincia di Latina</title>
    <link rel="stylesheet" href="style/stilimeteo.css">
</head>

<body>

    <div class="top-container">
    <?php echo "<img src=\"$img_src\" alt=\"img_alt\">"; ?>
        <p class="town">METEO Latina</p>
        <a href="admin_login.php" class="button-admin">Area riservata</a>
    </div>

<table class="date-table">
  <tr>
    <!-- Visualizza le ULTIME 7 date presenti nel file XML -->
    <?php for ($i=($total_elements-7); $i<$total_elements; $i++) {
        if ($i==$current_item) 
            echo "<td style=\"background-color: blue;\">"; //Sfondo blu per l'elemento selezionato
        else
            echo "<td>";                    //...altrimenti niente sfondo blu
        $giorno = $root->getElementsByTagName('giorno')->item($i);
        $data = $giorno->getAttribute('data');
        echo "<a href=\"previsioniMeteo.php?elem=$i\">$data</a>"; //Nel link viene iniettata una query string con il "numero item" corrispondente a quella data, verrà inviata quando l'utente farà click sulla data di suo interesse
        echo "</td>";
    } ?>
  </tr>
</table>

<div class="raws-container">
    <?php

    $giorno = $root->getElementsByTagName('giorno')->item($current_item);
    $data = $giorno->getAttribute('data');
    
    $condizione = $giorno->getElementsByTagName('condizione')->item(0)->nodeValue;

    $temperatura = $giorno->getElementsByTagName('temperatura')->item(0)->nodeValue;

    $umidita = $giorno->getElementsByTagName('umidita')->item(0)->nodeValue;

    $vento = $giorno->getElementsByTagName('vento')->item(0);
    $velocitaVento = $vento->getAttribute('velocita');
    $direzioneVento = $vento->getAttribute('direzione');

    $precipitazioni = $giorno->getElementsByTagName('precipitazioni')->item(0);
    $probabilitaPrecipitazioni = $precipitazioni->getAttribute('probabilita');
    $intensitaPrecipitazioni = $precipitazioni->getAttribute('intensita');

    $luna = $giorno->getElementsByTagName('luna')->item(0);
    $faseLuna = $luna->getElementsByTagName('fase')->item(0)->nodeValue;
    $illuminazioneLuna = $luna->getElementsByTagName('illuminazione')->item(0)->nodeValue;
    $tendenzaLuna = $luna->getElementsByTagName('tendenza')->item(0)->nodeValue;

    echo "<h2>Previsioni per il giorno $data</h2>";
    // prima riga 
    echo "<div class=\"raws-grey\">";
    echo "<div style=\"padding: 20px;\">Condizione:</div>";
    echo "<div style=\"padding: 20px;\">$condizione</div>";
    echo "</div>";
    //seconda riga 
    echo "<div class=\"raws-white\">";
    echo "<div style=\"padding: 20px;\">Temperatura:</div>";
    echo "<div style=\"padding: 20px;\">$temperatura °C</div>";
    echo "</div>";
    //terza riga
    echo "<div class=\"raws-grey\">";
    echo "<div style=\"padding: 20px;\">Umidità:</div>";
    echo "<div style=\"padding: 20px;\">$umidita%</div>";
    echo "</div>";
    //quarta riga
    echo "<div class=\"raws-white\">";
    echo "<div style=\"padding: 20px;\">Velocità vento:</div>";
    echo "<div style=\"padding: 20px;\">$velocitaVento km/h</div>";
    echo "</div>";
    //quinta riga
    echo "<div class=\"raws-grey\">";
    echo "<div style=\"padding: 20px;\">Direzione vento:</div>";
    echo "<div style=\"padding: 20px;\">$direzioneVento</div>";
    echo "</div>";
    //sesta riga
    echo "<div class=\"raws-white\">";
    echo "<div style=\"padding: 20px;\">Probabilità pecipitazioni:</div>";
    echo "<div style=\"padding: 20px;\">$probabilitaPrecipitazioni%</div>";
    echo "</div>";
    //settima riga
    echo "<div class=\"raws-grey\">";
    echo "<div style=\"padding: 20px;\">Intensità precipitazioni:</div>";
    echo "<div style=\"padding: 20px;\">$intensitaPrecipitazioni mm</div>";
    echo "</div>";
    //ottava riga
    echo "<div class=\"raws-white\">";
    echo "<div style=\"padding: 20px;\">Fase lunare:</div>";
    echo "<div style=\"padding: 20px;\">$faseLuna</div>";
    echo "</div>";
    //nona riga
    echo "<div class=\"raws-grey\">";
    echo "<div style=\"padding: 20px;\">Illuminazione lunare:</div>";
    echo "<div style=\"padding: 20px;\">$illuminazioneLuna%</div>";
    echo "</div>";
    //decima riga
    echo "<div class=\"raws-white\">";
    echo "<div style=\"padding: 20px;\">Tendenza lunare:</div>";
    echo "<div style=\"padding: 20px;\">$tendenzaLuna</div>";
    echo "</div>";

    ?>
</div>

</body> 
</html>