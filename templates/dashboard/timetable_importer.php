<?php $wideContainer = true ?>

<?php include 'templates/dashboard/header.php' ?>

<link rel="stylesheet" type="text/css" href="/assets/css/timetable-importer.css"/>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
<script src="/assets/js/csv-parser.js"></script>
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
			<label>1. CSV</label>
			<textarea class="code" ng-model="importer.inputCsv"></textarea>
		</div>
	</div>

	<div class="row">
		<div class="col-md-6">
			<label>2. Znajdź i zamień tekst w komórce tabeli <code><i>regex</i> -&gt; <i>zamień_na</i></code></label>
			<textarea class="code" ng-model="importer.inputReplace"></textarea>
		</div>
		<div class="col-md-6">
			<label>3. Wykryj nazwy przedmiotów <code><i>regex</i> -&gt; <i>nazwa_przedmiotu</i> [-&gt; <i>grupa</i>]</code></label>
			<textarea class="code" ng-model="importer.inputSubjects"></textarea>
		</div>
	</div>
	<br>
	<button ng-click="importer.parseCsv()" class="btn btn-lg btn-primary btn-center">
		<span class="glyphicon glyphicon-refresh"></span>
		Konwertuj
	</button>

	<br/>
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