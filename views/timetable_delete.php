<?php include 'html/header.php' ?>

    <div class="page-header">
        <h1>Timetables</h1>
    </div>

    <form class="form-horizontal" action="/timetable" method="post">
        <input type="hidden" name="delete" value="true"/>
        <input type="hidden" name="class" value="<?=$timetable['class']?>"/>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="class">Class</label>
            <div class="col-sm-10">
                <p id="class" class="form-control-static"><?=$timetable['class']?></p>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">Delete</button>
            </div>
        </div>
    </form>

<?php include 'html/footer.php' ?>