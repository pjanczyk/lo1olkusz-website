<?php
use pjanczyk\lo1olkusz\Models\NewsModel;

include 'Views/header.php' ?>

<div class="page-header">
    <h1>Strona główna</h1>
</div>
<p>since <?=$now?></p>


<table class="table table-bordered">
    <thead>
        <tr>
            <th>Typ</th>
            <th>Data/Klasa</th>
            <th>Wartość</th>
            <th>Ostatnio zmodyfikowano</th>
        </tr>
    </thead>
    <tbody>

<?php
foreach ($news as $n) {
    echo '<tr>';

    switch($n['type']) {
        case NewsModel::APK:
            ?>
            <td>Wersja aplikacji</td>
            <td></td>
            <td></td>
            <td><?=$n['value']?></td>
            <?php
            break;
        case NewsModel::REPLACEMENTS:
            ?>
            <td>Zastępstwa</td>
            <td><?=$n['date']?><br/><?=$n['class']?></td>
            <td><?
                $value = json_decode($n['value'], true);
                foreach($value as $hour => $replacement) {
                    echo $hour . '. ' . $replacement . '<br/>';
                }
                ?></td>
            <td><?=date('Y-m-d H:i:s', $n['timestamp'])?></td>
            <?php
            break;
        case NewsModel::LUCKY_NUMBER:
            ?>
            <td>Szczęśliwy numerek</td>
            <td><?=$n['date']?></td>
            <td><?=$n['value']?></td>
            <td><?=date('Y-m-d H:i:s', $n['timestamp'])?></td>
            <?php
            break;
        case NewsModel::TIMETABLE:
            ?>
            <td>Plan lekcji</td>
            <td><?=$n['class']?></td>
            <td><pre><?=$n['value']?></pre></td>
            <td><?=date('Y-m-d H:i:s', $n['timestamp'])?></td>
            <?php
            break;
    }
    echo '</tr>';
}
?>

    </tbody>
</table>

<?php include 'Views/footer.php';