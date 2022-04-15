<?php

include 'session_checker.php';
include 'db/config_v2.php';

?>

<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Custom Battle | Freestyle Battle</title>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
    <meta name="keywords" content="Freestyle Battle" />
    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- <link rel="stylesheet" type="text/css" href="../css/my/dashboard.css?v=1">
  	<link rel="stylesheet" type="text/css" href="/css/custom_battle.css?v=1"> -->
  </head>
  <body>

    <div class="container-sm w-75">

      <br><br>

      <div class="table-responsive">

        <table class="table caption-top table-hover">
          <caption>FREESTYLE DISPONIBILI</caption>
          <thead>
            <tr>
              <th scope="col">SFIDANTE</th>
              <th scope="col">STATO</th>
              <th scope="col">AZIONI</th>
            </tr>
          </thead>
          <tbody>

                <?php

                /*
                blocco try/catch di gestione delle eccezioni
                */
                try {
                // stringa di connessione al DBMS
                $connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
                // imposto dell'attributo necessario per ottenere il report degli errori
                $connessione->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // selezione e visualizzazione dei dati estratti
                foreach ($connessione->query("SELECT id, guest_one, status FROM battle_request") as $battle)
                {

                  if ($battle['guest_one'] != $_SESSION['session_user'] AND $battle['status'] == 'Aperto') {
                    echo '

                    <tr style="border-bottom: 2px solid black">
                      <td><b>' . $battle['guest_one'] . '</b></td>
                      <td><i>' . $battle['status'] . '</i></td>
                      <td><a class="btn btn-outline-primary" href="record.php?battle_id=' . $battle['id'] . '" role="button">SFIDA</a></td>
                    </tr>

                    ';
                  }

                }
                // chiusura della connessione
                $connessione = null;
                }
                catch(PDOException $e)
                {
                // notifica in caso di errore nel tentativo di connessione
                echo $e->getMessage();
                }

                ?>

            </tbody>
          </table>
        </div>
      </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

  </body>
</html>
