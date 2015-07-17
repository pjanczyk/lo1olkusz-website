<?php

?>

<?php include 'html/header.php' ?>

<div class="page-header">
    <h1>Timetables</h1>
</div>

<?php include 'views/alerts.php' ?>

<div class="btn-group" role="group">
    <a href="/timetable/add" class="btn btn-default">Add</a>
</div>
<br/>
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
                    <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Actions
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="/api/timetables/<?=urlencode($timetable['class'])?>">View</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="/timetable/edit/<?=urlencode($timetable['class'])?>">Edit</a></li>
                        <li><a class="timetable-delete" href="/timetable/delete/<?=urlencode($timetable['class'])?>">Delete</a></li>
                    </ul>
                </div>
            </td>
        </tr>
    <? endforeach ?>
    </tbody>
</table>
<script>
    $('a.timetable-delete').click(function() {
        $(post)
    });
</script>
<?php include 'html/footer.php' ?>