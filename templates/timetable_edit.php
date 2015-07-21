<?php include 'templates/header.php' ?>

<div class="page-header">
    <h1><?= $timetable !== null ? 'Edit timetable' : 'Add timetable'?></h1>
</div>

<form class="form-horizontal" action="/settings" method="post">
    <input type="hidden" name="edit" value="true"/>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="class">Class</label>
        <div class="col-sm-10">
            <?php if ($timetable !== null): ?>
            <input type="hidden" name="class" value="<?=$timetable->class?>"/>
            <p id="class" class="form-control-static"><?=$timetable->class?></p>
            <?php else: ?>
            <input type="text" class="form-control" name="class" id="class" placeholder="Class name"/>
            <?php endif ?>
        </div>
    </div>
    <div class="form-group">
        <label for="timetable" class="col-sm-2 control-label">Timetable</label>
        <div class="col-sm-10">
            <textarea class="form-control" name="timetable" id="timetable" rows="10" placeholder="Timetable"
                ><?= $timetable !== null ? $timetable->value : '' ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-default">Save</button>
        </div>
    </div>
</form>

<?php include 'templates/footer.php' ?>