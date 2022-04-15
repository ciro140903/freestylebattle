<?php
require_once('db/config.php');

if (isset($_POST['register'])) {
    $name = $_POST['name'] ?? '';
    $surname = $_POST['surname'] ?? '';
    $year = $_POST['year'] ?? '';
    $email = $_POST['email'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $pwdLenght = mb_strlen($password);


    if (empty($username) || empty($password)) {
        $msg = 'Compila tutti i campi %s';
    } elseif (false === $isUsernameValid) {
        $msg = 'Lo username non è valido. Sono ammessi solamente caratteri
                alfanumerici e l\'underscore. Lunghezza minina 3 caratteri.
                Lunghezza massima 20 caratteri';
                header('Location: login.php?status=notvalid');
                exit;
    } elseif ($pwdLenght < 6 || $pwdLenght > 20) {
        $msg = 'Lunghezza minima password 6 caratteri.
                Lunghezza massima 20 caratteri';
                header('Location: login.php?status=lenght_error');
                exit;
    } else {
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $query = "
            SELECT id
            FROM users
            WHERE username = :username
        ";

        $check = $pdo->prepare($query);
        $check->bindParam(':username', $username, PDO::PARAM_STR);
        $check->execute();

        $user = $check->fetchAll(PDO::FETCH_ASSOC);

        if (count($user) > 0) {
            $msg = 'Username già in uso %s';
            header('Location: login.php?status=already_taken');
            exit;
        } else {

            $query = "
                INSERT INTO users
                VALUES (0, '" . $name . "', '" . $surname . "', '" . $year . "', '" . $email . "', :username, :password)
            ";

            $check = $pdo->prepare($query);
            $check->bindParam(':username', $username, PDO::PARAM_STR);
            $check->bindParam(':password', $password_hash, PDO::PARAM_STR);
            $check->execute();

            if ($check->rowCount() > 0) {
                $msg = 'Registrazione eseguita con successo';
                header('Location: login.php?status=ok');
                exit;
            } else {
                $msg = 'Problemi con l\'inserimento dei dati %s';
                header('Location: login.php?status=error');
                exit;
            }
        }
    }

    printf($msg, '<a href="register.php">torna indietro</a>');

}

?>
