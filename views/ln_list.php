<?php include 'views/header.php' ?>

    <div class="page-header">
        <h1>Lucky numbers</h1>
    </div>

    <table class="table table-condensed" style="width: auto">
        <thead>
        <tr>
            <th>date</th>
            <th>value</th>
            <th>last modified</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($lns as $ln): ?>
            <tr>
                <td><?=$ln->date?></td>
                <td><?=$ln->value?></td>
                <td><?=$ln->lastModified?></td>
                <td>
                    <a class="btn btn-default btn-xs" href="/api/ln/<?=$ln->date?>">Show API</a>
                </td>
            </tr>
        <? endforeach ?>
        </tbody>
    </table>

<?php include 'views/footer.php' ?>