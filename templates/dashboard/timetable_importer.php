<?php $wideContainer = true ?>

<?php include 'templates/dashboard/header.php' ?>

<link rel="stylesheet" type="text/css" href="/assets/css/timetable-importer.css"/>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
<script src="/assets/js/timetable-editor.js"></script>
<script src="/assets/js/timetable-importer.js"></script>

<div ng-app="timetableImporter" ng-controller="ImporterCtrl as importer">
	<div class="page-header">
		<h1><a href="/dashboard/timetables">Plany lekcji</a>
            <span class="glyphicon glyphicon-menu-right"></span>
            Importuj
        </h1>
	</div>

	<div class="row">
		<div class="col-md-12">
			<label>JSON: </label>
            <input type="file" id="files" style="display:inline-block"/>
            <button ng-click="importer.loadFile()" class="btn btn-primary">
                <span class="glyphicon glyphicon-refresh"></span>
                Konwertuj
            </button>
		</div>
	</div>

	<hr/>
	<br/>

	<div ng-repeat="(class, timetable) in importer.timetables">
		<h2 class="class page-header">
			klasa <span>{{class}}</span>
			<div class="pull-right">
				<button ng-click="importer.discard(class)" class="btn btn-danger">Odrzuć</button>
				<button ng-click="importer.save(class)" class="btn btn-success">Zapisz</button>
			</div>
		</h2>
		<timetable-editor value="timetable"/>
	</div>
</div>

<?php include 'templates/dashboard/footer.php' ?>