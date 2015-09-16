<?php include 'Views/header.php' ?>

<div class="page-header">
    <h1>Usuń plan lekcji</h1>
</div>

<form class="form-horizontal" action="/dashboard/settings" method="post">
    <input type="hidden" name="delete" value="true"/>
    <input type="hidden" name="class" value="<?=$timetable->class?>"/>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="class">Klasa</label>
        <div class="col-sm-10">
            <p id="class" class="form-control-static"><?=$timetable->class?></p>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-default">Usuń</button>
        </div>
    </div>
</form>

<?php include 'Views/footer.php' ?>