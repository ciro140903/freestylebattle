<?php

include 'session_checker.php';
include 'db/config_v2.php';

$battle_id = $_GET['battle_id'];

if (!isset($battle_id)) {
  header('Location: index.php');
  exit;
}

$logged_user = $_SESSION['session_user'];


try {

  $connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
  $connessione->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // DETERMINA LA DIRECTORY DELL'UTENTE LOGGATO E QUELLA DELL'AVVERSARIO
  foreach ($connessione->query("SELECT guest_one, guest_two, g1_dir, g2_dir FROM battles WHERE id = $battle_id") as $out) {

    $g1 = $out['guest_one'];
    $g2 = $out['guest_two'];

    if ($logged_user == $g1) {
      $userdir1 = $out['g1_dir'];
      $opponent1 = $out['g2_dir'];
    }elseif ($logged_user == $g2) {
      $userdir1 = $out['g2_dir'];
      $opponent1 = $out['g1_dir'];
    }


    // PEZZI NELLA DIRECTORY DELL'UTENTE LOGGATO
    $self_counter = 0;
    $files = glob($userdir1 . "*");
    if ($files){
      $self_counter = count($files);
    }

    // PEZZI NELLA DIRECTORY DELL'AVVERSARIO
    $opponent_counter = 0;
    $files2 = glob($opponent1 . "*");
    if ($files2){
      $opponent_counter = count($files2);
    }

    //echo "SELF --> " . $self_counter . "<br>OPPONENT --> " . $opponent_counter . "<br>USERDIR --> " . $userdir1 . "<br>OPPONENT DIR --> " . $opponent1;

  }

  include 'check_and_accept.php';

  $connessione = null;
}
catch(PDOException $e)
{
  echo $e->getMessage();
}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0"/>
    <title>Record | Freestyle Battle</title>
    <meta name="keywords" content="Freestyle Battle" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
    <meta name="battle_id" content="<?php echo $battle_id; ?>" />
    <link rel="stylesheet" href="/css/record.css?v=1">
  </head>
  <body>

      <?php

      if ($self_counter == 3 AND $opponent_counter == 3) {

        try {
        $connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
        $connessione->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        foreach ($connessione->query("SELECT vote_one, vote_two FROM battles WHERE id = $battle_id") as $out) {

          $voteone = $out['vote_one'];
          $votetwo = $out['vote_two'];

        }

        if ($voteone > $votetwo) {
          $winner = $g1;
        }elseif ($votetwo > $voteone) {
          $winner = $g2;
        }elseif ($voteone == $votetwo) {
          $winner = 'Pareggio';
        }

        $aggiorna = $connessione->exec("UPDATE battles SET status = 'Chiuso' WHERE id = $battle_id");
        $aggiorna = $connessione->exec("UPDATE battles SET winner = '$winner' WHERE id = $battle_id");

        // chiusura della connessione
        $connessione = null;
        }
        catch(PDOException $e)
        {
        // notifica in caso di errore nel tentativo di connessione
        echo $e->getMessage();
        }

        echo '

        <h2 style="text-align: center; font-family: Arial;">FREESTYLE CHIUSO</h2>

        ';

      }else {

        if ($self_counter == $opponent_counter || $self_counter + 1 == $opponent_counter) {

          if ($self_counter == 5) {

            echo '

            <h2 style="text-align: center; font-family: Arial;">HAI FINITO LA TUA PARTE</h2>

            ';

          }else {
            echo '

            <div id="controls">
             <button id="recordButton">Record</button>
             <button id="pauseButton" disabled>Pause</button>
             <button id="stopButton" disabled>Stop</button>
            </div>


            <div class="output_container">

              <div id="formats"></div>
              <ol id="recordingsList"></ol>

            </div>

              <br><br>

              <h2 style="text-align: center; font-family: Arial;">AGGIORNA LA PAGINA DOPO OGNI SALVATAGGIO</h2>



            ';
          }

        }else {

          echo '

          <h2 style="text-align: center; font-family: Arial;">ATTENDI CHE L\'AVVERSARIO REGISTRI LA SUA PARTE</h2>

          ';

        }

      }

      ?>

    <!-- inserting these scripts at the end to be able to use all the elements in the DOM -->
  	<script src="js/recorder.js"></script>
  	<script src="js/app.js?1"></script>

  </body>
</html>
