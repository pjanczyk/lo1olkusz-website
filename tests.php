<h3>Tests</h3>

<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

function printArray($array) {
    foreach($array as $el) {
        echo $el, '<br>';
    }
}

require_once 'simple_html_dom.php';
$dom = file_get_html("zast.html");
?>

<h4>Replacements</h4>

<?php

include 'HtmlReplacementsProvider.php';


$replsProvider = new HtmlReplacementsProvider;
$repls = $replsProvider->getReplacements($dom);

$errors = $replsProvider->getErrors();

if (count($errors) > 0) {
    echo 'Błędy:<br>';
    printArray($errors);
}
else {
    echo 'OK<br>';
}

$json = json_encode($repls);

$correctJson = '{"date":"2015-06-23","replacements":{"1a":{"5":"matematyka, mgr R. Dylewska"},"1b":{"1":"zaczyna o 9.55"},"1f":{"7":"gr. N6- j.niem, mgr T. Wajdzik"},"2a":{"1":"gr. N9- j.niem, mgr T. Wajdzik","8":"gr. N1- j.niem, mgr T. Wajdzik"},"2d":{"1":"gr. N9- j.niem, mgr T. Wajdzik","2":"gr. N4- j.niem, mgr T. Wajdzik"},"2e":{"1":"gr. N9- j.niem, mgr T. Wajdzik"}}}';

if (strcmp($json, $correctJson) !== 0) {
    echo 'Niepoprawny json:<br>';
    echo $json;
    echo '<br>zamiast:<br>';
    echo $correctJson;
}
else {
    echo $json;
    echo '<br>OK';
}

?>

<h4>LN</h4>

<?php

include 'HtmlLuckyNumberProvider.php';

$lnProvider = new HtmlLuckyNumberProvider;
$ln = $lnProvider->getLuckyNumber($dom);

$errors = $lnProvider->getErrors();

if (count($errors) > 0) {
    echo 'Błędy:<br>';
    printArray($errors);
}
else {
    echo 'OK<br>';
}

$json = json_encode($ln);

$correctJson = '{"date":"2015-06-10","number":8}';

if (strcmp($json, $correctJson) !== 0) {
    echo 'Niepoprawny json:<br>';
    echo $json;
    echo '<br>zamiast:<br>';
    echo $correctJson;
}
else {
    echo $json;
    echo '<br>OK';
}
