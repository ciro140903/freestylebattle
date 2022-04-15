<?php

include '../session_checker.php';
include '../db/config_v2.php';

$logged_user = $_SESSION['session_user'];

?>

<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Dashboard | Freestyle Battle</title>
    <meta name="keywords" content="Freestyle Battle" />
    <link rel="icon" type="image/x-icon" href="../img/favicon.ico">
    <!-- <link rel="stylesheet" type="text/css" href="../css/my/dashboard.css?v=1"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  </head>
  <body>

          <div class="container-sm w-75">

            <br><br>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
              <a class="btn btn-primary" href="new_battle.php" role="button">PUBBLICA SFIDA</a>
            </div>

            <br><br>

            <div class="table-responsive">

              <table class="table caption-top table-hover">
                <caption>LE MIE SFIDE APERTE</caption>
                <thead>
                  <tr>
                    <th scope="col">SFIDANTE 1</th>
                    <th scope="col">SFIDANTE 2</th>
                    <th scope="col">STATO</th>
                  </tr>
                </thead>
                <tbody>

                    <?php

                    try {
                    $connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
                    $connessione->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    foreach ($connessione->query("SELECT guest_one, status FROM battle_request WHERE guest_one = '$logged_user' AND status = 'Aperto'") as $battle)
                    {

                        echo '

                        <tr style="border-bottom: 2px solid black">
                          <td><b>' . $battle['guest_one'] . '</b></td>
                          <td>...............</td>
                          <td><i>' . $battle['status'] . '</i></td>
                        </tr>

                        ';

                    }
                    $connessione = null;
                    }
                    catch(PDOException $e)
                    {
                    echo $e->getMessage();
                    }

                    ?>

                  </tbody>
              </table>
            </div>

            <br><br><br><br>

            <div class="table-responsive">

              <table class="table caption-top table-hover">
                <caption>LE MIE SFIDE ACCETTATE/CHIUSE</caption>
                <thead>
                  <tr>
                    <th scope="col">SFIDANTE 1</th>
                    <th scope="col">SFIDANTE 2</th>
                    <th scope="col">STATO</th>
                    <th scope="col">VINCITORE</th>
                    <th scope="col">AZIONI</th>
                  </tr>
                </thead>
                <tbody>

                <?php

                try {
                $connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
                $connessione->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                foreach ($connessione->query("SELECT id, guest_one, guest_two, vote_one, vote_two, status, g1_dir, g2_dir, winner FROM battles WHERE guest_one = '$logged_user' OR guest_two = '$logged_user'") as $battle)
                {

                  $g1_dir = $battle['g1_dir'];
                  $g2_dir = $battle['g2_dir'];

                  $i = 0;

                  foreach (glob('../' . $g1_dir . '*.wav') AS $wav) {

                    $i++;

                    $wav = "'" . $wav . "'";

                    $g1_lines .= '<input style="display:inline; cursor: pointer;" class="btn btn-primary btn-sm" type="button" onclick="play(' . $wav . ')" value="' . $i . '"> - ';

                  }

                  $i = 0;

                  foreach (glob('../' . $g2_dir . '*.wav') AS $wav2) {

                    $i++;

                    $wav2 = "'" . $wav2 . "'";

                    $g2_lines .= '<input style="display:inline; cursor: pointer;" type="button" class="btn btn-primary btn-sm" onclick="play(' . $wav2 . ')" value="' . $i . '"> - ';

                  }

                  $g1_lines = substr_replace($g1_lines, "", -2);
                  $g2_lines = substr_replace($g2_lines, "", -2);

                    echo '

                    <tr>
                      <td><b>' . $battle['guest_one'] . '</b> (' . $battle['vote_one'] .' like) <br>' . $g1_lines . '</td>
                      <td><b>' . $battle['guest_two'] . '</b> (' . $battle['vote_two'] .' like) <br>' . $g2_lines . '</td>
                      <td><i>' . $battle['status'] . '</i></td>
                      <td>' . $battle['winner'] . '</td>
                      <td><a href="../record.php?battle_id=' . $battle['id'] . '" style="text-decoration: none; font-size: 20px;">➤</a></td>
                    </tr>

                    ';

                    $g1_lines = '';
                    $g2_lines = '';

                }
                $connessione = null;
                }
                catch(PDOException $e)
                {
                echo $e->getMessage();
                }

                ?>

              </tbody>
            </table>
          </div>

          </div>

    <script type="text/javascript">

    function play(path){

      var track = new Audio(path);
      var base = new Audio("../beat/base.mp3");
      base.volume = 0.3;
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
