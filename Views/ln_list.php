<?php include 'Views/header.php' ?>

    <div class="page-header">
        <h1>Lucky numbers</h1>
    </div>

    <table class="table table-condensed" style="width: auto">
        <thead>
        <tr>
            <th>date</th>
            <th>value</th>
            <th>last modified</th>
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

<?php include 'Views/footer.php' ?>