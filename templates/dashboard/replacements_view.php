<?php /** @var \pjanczyk\lo1olkusz\Model\Replacements $replacements */ ?>

<?php include 'templates/dashboard/header.php' ?>

<div class="page-header">
    <h1>
        <a href="/dashboard/replacements">Zastępstwa</a>
    </h1>
</div>

<div class="pnl">
    <div class="pnl-header">
        <?=$replacements->date?><br/>
        <b>klasa <?=$replacements->class?></b>
        <span class="last-modified pull-right"><?=formatTimestamp($replacements->lastModified)?></span>
    </div>
    <?php foreach($replacements->value as $hour => $text): ?>
        <div class="list-item">
            <div class="replacement-hour"><?=$hour?>.</div>
            <div class="replacement-subject"><?=$text?></div>
        </div>
    <?php endforeach ?>
</div>

<?php include 'templates/dashboard/footer.php' ?>