<?php
/**
 * Copyright 2015 Piotr Janczyk
 *
 * This file is part of I LO Olkusz Unofficial App.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
?>

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