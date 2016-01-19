<?php include 'templates/dashboard/header.php' ?>

<script>var className = <?= isset($class) ? "\"$class\"" : 'null' ?>;</script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
<script src="/assets/js/timetable-editor.js"></script>
<script src="/assets/js/timetable-viewer.js"></script>

<div ng-app="timetableViewer" ng-controller="ViewerController as controller">
    <div class="page-header">
        <h1>
            <a href="/dashboard/timetables">Plany lekcji</a>
            <span class="glyphicon glyphicon-menu-right"></span>
            <span ng-if="controller.addingNew">Dodaj</span>
            <span ng-if="!controller.addingNew">Klasa {{controller.className}}</span>
            <div class="pull-right">
                <button ng-if="!controller.addingNew" ng-click="controller.delete()" type="button" name="delete" class="btn btn-danger">Usuń</button>
                <button ng-click="controller.save()" type="button" id="button-save" class="btn btn-success">Zapisz</button>
            </div>
        </h1>
    </div>

    <div ng-repeat="alert in controller.alerts" class="alert alert-{{alert.type}}" role="alert">
        {{alert.value}}
    </div>

    <span ng-if="controller.lastModified" class="last-modified pull-right">{{controller.lastModified * 1000 | date:'yyyy-MM-dd HH:mm'}}</span>
    <div class="clearfix"></div>
    <br>

    <div ng-if="controller.addingNew" class="form-group">
        <label for="class">Klasa</label>
        <input ng-model="controller.className" type="text" class="form-control" name="class" id="class" placeholder="Nazwa klasy"/>
    </div>
    <timetable-editor value="controller.value"></timetable-editor>
</div>

<?php include 'templates/dashboard/footer.php' ?>