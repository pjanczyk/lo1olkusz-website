<?php /** @var \pjanczyk\lo1olkusz\Model\Timetable $timetable */ ?>

<?php include 'templates/dashboard/header.php' ?>

<script>var bells = <?= $bells !== null ? json_encode($bells->value) : 'null' ?>;</script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
<script src="/assets/js/timetable-bells.js"></script>

<div ng-app="timetableBells" ng-controller="BellsController as controller">
    <form name="form" id="form" action="/dashboard/timetables/bells" method="post">
        <input type="hidden" name="value"/>

        <div class="page-header">
            <h1>
                <a href="/dashboard/timetables">Plany lekcji</a>
                <span class="glyphicon glyphicon-menu-right"></span>
                Dzwonki
                <div class="pull-right">
                    <button ng-disabled="form.$invalid" ng-click="controller.save()" type="button" id="button-save" class="btn btn-success">Zapisz</button>
                </div>
            </h1>
        </div>

        <?php include 'templates/dashboard/alerts.php' ?>

        <div class="pnl">
            <div class="pnl-header">
                <button type="button" class="btn btn-sm" ng-click="controller.addRow()">
                    <span class="glyphicon glyphicon-plus"></span>
                </button>
                <button type="button" class="btn btn-sm" ng-click="controller.removeRow()">
                    <span class="glyphicon glyphicon-minus"></span>
                </button>
                <?php if ($bells !== null): ?>
                    <span class="last-modified pull-right"><?=formatTimestamp($bells->lastModified)?></span>
                <?php endif ?>
            </div>
            <div>
                <div ng-repeat="(hourNo, bell) in controller.bells" class="list-item">
                    <div class="bell-hour">{{hourNo+1}}.</div>
                    <input class="bell-time" type="text" ng-required="true" ng-pattern="'\\d?\\d:\\d\\d'" ng-model="bell[0]">
                    -
                    <input class="bell-time" type="text" ng-required="true" ng-pattern="'\\d?\\d:\\d\\d'" ng-model="bell[1]">

                </div>
            </div>
        </div>
    </form>
</div>

<?php include 'templates/dashboard/footer.php' ?>