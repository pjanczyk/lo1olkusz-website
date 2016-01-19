<?php include 'templates/dashboard/header.php' ?>

<div class="page-header"><h1>Logi</h1></div>

<div>
    <a id="run-cron" class="btn btn-default btn-sm" href="#">Uruchom cron</a>
    <a id="clear-log" class="btn btn-danger btn-sm" href="#">Usuń logi</a>
    <a id="cron-refresh" class="btn btn-default btn-sm" href="#">Odśwież</a>
</div>
<br/>

<pre id="cron-log"></pre>

<script>
    function showAlert(type, html) {
        $(".page-header").after(
            '<div class="alert alert-' + type + '" role="alert">' + html + '</div>'
        );
    }

    function loadCron() {
        $.get('/api/logs', function (data) {
            $("#cron-log").html(data);
        });
    }

    function clearCron() {
        $.ajax({
            type: "DELETE",
            url: "/api/logs"
        }).done(function () {
            $("#cron-log").empty();
            showAlert("success", "Usunięto");
        }).fail(function (jqXHR) {
            var data = $.parseJSON(jqXHR.responseText);
            showAlert("danger", data.error);
        });
    }

    function runCron() {
        $.get("/api/run-cron", function (data) {
            $(".page-header").after(
                '<pre>' + data + '</pre>'
            );
        });
    }

    $(function () {
        $("#clear-log").click(clearCron);
        $("#run-cron").click(runCron);
        $("#cron-refresh").click(function () {
            $("#cron-log").empty();
            loadCron();
        });
        loadCron();
    });
</script>

<?php include 'templates/dashboard/footer.php' ?>