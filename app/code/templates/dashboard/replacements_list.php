<?php include 'templates/dashboard/header.php' ?>

<div class="page-header">
    <h1>Zastępstwa</h1>
</div>

<table class="table-centered" style="width: auto">
    <thead>
    <tr>
        <th>Data</th>
        <th>Klasa</th>
        <th>Zmodyfikowano</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($transposed as $date => $replacements): ?>
        <tr>
        <td rowspan="<?= count($replacements) ?>"><?= $date ?></td>
        <?php $count = 0 ?>
        <?php foreach ($replacements as $r): ?>
            <?php if ($count > 0): ?>
                <tr>
            <?php endif ?>

            <td><?= $r->class ?></td>
            <td class="last-modified"><?= formatTimestamp($r->lastModified) ?></td>
            <td>
                <a class="btn btn-default btn-xs"
                   href="/dashboard/replacements/view/<?= $r->date ?>/<?= $r->class ?>">Wyświetl</a>
            </td>
            </tr>
        <?php endforeach ?>
    <?php endforeach ?>
    </tbody>
</table>

<?php include 'templates/dashboard/footer.php' ?>