<?php

session_start();
require('db/config.php');

if (isset($_SESSION['session_id'])) {
    header('Location: index.php');
    exit;
}

if (isset($_POST['login'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // echo "<br> USR_INPUT: " . $username;
    // echo "<br> PSW_INPUT: " . $password;

    if (empty($username) || empty($password)) {
        $msg = 'Inserisci username e password %s';
    } else {
        $query = "
            SELECT username, password
            FROM users
            WHERE username = :username
        ";

        $check = $pdo->prepare($query);
        $check->bindParam(':username', $username, PDO::PARAM_STR);
        $check->execute();

        $user = $check->fetch(PDO::FETCH_ASSOC);

        if (!$user || password_verify($password, $user['password']) === false) {
            $msg = 'Credenziali utente errate %s';

        } else {
            session_regenerate_id();
            $_SESSION['session_id'] = session_id();
            $_SESSION['session_user'] = $user['username'];
        }
    }

    printf($msg, '<a href="login.php">torna indietro...</a>');

}else {
    header('Location: index.php');
    exit();
}

header("Location: index.php");
exit;

?>
