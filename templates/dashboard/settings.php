<?php include 'templates/dashboard/header.php' ?>

<div class="page-header">
    <h1>Ustawienia</h1>
</div>

<?php include 'templates/dashboard/alerts.php' ?>

<div class="row">

    <div class="col-sm-6">
        <div class="pnl">
            <form action="/dashboard/settings" method="post" enctype="multipart/form-data">
                <div class="pnl-header">
                    Autoaktualizacje aplikacji
                    <button type="submit" class="btn btn-success btn-sm pull-right">Zapisz</button>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="apk-version">Wersja</label>
                        <input type="text" class="form-control" name="version" id="version"
                               placeholder="Wersja" value="<?= $version ?>">
                    </div>
                    <div class="form-group">
                        <label for="apk-file">Plik APK</label>
                        <?php if (isset($apkLastModified)): ?>
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <a href="/download"><span class="last-modified"><?=formatTimestamp($apkLastModified)?></span></a>
                                    <br>
                                    <small>MD5: <?=$apkMd5?></small>
                                </div>
                            </div>
                        <?php endif ?>
                        <input type="file" name="apk" id="apk">
                    </div>
                </div>
            </form>
        </div>
    </div>

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