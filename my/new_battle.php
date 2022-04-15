<?php

include '../session_checker.php';
include '../db/config_v2.php';

$logged_user = $_SESSION['session_user'];

/*
  blocco try/catch di gestione delle eccezioni
*/
try {
  // stringa di connessione al DBMS
  $connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
  // impostazione dell'attributo per il report degli errori
  $connessione->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $newid = $connessione->lastInsertId();
  $newid++;

  // popolo la tabella con due record
  $insert_new_battle = $connessione->exec("INSERT INTO battle_request (guest_one, guest_two, status) VALUES ('$logged_user', '', 'Aperto')");
  // chiusura della connessione
  $connessione = null;
}
catch(PDOException $e)
{
  // notifica in caso di errore nel tentativo di connessione
  echo $e->getMessage();
}

header('Location: dashboard.php');
exit;


?>
