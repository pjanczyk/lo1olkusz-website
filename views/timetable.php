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
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($timetables as $timetable): ?>
                <tr>
                    <td><?=$timetable['class']?></td>
                    <td><?=$timetable['last_modified']?></td>
                    <td>
                        <div class="btn-group">
                            <a href="/api/timetable/<?=urlencode($timetable['class'])?>" class="btn btn-default btn-sm">View</a>
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="/timetable/edit/<?=urlencode($timetable['class'])?>">Edit</a></li>
                                <li><a href="/timetable/delete/<?=urlencode($timetable['class'])?>">Delete</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            <? endforeach ?>
            </tbody>
        </table>
    </div>
    <div class="col-sm-6">

    </div>
</div>

<?php include 'html/footer.php' ?>