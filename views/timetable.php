<?php

?>

<?php include 'html/header.php' ?>

<div class="page-header">
    <h1>Timetables</h1>
</div>

<div class="row">
    <div class="col-sm-6">
        <table class="table table-condensed" style="width: auto">
            <thead>
            <tr>
                <th>class</th>
                <th>last modified</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($timetables as $timetable): ?>
                <tr>
                    <td>
                        <a href="/api/timetable/<?=urlencode($timetable['class'])?>">
                            <?=$timetable['class']?>
                        </a>
                    </td>
                    <td><?=$timetable['last_modified']?></td>
                </tr>
            <? endforeach ?>
            </tbody>
        </table>
    </div>
    <div class="col-sm-6">
<h2>Change</h2>
    <form action="/timetable" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="class">Class</label>
            <input type="text" class="form-control" name="class" id="class" placeholder="Class name"/>
        </div>
        <div class="form-group">
            <label for="timetable">Timetable</label>
            <textarea class="form-control" name="timetable" id="timetable" rows="10" placeholder="Timetable content"></textarea>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox"> Delete
            </label>
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
    </form>
    </div>
</div>

<?php include 'html/footer.php' ?>