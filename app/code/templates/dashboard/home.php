<?php
/** @var \pjanczyk\lo1olkusz\Model\News $news */
/** @var int $now */
?>

<?php include 'templates/dashboard/header.php' ?>

<?php
function map($item, &$days) {
    $date = $item->date;
    if (!isset($days[$date])) {
        $days[$date] = [];
    }
    $days[$date][] = $item;
}

$daily = [];
foreach ($news->luckyNumbers as $ln) {
    map($ln, $daily);
}
$dailyReplacements = [];
foreach ($news->replacements as $r) {
    map($r, $daily);
}

?>

<div class="page-header">
    <h1>Strona główna</h1>
</div>

<span class="last-modified">wyniki z <?=formatTimestamp($now)?></span>
<span class="pull-right">wersja aplikacji: <?=$news->version?></span>
<br>

<?php foreach ($daily as $day=>$daily): ?>
    <h3><?=$day?></h3>
    <?php foreach ($daily as $item): ?>
        <?php if ($item instanceof \pjanczyk\lo1olkusz\Model\LuckyNumber): ?>
            <div class="pnl">
                <div class="pnl-header">
                    Szczęśliwy numerek: <b><?=$item->value?></b>
                    <span class="last-modified pull-right"><?=formatTimestamp($item->lastModified)?></span>
                </div>
            </div>
        <?php else: ?>
            <div class="pnl">
                <div class="pnl-header">
                    Zastępstwo
                    <span class="class-name"><b><?=$item->class?></b></span>
                    <span class="last-modified pull-right"><?=formatTimestamp($item->lastModified)?></span>
                </div>
                <?php foreach($item->value as $hour => $text): ?>
                    <div class="list-item">
                        <div class="replacement-hour"><?=$hour?>.</div>
                        <div class="replacement-subject"><?=$text?></div>
                    </div>
                <?php endforeach ?>
            </div>
        <?php endif ?>
    <?php endforeach ?>
<?php endforeach ?>

<?php include 'templates/dashboard/footer.php';