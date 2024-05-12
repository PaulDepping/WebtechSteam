<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale">
    <link rel="stylesheet" href="css/styles.css">
    <title>Registration Steam™</title>
</head>

<body>
    <div class="login">
        <h1>Steam™</h1>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            include_once "config.php";
            $error = false;
            $username = $_POST['username'];
            $password = $_POST['password'];
            $password2 = $_POST['password2'];

            //if (strlen($username) == 0) {
            //    echo '<Bitte ein Nutzernamen angeben<br>';
            //    $error = true;
            //}
            //if (strlen($password) == 0) {
            //    echo 'Bitte ein Passwort angeben<br>';
            //    $error = true;
            //}
            if ($password != $password2) {
                echo "<p class=\"error\">Die Passwörter müssen übereinstimmen</p>";
                $error = true;
            }

            //Überprüfe, dass der Nutzer noch nicht registriert wurde
            if (!$error) {
                $statement = $mysqli->prepare("SELECT id FROM Users WHERE username = ?");
                $statement->bind_param("s", $username);
                $result = $statement->execute();

                if (!$result) {
                    echo 'SQL Error!<br>';
                    $error = true;
                } else {
                    $res = $statement->get_result();

                    if (mysqli_num_rows($res) > 0) {
                        echo "<p class=\"error\">Benutzer existiert bereits!</p>";
                        $error = true;
                    }
                }
            }

            //Keine Fehler, wir können den Nutzer registrieren
            if (!$error) {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);

                $statement = $mysqli->prepare("INSERT INTO Users (username, password_hash) VALUES (?, ?)");
                $statement->bind_param("ss", $username, $password_hash);
                $result = $statement->execute();

                if ($result) {
                    echo "<p class=\"info\">Du wurdest erfolgreich registriert. <a href=\"login.php\">Zum Login</a></p>";
                    $showFormular = false;
                } else {
                    echo "<p class=\"error\">Beim Abspeichern ist leider ein Fehler aufgetreten</p>";
                }
            }
        }
        ?>
        <form method="POST">
            <input type="text" id="name" placeholder="Benutzername" name="username" required><br>
            <input type="password" id="password" name="password" placeholder="Passwort" required><br>
            <input type="password" id="password2" name="password2" placeholder="Passwort wiederholen" required><br>
            <button type="submit">Registrieren</button><br>
            <p>zurück zum <a href="login.php">Login</a></p>
        </form>


</body>

</html>