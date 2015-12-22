<?php /** @var \pjanczyk\lo1olkusz\Model\Timetable $timetable */ ?>

<?php include 'templates/dashboard/header.php' ?>

<script>var timetable = <?= $timetable !== null ? json_encode($timetable->value) : 'null' ?>;</script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
<script src="/assets/js/timetable-editor.js"></script>
<script src="/assets/js/timetable-viewer.js"></script>

<div ng-app="timetable-viewer" ng-controller="ViewerController as viewer">
    <form id="form" action="/dashboard/timetables" method="post">
        <div class="page-header">
            <h1>
                <a href="/dashboard/timetables">Plany lekcji</a>
                <span class="glyphicon glyphicon-menu-right"></span>
                <?php if ($timetable !== null): ?>
                    Klasa <?=$timetable->class?>
                <?php else: ?>
                    Dodaj
                <?php endif ?>
                <div class="pull-right">
                    <?php if ($timetable !== null): ?>
                    <button type="submit" name="delete" class="btn btn-danger">Usuń</button>
                    <?php endif ?>
                    <button ng-click="viewer.save()" type="button" id="button-save" class="btn btn-success">Zapisz</button>
                </div>
            </h1>
        </div>

        <?php if ($timetable !== null): ?>
            <span class="last-modified pull-right"><?=formatTimestamp($timetable->lastModified)?></span>
            <div class="clearfix"></div>
            <br>
        <?php endif ?>

        <input type="hidden" name="save" value="true" />

        <?php if ($timetable !== null): ?>
            <input type="hidden" name="class" value="<?=$timetable->class?>"/>
        <?php else: ?>
            <div class="form-group">
                <label for="class">Klasa</label>
                <input type="text" class="form-control" name="class" id="class" placeholder="Nazwa klasy"/>
            </div>
        <?php endif ?>
        <input type="hidden" name="value"/>
        <timetable-editor value="viewer.timetable"></timetable-editor>
    </form>
</div>

<?php include 'templates/dashboard/footer.php' ?>