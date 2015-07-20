<?php include 'views/header.php' ?>

    <div class="page-header">
        <h1>Replacements</h1>
    </div>

    <table class="table table-condensed" style="width: auto">
        <thead>
        <tr>
            <th>date</th>
            <th>class</th>
            <th>last modified</th>
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
                    <a class="btn btn-default btn-xs" href="/api/replacements/<?=$r->date?>/<?=$r->class?>">Show API</a>
                </td>
            </tr>
        <? endforeach ?>
        </tbody>
    </table>

<?php include 'views/footer.php' ?>