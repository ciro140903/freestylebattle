<?php

include 'session_checker.php';
include 'db/config_v2.php';

if (!isset($_GET['auth'])) {
  header('Location: battle.php');
  exit;
}

$logged_user = $_SESSION['session_user'];

$battle_id = $_GET['battle_id'];
$guestID = $_GET['guest'];
$currentvote = $_GET['currentvote'];

if ($guestID == 1) {
  $guest = 'vote_one';
}elseif ($guestID == 2) {
  $guest = 'vote_two';
}

$finded = false;

$dbh = new PDO("mysql:host=$host;dbname=$db", $user, $password);
$stmt = $dbh->prepare("SELECT vote_for FROM battle_like WHERE battle_id = $battle_id AND user = '$logged_user'");
$stmt->execute();

if($stmt->rowCount() > 0)
{

  $out = $stmt->fetch(PDO::FETCH_BOTH);

  $finded = true;

  $vote_for = $out['vote_for'];

  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// 1 - 1
  if ($guestID != $vote_for) { //SE NON HAI GIA' VOTATO QUEL GUEST

    $aggiorna_voti = $dbh->exec("UPDATE battles SET $guest = $guest + 1 WHERE id = $battle_id");

    if ($guest == 'vote_one') {
      $delete_vote = $dbh->exec("UPDATE battles SET vote_two = vote_two - 1 WHERE id = $battle_id");
    }elseif ($guest == 'vote_two') {
      $delete_vote = $dbh->exec("UPDATE battles SET vote_one = vote_one - 1 WHERE id = $battle_id");
    }

    $aggiorna_voti2 = $dbh->exec("UPDATE battle_like SET vote_for = '$guestID' WHERE battle_id = $battle_id AND user = '$logged_user'");

  }

}

if (!$finded) {

  $inserisci_dati = $dbh->exec("INSERT INTO battle_like (battle_id, user, vote_for) VALUES ('$battle_id', '$logged_user', '$guestID')");
  $aggiorna_voti = $dbh->exec("UPDATE battles SET $guest = $guest + 1 WHERE id = $battle_id");

}


//header('Location: battle.php');
//exit;

?>
