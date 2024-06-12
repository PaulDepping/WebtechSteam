<?php
session_start();
include_once "config.php"
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/viewstyle.css">
    <title>STEAM</title>
</head>

<body>
    <div class="contain-header">
        <h1> &zwnj; </h1>
        <form action="logout.php"><input type="submit" value="Log out"></form>
    </div>

    <div>
        <?php

        if (!isset($_SESSION['user_id'])) {
            exit('Bitte zuerst <a href="login.php">einloggen</a>');
        }

        //Abfrage der Nutzer ID vom Login
        $userid = $_SESSION['user_id'];
        $username = $_SESSION['user_name'];

        $filter_data = false;
        if ($_SERVER["REQUEST_METHOD"] == "GET" && (isset($_GET['ftitel']) || isset($_GET['fgenre']) || isset($_GET['fplatform']))) {
            $filter_data = true;
            $filter_titel = isset($_GET["ftitel"]) ? '%' . $_GET['ftitel'] . '%' : '%';
            $filter_genre = isset($_GET["fgenre"]) ? '%' . $_GET['fgenre'] . '%' : '%';
            $filter_platform = isset($_GET["fplatform"]) ? '%' . $_GET['fplatform'] . '%' : '%';
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["addBtn"])) {
            $title = $_POST['titel'];
            $staffeln = $_POST['staffeln'];
            $genre = $_POST['genre'];
            $plattform = $_POST['plattform'];

            $stmt = $mysqli->prepare('INSERT INTO Watching (user_id, title, seasons, genre, platform) VALUES (?, ?, ?, ?, ?)');
            if ($stmt === false) {
                exit('error in sql insert1');
            }
            $rc = $stmt->bind_param('isiss', $userid, $title, $staffeln, $genre, $plattform);
            if (!$rc) {
                exit('error in sql insert2');
            }
            $rc = $stmt->execute();
            if (!$rc) {
                exit('error in sql insert3');
            }
        }


        ?>
    </div>


    <div class="container_top">
        <form method="get">
            <input type="text" id="ftitel" name="ftitel" placeholder="Titel">
            <input type="text" id="fgenre" name="fgenre" placeholder="Genre">
            <input type="text" id="Plattform" name="fplatform" placeholder="Plattform">
            <button type="submit" id="search">Suchen</button>
        </form>
    </div>
    <div class="container_bot">
        <form id="add_form" name="add_form" method="post"></form>
        <table class="table-container">
            <thead>
                <tr>
                    <th id="singleHashtag">#</th>
                    <th class="text_center">Titel</th>
                    <th class="text_center">Anz. Staffeln</th>
                    <th class="text_center">Genre</th>
                    <th class="text_center">Streaming-Plattform</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text_center"><button type="submit" id="addBtn" name="addBtn" value="submit" form="add_form">+</button></td>
                    <td><input type="text" id="titel" name="titel" form="add_form" required></td>
                    <td><input type="number" id="staffeln" name="staffeln" form="add_form" required></td>
                    <td><input type="text" id="genre" name="genre" form="add_form" required></td>
                    <td><input type="text" id="plattform" name="plattform" form="add_form" required></td>
                    <td></td>
                    <td></td>
                </tr>
                <?php
                $extra_elements = 0;
                $push_elements = [];
                if ($filter_data) {
                    $sql_string = 'SELECT title, seasons, genre, platform
                     FROM Watching
                     WHERE user_id = ?
                     AND title LIKE ?
                     AND genre LIKE ?
                     AND platform LIKE ?';
                } else {
                    $sql_string = 'SELECT title, seasons, genre, platform
                     FROM Watching
                     WHERE user_id = ?';
                }

                $stmt = $mysqli->prepare($sql_string);
                if ($filter_data) {
                    $rc = $stmt->bind_param("isss", $userid, $filter_titel, $filter_genre, $filter_platform);
                } else {
                    $rc = $stmt->bind_param("i", $userid);
                }
                if (!$rc) {
                    exit('error in sql get1');
                }

                $rc = $stmt->execute();
                if (!$rc) {
                    exit('error in sql get2');
                }

                $res = $stmt->get_result();

                if ($res === false) {
                    exit('error in sql get3');
                }

                $i = 0;
                while (true) {
                    $row = mysqli_fetch_array($res);
                    if ($row === false) {
                        exit('error in row iteration');
                    }
                    if (is_null($row)) {
                        break;
                    }
                    $i = $i + 1;
                    echo '
                <tr>
                    <td class="text_center">' . $i . '</td>
                    <td class="text_right">' . $row['title'] . '</td>
                    <td class="text_right">' . $row['seasons'] . '</td>
                    <td class="text_right">' . $row['genre'] . '</td>
                    <td class="text_right">' . $row['platform'] . '</td>
                    <td> <button onclick="deleteLine(this)" id="deleteBtn">X</button>  </td>
                    <td> <button onclick="openEditForm()" id="editBtn">Edit</button>  </td>
                </tr>';
                }

                ?>

            </tbody>
        </table>
    </div>
    <!-- edit Form -->
    <div class="form-popup" id="myForm">
        <form action="/action_page.php" class="form-container">
            <h1>Edit</h1>

            <input type="text" placeholder="Titel" name="titel" required>
            <input type="number" placeholder="Staffeln" name="staffel" required>
            <input type="text" placeholder="Genre" name="genre" required>
            <input type="text" placeholder="Plattform" name="plattform" required>

            <button type="submit" class="btn">Bestätigen</button>
            <button type="button" class="btn cancel" onclick="closeEditForm()">Verwerfen</button>
        </form>
    </div>
    <script type="text/javascript">
        function openEditForm() {
            document.getElementById("myForm").style.display = "block";
        }

        function closeEditForm() {
            document.getElementById("myForm").style.display = "none";
        }

        function deleteLine(line) {
            // alert(line.parentNode.parentNode.innerText)
            var row = line.closest('tr')
            var cells = row.getElementsByTagName('td')
            alert("line:" + cells[0].innerText + ", Titel:" + cells[1].innerText + ", Staffel:" + cells[2].innerText + "...")
        }
    </script>

</body>

</html>
