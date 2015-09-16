<?php include 'Views/header.php' ?>

<div class="page-header">
    <h1>Zastępstwa</h1>
</div>

<div class="form-horizontal">
    <div class="form-group">
        <label class="col-sm-2 control-label">Data</label>
        <div class="col-sm-10"><?=$replacements->date?></div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Klasa</label>
        <div class="col-sm-10"><?=$replacements->class?></div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Ostatnio zmodyfikowano</label>
        <div class="col-sm-10"><?=$replacements->lastModified?></div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Wartość</label>
        <div class="col-sm-10">
            <? $value = json_decode($replacements->value, true);
            foreach($value as $hour => $replacement) {
                echo $hour . '. ' . $replacement . '<br/>';
            }
            ?>
        </div>
    </div>
</div>

<?php include 'Views/footer.php' ?>