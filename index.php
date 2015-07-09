<?php

require_once 'simple_html_dom.php';
require_once 'HtmlReplacementsProvider.php';
require_once 'HtmlLuckyNumberProvider.php';

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

function updateFile($path, $newContent) {

    if (!file_exists($path)) {
        $file = fopen($path, 'w');
        fwrite($file, $newContent);
        fclose($file);
        $updated = true;
    }
    else {
        $file = fopen($path, 'r+');
        $oldContent = fread($file, filesize($path));
        if (strcmp($newContent, $oldContent) !== 0) { //different
            fseek($file, 0);
            fwrite($file, $newContent);
            $updated = true;
        }
        else { //same
            $updated = false;
        }
        fclose($file);
    }

    return $updated;
}

//$dom = file_get_html("http://lo1.olkusz.pl/aktualnosci/zast");
$dom = file_get_html("zast.html");

$replsProvider = new HtmlReplacementsProvider;
$repls = $replsProvider->getReplacements($dom);
$replsJson = json_encode($repls);

$lnProvider = new HtmlLuckyNumberProvider;
$ln = $lnProvider->getLuckyNumber($dom);
$lnJson = json_encode($ln);

if (!file_exists('ln')) {
    mkdir('ln');
}
if (!file_exists('replacements')) {
    mkdir('replacements');
}

$lnPath = 'ln/'.$ln['date'];
$replsPath = 'replacements/'.$repls['date'];

if (updateFile($lnPath, $lnJson)) {
    echo "UPDATED {$lnPath}<br>";
}
else {
    echo "OK {$lnPath}<br>";
}
if (updateFile($replsPath, $replsJson)) {
    echo "UPDATED {$replsPath}<br>";
}
else {
    echo "OK {$replsPath}<br>";
}