<?php
/** @var \pjanczyk\lo1olkusz\Model\News $news */
/** @var int $now */
?>

<?php include 'templates/dashboard/header.php' ?>

<div class="page-header">
    <h1>Strona główna</h1>
</div>
<p>wyniki od <?=$now?></p>

<h2>Szczęśliwe numerki</h2>
<table class="table table-responsive">
    <?php foreach ($news->luckyNumbers as $ln): ?>
        <tr>
            <td><?=$ln->date?></td>
            <td><?=$ln->value?></td>
            <td class="last-modified"><?=formatTimestamp($ln->lastModified)?></td>
        </tr>
    <?php endforeach ?>
</table>

<h2>Zastępstwa</h2>
<table class="table table-responsive">
    <?php foreach ($news->replacements as $r): ?>
        <tr>
            <td><?=$r->date?></td>
            <td><?=$r->class?></td>
            <td>
                <?php foreach($r->value as $hour => $text): ?>
                    <?=$hour?>. <?=$text?><br/>
                <?php endforeach ?>
            </td>
            <td><?=formatTimestamp($r->lastModified)?></td>
        </tr>
    <?php endforeach ?>
</table>

<h2>Plany lekcji</h2>
<table class="table table-responsive">
    <?php foreach ($news->timetables as $t): ?>
        <tr>
            <td><?=$t->class?></td>
            <td><pre><?=$t->value?></pre></td>
            <td><?=formatTimestamp($t->lastModified)?></td>
        </tr>
    <?php endforeach ?>
</table>

<h2>Wersja aplikacji: <?=$news->version?></h2>

<?php include 'templates/dashboard/footer.php';