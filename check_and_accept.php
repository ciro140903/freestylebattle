<?php

include 'session_checker.php';

$logged_user = $_SESSION['session_user'];

foreach ($connessione->query("SELECT id, guest_one, guest_two, status FROM battle_request WHERE id = $battle_id") as $out) {

  $br_guestone = $out['guest_one'];
  $br_guesttwo = $out['guest_two'];
  $status = $out['status'];

  if ($br_guesttwo == '') { // == AUTORIZZATO A CREARE

    if ($logged_user == $br_guestone) {
      $isnew = 0;
      $authorized = 1;
      break;
    }else {

      $update_g2 = $connessione->exec("UPDATE battle_request SET guest_two = '$logged_user' WHERE id = $battle_id");
      $isnew = 1;
      $authorized = 1;
      break;

    }

  }elseif ($br_guestone == $logged_user OR $br_guesttwo == $logged_user) {
    $isnew = 0;
    $authorized = 1;
    break;
  }

}

//echo "<br>AUTH: " . $authorized . "<br>NEW: " . $isnew . "<br>LOGGED USER: " . $logged_user . "<br>G1: " . $out['guest_one'] . "<br>G2: " . $out['guest_two'] . "<br>STATUS: " . $out['status'];

  //ACCOPPIA GIOCATORI
 if ($authorized AND $isnew) {

    $g1_dir = "recordings/" . $battle_id . "/";
    $g2_dir = "recordings/" . $battle_id . "/";

    $g1_fulldir = $g1_dir . "g1/";
    $g2_fulldir = $g2_dir . "g2/";

    mkdir($g1_dir);
    mkdir($g1_fulldir);

    mkdir($g2_dir);
    mkdir($g2_fulldir);

    $inserisci_dati = $connessione->exec("INSERT INTO battles (id, guest_one, guest_two, vote_one, vote_two, status, g1_dir, g2_dir, winner) VALUES ('$battle_id', '$br_guestone', '$logged_user', 0, 0, 'In corso...', '$g1_fulldir', '$g2_fulldir', 'Pareggio')");

    //cambio lo stato in chiuso in battle_request
    $update_status = $connessione->exec("UPDATE battle_request SET status = 'Chiuso' WHERE id = $battle_id");

 }else if (!$authorized) {
   header('Location: index.php');
   exit;
 }

?>
