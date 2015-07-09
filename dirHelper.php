<?php
/**
 * Date: 2015-07-08
 */

function printLs($dirPath) {
    if ($handle = opendir($dirPath)) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != ".." && $entry != 'index.php') {
                $filePath = $dirPath . '/' . $entry;
                echo '<a href="' . $filePath . '">' . $entry . "</a>";
                echo '  (' . date('Y-m-d G:i', filemtime($filePath)) . ")\n";
            }
        }
        closedir($handle);
    }
}