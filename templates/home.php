<?php
use pjanczyk\lo1olkusz\Model\NewsModel;

include 'templates/header.php' ?>

<div class="page-header">
    <h1>News</h1>
</div>
<p>since <?=$now?></p>


<table class="table table-bordered">
    <thead>
        <tr>
            <th>type</th>
            <th>lastModified</th>
            <th>date</th>
            <th>class</th>
            <th>value</th>
        </tr>
    </thead>
    <tbody>

<?php
foreach ($news as $n) {
    echo '<tr>';

    switch($n['type']) {
        case NewsModel::APK:
            ?>
            <td>apk version</td>
            <td></td>
            <td></td>
            <td></td>
            <td><?=$n['value']?></td>
            <?php
            break;
        case NewsModel::REPLACEMENTS:
            ?>
            <td>replacements</td>
            <td><?=$n['timestamp']?></td>
            <td><?=$n['date']?></td>
            <td><?=$n['class']?></td>
            <td><?=$n['value']?></td>
            <?php
            break;
        case NewsModel::LUCKY_NUMBER:
            ?>
            <td>lucky number</td>
            <td><?=$n['timestamp']?></td>
            <td><?=$n['date']?></td>
            <td></td>
            <td><?=$n['value']?></td>
            <?php
            break;
        case NewsModel::TIMETABLE:
            ?>
            <td>timetable</td>
            <td><?=$n['timestamp']?></td>
            <td></td>
            <td><?=$n['class']?></td>
            <td><?=$n['value']?></td>
            <?php
            break;
    }
    echo '</tr>';
}
?>

    </tbody>
</table>

<?php include 'templates/footer.php';