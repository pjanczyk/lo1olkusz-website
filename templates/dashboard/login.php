<?php include 'templates/dashboard/header.php' ?>

<style>
    body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #eee;
    }

    .form-signin {
        max-width: 330px;
        padding: 15px;
        margin: 0 auto;
    }
    .form-signin .form-signin-heading {
        margin-bottom: 10px;
    }
    .form-signin .form-control {
        position: relative;
        height: auto;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        padding: 10px;
        font-size: 16px;
    }
    .form-signin .form-control:focus {
        z-index: 2;
    }
    .form-signin #inputUsername {
        margin-bottom: -1px;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }
    .form-signin #inputPassword {
        margin-bottom: 10px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }
</style>

    <form class="form-signin" action="" method="post">
        <h1 class="form-signin-heading">Logowanie</h1>
        <?php include 'templates/dashboard/alerts.php' ?>
        <label for="inputUsername" class="sr-only">Login</label>
        <input type="text" id="inputUsername" name="login" class="form-control" placeholder="Login" required autofocus>
        <label for="inputPassword" class="sr-only">Hasło</label>
        <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Hasło" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Zaloguj</button>
    </form>

<?php include 'templates/dashboard/footer.php' ?>