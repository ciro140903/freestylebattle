<?php

session_start();

if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}

?>

<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Login | Freestyle Battle</title>
    <link rel="stylesheet" href="/css/login.css">
    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- <script type="text/javascript" src="/js/login.js"></script> -->
  </head>
  <body>

    <div class="wrapper fadeInDown">

      <?php

        if (isset($_GET['status'])) {
          if ($_GET['status'] == 'ok') {
            echo '

            <div class="alert alert-success w-25" style="margin: 0 auto; margin-bottom: 100px; text-align: center;" role="alert">
              Registrazione avvenuta con successo!
            </div>

            ';
          }elseif ($_GET['status'] == 'already_taken') {
            echo '

            <div class="alert alert-danger w-25" style="margin: 0 auto; margin-bottom: 100px; text-align: center;" role="alert">
              Username già in uso!
            </div>

            ';
          }elseif ($_GET['status'] == 'notvalid') {
            echo '

            <div class="alert alert-danger w-25" style="margin: 0 auto; margin-bottom: 100px; text-align: center;" role="alert">
              La password non rispetta i requisiti!
            </div>

            ';
          }else{
            echo '

            <div class="alert alert-danger w-25" style="margin: 0 auto; margin-bottom: 100px; text-align: center;" role="alert">
              Errore interno!
            </div>

            ';
          }
        }

      ?>

      <div id="formContent">
        <!-- Tabs Titles -->

        <!-- Icon -->
        <div class="fadeIn first">
          <img src="/img/favicon.ico" id="icon" alt="N/A"/>
        </div>

        <!-- Login Form -->
        <form action="auth.php" method="post">
          <input type="text" id="login" class="fadeIn second" name="username" placeholder="Username" required>
          <input type="password" id="password" class="fadeIn third" name="password" placeholder="Password" required>
          <input type="submit" class="fadeIn fourth" value="Log In">
          <input type="hidden" name="login" value="1">
        </form>

        <!-- Remind Passowrd -->
        <div id="formFooter">
          <a class="underlineHover" href="register.php">Registrati</a>
        </div>

      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

  </body>
</html>
