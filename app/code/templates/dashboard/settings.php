<?php include 'templates/dashboard/header.php' ?>

<div class="page-header">
    <span class="last-modified pull-right"><?=date('r')?></span>
    <h1>Ustawienia</h1>
</div>

<?php include 'templates/dashboard/alerts.php' ?>

<div class="row">
    <div class="col-sm-6">
        <div class="pnl">
            <div class="pnl-header">
                Statystyki
            </div>
            <div class="panel-body">
                <h4>Zapytania API</h4>
                <table>
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Użytkowników</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($statisticsApi as $s): ?>
                            <tr>
                                <td><?=$s->date?></td>
                                <td><?=$s->users?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'templates/dashboard/footer.php' ?>