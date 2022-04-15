<?php

session_start();

?>

<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>New Account | Freestyle Battle</title>
    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  </head>
  <body>

    <br><br>

    <h2 style="text-align: center; margin-bottom: 50px;" class="text-uppercase display-4">REGISTRAZIONE</h2>

    <div class="container-sm w-50">

    <form action="newaccount.php" method="post">

      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text" id="">Nome e Cognome</span>
        </div>
        <input type="text" name="name" class="form-control" placeholder="Nome" maxlength="20" required>
        <input type="text" name="surname" class="form-control" placeholder="Cognome" maxlength="20" required>
      </div>
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text" id="basic-addon1">Anno</span>
        </div>
        <input type="text" class="form-control" name="year" placeholder="Inserisci il tuo anno di nascita" aria-label="Anno" aria-describedby="basic-addon1" maxlength="4" required>
      </div>
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text" id="basic-addon1"><img src="https://img.icons8.com/ios-glyphs/25/000000/email.png"/></span>
        </div>
        <input type="text" class="form-control" name="username" placeholder="Inserisci un nome utente" aria-label="Username" aria-describedby="basic-addon1" maxlength="20" required>
      </div>
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text" id="basic-addon1"><img src="https://img.icons8.com/ios-glyphs/25/000000/unlock.png"/></span>
        </div>
        <input type="text" class="form-control" name="password" placeholder="Inserisci una password" aria-label="Password" aria-describedby="basic-addon1" maxlength="30" required>
      </div>
      <br>
      <div class="form-group">
        <label for="exampleInputEmail1">Email</label>
        <input type="email" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Inserisci l'email" maxlength="40" required>
      </div>
      <br>
      <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <button type="submit" class="btn btn-primary">INVIA</button>
      </div>

      <input type="hidden" name="register" value="1">

    </form>

      </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

  </body>
</html>
