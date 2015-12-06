<?php /** @var \pjanczyk\lo1olkusz\Model\Replacements $replacements */ ?>

<?php include 'templates/dashboard/header.php' ?>

<div class="page-header">
    <h1>Zastępstwa</h1>
</div>

<div class="form-horizontal">
    <div class="form-group">
        <label class="col-sm-2 control-label">Data</label>
        <div class="col-sm-10">
            <p class="form-control-static"><?=$replacements->date?></p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Klasa</label>
        <div class="col-sm-10">
            <p class="form-control-static"><?=$replacements->class?></p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Zmodyfikowano</label>
        <div class="col-sm-10">
            <p class="form-control-static"><?=formatTimestamp($replacements->lastModified)?></p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Wartość</label>
        <div class="col-sm-10">
            <p class="form-control-static">
                <?php foreach($replacements->value as $hour => $text): ?>
                    <?=$hour?>. <?=$text?><br/>
                <?php endforeach ?>
            </p>
        </div>
    </div>
</div>

<?php include 'templates/dashboard/footer.php' ?>