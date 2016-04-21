<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#131d62">

    <meta name="description" content="Wszystkie informacje w jednym miejscu. Plan lekcji, zastępstwa i szczęśliwy numerek na dany dzień. Powiadomienia o zastępstawch i szczęśliwych numerkach.">
    <meta name="author" content="Piotr Janczyk">
    <link rel="icon" type="image/png" href="/assets/img/favicon-16.png" sizes="16x16" />
    <link rel="icon" type="image/png" href="/assets/img/favicon-32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="/assets/img/favicon-48.png" sizes="48x48" />
    <link rel="icon" type="image/png" href="/assets/img/favicon-96.png" sizes="96x96" />
    <link rel="icon" type="image/png" href="/assets/img/favicon-144.png" sizes="144x144" />
    <link rel="icon" type="image/png" href="/assets/img/favicon-192.png" sizes="192x192" />

    <title>lo1olkusz - nieoficjalna aplikacja na Androida</title>

    <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&subset=latin,latin-ext' rel='stylesheet'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link href="/assets/css/cover.css?rev=2" rel="stylesheet">
</head>

<body>

<img src="/assets/img/background-logo.svg" style="display: none" />
<div class="site-wrapper">

    <div class="site-wrapper-inner">

        <div class="cover-container">

            <div class="masthead clearfix">
                <div class="inner">
                    <h3 class="masthead-brand">
                        <div class="logo-icon"></div>
                        <div>
                            <p>lo1olkusz</p>
                            <p><small>nieoficjalna aplikacja</small></p>
                        </div>
                    </h3>

                    <nav>
                        <ul class="nav masthead-nav">
                            <li class="active"><a href="/">Home</a></li>
                            <li><a href="/download">Pobierz</a></li>
                            <li><a href="https://github.com/pjanczyk/lo1olkusz">GitHub</a></li>
                        </ul>
                    </nav>
                </div>
            </div>

            <div class="inner cover">
                <div class="row">
                    <div class="col-sm-6 col-sm-push-6 screen-container">
                        <img class="screen" src="/assets/img/screen.png" />
                    </div>
                    <div class="product-description col-sm-6 col-sm-pull-6">
                        <div>
                            <h1 class="cover-heading">Wszystkie informacje w&nbsp;jednym miejscu</h1>
                            <p class="lead">Plan lekcji, zastępstwa i szczęśliwy numerek na&nbsp;dany&nbsp;dzień</p>
                            <p class="lead">Powiadomienia o zastępstawch i&nbsp;szczęśliwych numerkach</p>
                            <div class="btn-download-box">
                                <a href="/download" class="btn btn-lg btn-download">
                                    <img src="/assets/img/android_icon.png"/>
                                    Pobierz
                                </a>
                                <div class="required-version">Android 4.0+</div>
                                <div class="statistics"><?= $userCount ?> użytkowników</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mastfoot">
                <div class="inner">
                    <p class="thanks">Podziękowania dla <span class="weight-400">Michała Kiełtyki</span>, od&nbsp;którego zaczerpnięty został pomysł stworzenia tej&nbsp;aplikacji.</p>
                    <p>&copy; 2016 <a href="mailto:kontakt.pjanczyk@gmail.com">Piotr Janczyk</a></p>
                </div>
            </div>

        </div>

    </div>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</body>
</html>
