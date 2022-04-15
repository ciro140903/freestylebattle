<?php

include 'session_checker.php';
include 'db/config_v2.php';

$logged_user = $_SESSION['session_user'];

?>

<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0"/>
    <title>Battle | Freestyle Battle</title>
    <meta name="keywords" content="Freestyle Battle" />
    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
    <!-- <link rel="stylesheet" type="text/css" href="css/battle.css?v=1"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  </head>
  <body>

    <div class="container-sm w-75">

      <br><br>

      <div class="table-responsive">

        <table class="table caption-top table-hover">
          <caption>IN CORSO/CONCLUSI</caption>
          <thead>
            <tr>
              <th scope="col">SFIDANTE 1</th>
              <th scope="col">SFIDANTE 2</th>
              <th scope="col">STATO</th>
              <th scope="col">VINCITORE</th>
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
                foreach ($connessione->query("SELECT id, guest_one, guest_two, vote_one, vote_two, status, g1_dir, g2_dir, winner FROM battles WHERE guest_one != '$logged_user' AND guest_two != '$logged_user'") as $battle)
                {

                  $battle_id = $battle['id'];

                  $g1 = $battle['guest_one'];
                  $g2 = $battle['guest_two'];

                  $g1_vote = $battle['vote_one'];
                  $g2_vote = $battle['vote_two'];

                  $g1_dir = $battle['g1_dir'];
                  $g2_dir = $battle['g2_dir'];

                  $winner = $battle['winner'];

                  if ($g1_vote > $g2_vote) {
                    $stmt = $connessione->prepare("UPDATE battles SET winner = '$g1' WHERE id = $battle_id");
                    $winner = $g1;
                  }elseif ($g2_vote > $g1_vote) {
                    $stmt = $connessione->prepare("UPDATE battles SET winner = '$g2' WHERE id = $battle_id");
                    $winner = $g2;
                  }elseif ($g1_vote == $g2_vote) {
                    $stmt = $connessione->prepare("UPDATE battles SET winner = 'Pareggio' WHERE id = $battle_id");
                    $winner = 'Pareggio';
                  }

                  $stmt->execute();

                  $i = 0;

                  foreach (glob($g1_dir . '*.wav') AS $wav) {

                    $i++;

                    $wav = "'" . $wav . "'";

                    $g1_lines .= '<input style="display:inline; cursor: pointer;" class="btn btn-primary btn-sm" type="button" onclick="play(' . $wav . ')" value="' . $i . '"> - ';

                  }

                  $i = 0;

                  foreach (glob($g2_dir . '*.wav') AS $wav2) {

                    $i++;

                    $wav2 = "'" . $wav2 . "'";

                    $g2_lines .= '<input style="display:inline; cursor: pointer;" class="btn btn-primary btn-sm" type="button" onclick="play(' . $wav2 . ')" value="' . $i . '"> - ';

                  }

                  $g1_lines = substr_replace($g1_lines, "", -2);
                  $g2_lines = substr_replace($g2_lines, "", -2);

                    echo '

                    <tr>
                      <td><b>' . $battle['guest_one'] . '</b> (' . $battle['vote_one'] . ' like) <a style="cursor: pointer;" onclick="vote(1, ' . $battle['vote_one'] . ', ' . $battle['id'] . ')"><img src="https://img.icons8.com/external-kmg-design-glyph-kmg-design/20/000000/external-like-feedback-kmg-design-glyph-kmg-design.png"/></a> <img src="https://img.icons8.com/fluency/25/000000/arrow.png"/> <br>' . $g1_lines . '</td>
                      <td><b>' . $battle['guest_two'] . '</b> (' . $battle['vote_two'] . ' like) <a style="cursor: pointer;" onclick="vote(2, ' . $battle['vote_two'] . ', ' . $battle['id'] . ')"><img src="https://img.icons8.com/external-kmg-design-glyph-kmg-design/20/000000/external-like-feedback-kmg-design-glyph-kmg-design.png"/></a> <img src="https://img.icons8.com/fluency/25/000000/arrow.png"/> <br>' . $g2_lines . '</td>
                      <td><i>' . $battle['status'] . '</i></td>
                      <td>' . $winner . '</td>
                    </tr>

                    ';

                    $g1_lines = '';
                    $g2_lines = '';

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

                <!-- <tr style="border-bottom: 2px solid black">
                  <td></td>
                  <td>ASCOLTA <img src="https://img.icons8.com/fluency/25/000000/arrow.png"/></td>
                  <td><b>(' . $battle['guest_one'] . ')</b> ' . $g1_lines . '</td>
                  <td><b>(' . $battle['guest_two'] . ')</b> ' . $g2_lines . '</td>
                </tr> -->

              </tbody>
          </table>
        </div>
      </div>

    <script type="text/javascript">

      var base = new Audio("beat/base.mp3");
      base.loop = false;
      base.volume = 0.3;

      function vote(guest, currentvote, battle_id){

        const xhttp = new XMLHttpRequest();
        xhttp.open("GET", "vote.php?battle_id=" + battle_id + "&guest=" + guest + "&currentvote=" + currentvote + "&auth=1", false);
        xhttp.send();

        location.reload();

      }

      function play(path){

        var track = new Audio(path);
        track.loop = false;

        base.play();
        track.play();

        track.addEventListener("ended", function(){
          base.pause();
        });

      }

    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

  </body>
</html>
