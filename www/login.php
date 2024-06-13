<?php
session_start();
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale">
    <link rel="stylesheet" href="css/styles.css">
    <title>Login Steam™</title>
</head>

<body>

    <div class="login">
        <h1>Steam™</h1>

        <?php

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            include_once "config.php";
            $username = $_POST['username'];
            $password = $_POST['password'];

            $result = GetUserData($username);
            if ($result['found'] && password_verify($password, $result['password_hash'])) {
                $_SESSION['user_name'] = $result['username'];
                $_SESSION['user_id'] = $result['id'];
                header('Location: view.php'); // redirect
                exit;
            } else {
                $errorMessage = "<p class=\"error\"><strong>Ungültiger Username oder Passwort!</strong></p>";
            }
        }

        if (isset($errorMessage)) {
            echo $errorMessage;
        }
        ?>

        <form method="post">
            <input type="text" id="name" placeholder="Benutzername" name="username" required><br>
            <input type="password" id="password" name="password" placeholder="Passwort" required><br>
            <button type="submit">Login</button><br>
            <p>Noch kein Account? <a href="register.php">Registriere dich hier!</a></p>
        </form>
    </div>
</body>

</html>