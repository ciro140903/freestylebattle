<?php

include 'session_checker.php';

?>

<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Home | Freestyle Battle</title>
    <meta name="keywords" content="Freestyle Battle" />
    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
    <link rel="stylesheet" href="/css/home.css?v=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  </head>
  <body>

    <div class="menu">

      <h2 style="text-align: center; margin-bottom: 50px;" class="text-uppercase display-4"><?php echo $_SESSION['session_user']; ?></h2>

      <div class="container">
        <div class="row">
          <div class="col align-self-center">
              <div class="list-group">
                <a href="my/dashboard.php" class="btn btn-primary btn-lg text-center">DASHBOARD</a>
                <a href="battle.php" class="btn btn-primary btn-lg text-center">MATCH IN CORSO</a>
                <a href="custom_battle.php" class="btn btn-primary btn-lg text-center">TROVA MATCH</a>
                <a href="logout.php" class="btn btn-danger btn-lg text-center">LOGOUT</a>
              </div>
          </div>
        </div>
      </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

  </body>
</html>
