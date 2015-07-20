<?php include 'html/header.php' ?>

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
        <?php foreach ($timetables as $t): ?>
            <tr>
                <td><?=$t->date?></td>
                <td><?=$t->class?></td>
                <td><?=$t->lastModified?></td>
                <td>
                    <a class="btn btn-default btn-xs" href="/api/replacements/<?=$t->date?>/<?=$t->class?>">Show API</a>
                </td>
            </tr>
        <? endforeach ?>
        </tbody>
    </table>

<?php include 'html/footer.php' ?>