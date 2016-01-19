<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="lo1olkusz app service">
    <meta name="author" content="Piotr Janczyk">

    <title>lo1olkusz app</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&subset=latin,latin-ext' rel='stylesheet'>
    <link href="/assets/css/style.css" rel="stylesheet">
</head>

<body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="brand-row">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <div class="navbar-brand">
                    <a class="navbar-brand-home" href="/">lo1olkusz app</a>
                    <a class="navbar-brand-dashboard" href="/dashboard">dashboard</a>
                </div>
            </div>
            <?php if(\pjanczyk\lo1olkusz\Auth::isAuthenticated()): ?>
                <a class="logout" href="/dashboard/login/logout">Wyloguj</a>
            <?php endif ?>
        </div>
    </div>

    <div class="container-fluid">
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <?php
                $menu = [
                    ['', 'Strona główna'],
                    ['cron', 'Logi'],
                    ['lucky-numbers', 'Numerki'],
                    ['replacements', 'Zastępstwa'],
                    ['timetables', 'Plany lekcji'],
                    ['settings', 'Ustawienia'],
                ];

                $url = isset($_GET['p']) ? $_GET['p'] : '';
                $url = ltrim($url, '/');
                $url = explode('/', $url, 3);
                $url = isset($url[1]) ? $url[1] : '';
                ?>

                <?php foreach ($menu as $menuItem): ?>
                    <li<?php if ($menuItem[0] === $url): ?> class="active"<?php endif ?>><a href="/dashboard/<?=$menuItem[0]?>"><?=$menuItem[1]?></a></li>
                <?php endforeach ?>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>

<div class="container <?= isset($wideContainer) ? '': 'container-narrow' ?>" role="main">



<?php

$authenticated = \pjanczyk\lo1olkusz\Auth::isAuthenticated();

function formatTimestamp($timestamp)
{
    return date("Y-m-d H:i", $timestamp);
}
?>

<script>var authenticated = <?= $authenticated ? 'true' : 'false' ?>;</script>
