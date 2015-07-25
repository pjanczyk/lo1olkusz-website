<?php include 'templates/header.php' ?>

<div class="page-header">
    <h1>Replacements</h1>
</div>

<div class="row">
    <div class="col-sm-offset-2 col-sm-10">
        <a class="btn btn-default" href="/api/replacements/<?=$replacements->date?>/<?=$replacements->class?>.json">Show API</a>
    </div>
</div>
<div class="row">
    <label class="col-sm-2 control-label">Date</label>
    <div class="col-sm-10"><?=$replacements->date?></div>
</div>
<div class="row">
    <label class="col-sm-2 control-label">Class</label>
    <div class="col-sm-10"><?=$replacements->class?></div>
</div>
<div class="row">
    <label class="col-sm-2 control-label">Last modified</label>
    <div class="col-sm-10"><?=$replacements->lastModified?></div>
</div>
<div class="row">
    <label class="col-sm-2 control-label">Value</label>
    <div class="col-sm-10"><?=$replacements->value?></div>
</div>

<?php include 'templates/footer.php' ?>