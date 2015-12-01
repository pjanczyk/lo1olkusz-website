<?php include 'templates/header.php' ?>

    <div class="page-header">
        <h1>Szczęśliwe numerki</h1>
    </div>

    <table class="table table-condensed" style="width: auto">
        <thead>
        <tr>
            <th>Data</th>
            <th>Wartość</th>
            <th>Ostatnio zmodyfikowano</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($lns as $ln): ?>
            <tr>
                <td><?=$ln->date?></td>
                <td><?=$ln->value?></td>
                <td><?=$ln->lastModified?></td>
            </tr>
        <? endforeach ?>
        </tbody>
    </table>

<?php include 'templates/footer.php' ?>