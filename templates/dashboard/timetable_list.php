<?php /** @var pjanczyk\lo1olkusz\Model\Timetable[] $timetables */ ?>

<?php include 'templates/dashboard/header.php' ?>

<div class="page-header">
    <h1>Plany lekcji</h1>
</div>

<div>
    <a href="/dashboard/timetables/add" class="btn btn-default">Dodaj</a>
    <a href="/dashboard/timetables/import" class="btn btn-default">Importuj</a>
    <a href="/dashboard/timetables/bells" class="btn btn-default">Dzwonki</a>
</div>
<br>
<div class="tbl tbl-centered" style="width: auto">
    <div class="tbl-row tbl-header">
        <div class="tbl-cell">Klasa</div>
        <div class="tbl-cell">Zmodyfikowano</div>
    </div>
    <?php foreach ($timetables as $timetable): ?>
        <a class="tbl-row" href="/dashboard/timetables/edit/<?= $timetable->class ?>">
            <div class="tbl-cell"><?= $timetable->class ?></div>
            <div class="tbl-cell last-modified"><?= formatTimestamp($timetable->lastModified) ?></div>
        </a>
    <?php endforeach ?>
</div>


<?php include 'templates/dashboard/footer.php' ?>

