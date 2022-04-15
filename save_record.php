<?php

include 'session_checker.php';

include 'db/config_v2.php';

$battle_id = $_GET['battle_id'];

/*
  blocco try/catch di gestione delle eccezioni
*/
try {
  // stringa di connessione al DBMS
  $connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
  // impostazione dell'attributo per il report degli errori
  $connessione->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  foreach ($connessione->query("SELECT guest_one, guest_two, g1_dir, g2_dir FROM battles WHERE id = $battle_id") as $out)
   {

     $g1 = $out['guest_one'];
     $g1_fulldir = $out['g1_dir'];
     $g2 = $out['guest_two'];
     $g2_fulldir = $out['g2_dir'];

   }

  // chiusura della connessione
  $connessione = null;
}
catch(PDOException $e)
{
  // notifica in caso di errore nel tentativo di connessione
  echo $e->getMessage();
}

$userdir = '';

if ($_SESSION['session_user'] == $g1) {
  $userdir = $g1_fulldir;
}elseif ($_SESSION['session_user'] == $g2) {
  $userdir = $g2_fulldir;
}

if ($userdir == '') {
  header('Location: index.php');
  exit;
}

$filecount = 0;
$files = glob($userdir . "*");
if ($files){
 $filecount = count($files);
}


print_r($_FILES);
//this will print out the received name, temp name, type, size, etc.
$input = $_FILES['audio_data']['tmp_name']; //get the temporary name that PHP gave to the uploaded file
$output = $userdir . $filecount . ".wav"; //letting the client control the filename is a rather bad idea
//$output = "recordings/3/g2/1.wav"; //letting the client control the filename is a rather bad idea
//move the file from temp name to local folder using $output name
move_uploaded_file($input, $output);

?>
