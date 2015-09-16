<?php include 'Views/header.php' ?>

<div class="page-header">
    <h1><?= $timetable !== null ? 'Edycja planu lekcji' : 'Dodaj plan lekcji'?></h1>
</div>

<form class="form-horizontal" action="/settings" method="post">
    <input type="hidden" name="edit" value="true"/>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="class">Klasa</label>
        <div class="col-sm-10">
            <?php if ($timetable !== null): ?>
            <input type="hidden" name="class" value="<?=$timetable->class?>"/>
            <p id="class" class="form-control-static"><?=$timetable->class?></p>
            <?php else: ?>
            <input type="text" class="form-control" name="class" id="class" placeholder="Nazwa klasy"/>
            <?php endif ?>
        </div>
    </div>
    <div class="form-group">
        <label for="timetable" class="col-sm-2 control-label">Plan lekcji</label>
        <div class="col-sm-10">
            <textarea class="form-control" name="timetable" id="timetable" rows="10" placeholder="Plan lekcji"
                ><?= $timetable !== null ? $timetable->value : '' ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-default">Zapisz</button>
        </div>
    </div>
</form>

<?php include 'Views/footer.php' ?>