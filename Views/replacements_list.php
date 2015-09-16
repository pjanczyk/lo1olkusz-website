<?php include 'Views/header.php' ?>

<div class="page-header">
    <h1>Zastępstwa</h1>
</div>

<table class="table table-condensed" style="width: auto">
    <thead>
    <tr>
        <th>Data</th>
        <th>Klasa</th>
        <th>Ostatnio zmodyfikowano</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($replacements as $r): ?>
        <tr>
            <td><?=$r->date?></td>
            <td><?=$r->class?></td>
            <td><?=$r->lastModified?></td>
            <td>
                <a class="btn btn-default btn-xs" href="/replacements/view/<?=$r->date?>/<?=$r->class?>">Wyświetl</a>
            </td>
        </tr>
    <? endforeach ?>
    </tbody>
</table>

<?php include 'Views/footer.php' ?>