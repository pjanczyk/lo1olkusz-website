<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" href="/assets/img/favicon-16.png" sizes="16x16" />
    <link rel="icon" type="image/png" href="/assets/img/favicon-32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="/assets/img/favicon-48.png" sizes="48x48" />

    <title>lo1olkusz app</title>

    <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&subset=latin,latin-ext' rel='stylesheet'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link href="/assets/css/cover.css" rel="stylesheet">
</head>

<body>

<img src="/assets/img/background-logo.svg" style="display: none" />
<div class="site-wrapper">

    <div class="site-wrapper-inner">

        <div class="cover-container">

            <div class="masthead clearfix">
                <div class="inner">
                    <h3 class="masthead-brand">
                        <img src="/assets/img/favicon-48.png"/>
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
                        <p class="lead">
                            <a href="/download" class="btn btn-lg btn-default">
                                <img src="/assets/img/android_icon.png"/>
                                Pobierz
                            </a>
                            <br/>
                            <span class="statistics"><?=$downloadCount?> pobrań, <?=$userCount?> użytkowników</span>
                            <!--<p class="required-version">Android 4.0+</p>-->
                        </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mastfoot">
                <div class="inner">
                    <p class="thanks">Podziękowania dla Michała Kiełtyki, od&nbsp;którego zaczerpnięty został pomysł stworzenia tej&nbsp;aplikacji.</p>
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
