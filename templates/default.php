<?php include 'templates/header.php' ?>

<?php include 'templates/alerts.php' ?>

<h4>Status file</h4>
<form action="/" method="post">
    <input type="hidden" name="update-status" />
    <button type="submit" class="btn btn-default">Update status</button>
</form>
<a href="/api/status"><?=$statusTimestamp?></a>

<?php include 'templates/footer.php';