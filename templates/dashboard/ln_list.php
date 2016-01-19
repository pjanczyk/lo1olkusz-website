<?php /** @var array(\pjanczyk\lo1olkusz\Model\LuckyNumber) $lns */ ?>

<?php include 'templates/dashboard/header.php' ?>

    <div class="page-header">
        <h1>Szczęśliwe numerki</h1>
    </div>

    <table class="table-centered" style="width: auto">
        <thead>
        <tr>
            <th>Data</th>
            <th>Wartość</th>
            <th>Zmodyfikowano</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($lns as $ln): ?>
            <tr>
                <td><?= $ln->date ?></td>
                <td class="ln-value"><?= $ln->value ?></td>
                <td class="last-modified"><?= formatTimestamp($ln->lastModified) ?></td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>

<?php include 'templates/dashboard/footer.php' ?>