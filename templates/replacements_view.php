<?php include 'templates/header.php' ?>

<div class="page-header">
    <h1>Replacements</h1>
</div>

<div class="form-horizontal">
    <div class="form-group">
        <label class="col-sm-2 control-label">Date</label>
        <div class="col-sm-10"><?=$replacements->date?></div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Class</label>
        <div class="col-sm-10"><?=$replacements->class?></div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Last modified</label>
        <div class="col-sm-10"><?=$replacements->lastModified?></div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Value</label>
        <div class="col-sm-10"><?=$replacements->value?></div>
    </div>
</div>

<?php include 'templates/footer.php' ?>