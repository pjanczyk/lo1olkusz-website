<?php
/**
 * Date: 2015-07-08
 */

function printLs($dirPath, $urlPath = null) {
    if ($urlPath === null) {
        $urlPath = $dirPath;
    }

    $i = 1;

    if ($handle = opendir($dirPath)): ?>
        <table class="table table-condensed" style="width: auto">
            <thead>
            <tr>
                <th>#</th>
                <th>file name</th>
                <th>last modified</th>
            </tr>
            </thead>
            <tbody>
            <?php while (false !== ($fileName = readdir($handle))):
                if ($fileName != "." && $fileName != ".."):
                    $filePath = $dirPath . '/' . $fileName;
                    $linkPath = $urlPath . '/' . $fileName ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><a href="<?= $linkPath ?>"><?= $fileName ?></a></td>
                        <td><?= date('Y-m-d G:i', filemtime($filePath)) ?></td>
                    </tr>
                <?php endif;
            endwhile ?>
            </tbody>
        </table>
        <?php closedir($handle);
    endif;
}

?>