<?php
$menu = [
    ['', 'Home'],
    ['cron', 'Cron'],
    ['lucky-numbers', 'Lucky numbers'],
    ['replacements', 'Replacements'],
    ['settings', 'Settings'],
];

$url = isset($_GET['p']) ? $_GET['p'] : '';
$url = ltrim($path, '/');
$url = explode('/', $path, 2)[0];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="lo1olkusz app service">
    <meta name="author" content="Piotr Janczyk">

    <title>lo1olkusz app service</title>

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <link href="/css/theme.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
</head>

<body role="document">

<!-- Fixed navbar -->
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">lo1olkusz app service</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <?php foreach ($menu as $menuItem): ?>
                    <li<?php if ($menuItem[0] === $url): ?> class="active"<?php endif ?>><a href="/<?=$menuItem[0]?>"><?=$menuItem[1]?></a></li>
                <?php endforeach ?>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>

<div class="container theme-showcase" role="main">