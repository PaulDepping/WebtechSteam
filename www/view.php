<?php
session_start();
include_once "config.php"
    ?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css\viewstyle1.css">
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

        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['delete_series_id'])) {
            DeleteWatched($_POST['delete_series_id']);
            header("Location: {$_SERVER['REQUEST_URI']}", true, 303); // PRG-Pattern
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_series_id"])) {
            ChangeWatchlist($_POST['edit_series_id'], null, $_POST['edit_title'] ?? null, $_POST['edit_staffeln'] ?? null, $_POST['edit_genre'] ?? null, $_POST['edit_platform'] ?? null, $_POST['edit_rating'] ?? null);
            header("Location: {$_SERVER['REQUEST_URI']}", true, 303); // PRG-Pattern
        }

        //Abfrage der Nutzer ID vom Login
        $userid = $_SESSION['user_id'];
        $username = $_SESSION['user_name'];

        $filter_data = array();
        if ($_SERVER["REQUEST_METHOD"] == "GET" && (isset($_GET['ftitel']) || isset($_GET['fgenre']) || isset($_GET['fplatform']))) {
            if (isset($_GET['ftitel'])) {
                $filter_data['title'] = $_GET['ftitel'];
            }
            if (isset($_GET['fgenre'])) {
                $filter_data['genre'] = $_GET['fgenre'];
            }
            if (isset($_GET['fplatform'])) {
                $filter_data['platform'] = $_GET['fplatform'];
            }
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["addBtn"])) {
            $title = $_POST['titel'];
            $staffeln = $_POST['staffeln'];
            $genre = $_POST['genre'];
            $platform = $_POST['plattform'];
            $rating = $_POST['rating'];
            AddToWatchlist($userid, $title, $staffeln, $genre, $platform, $rating);
            header("Location: {$_SERVER['REQUEST_URI']}", true, 303); // PRG-Pattern
        }



        ?>
    </div>


    <div class="container_top">
        <form method="get">
            <input type="text" id="ftitel" name="ftitel" placeholder="Suche nach Titel">
            <input type="text" id="fgenre" name="fgenre" placeholder="Suche nach Genre">
            <input type="text" id="Plattform" name="fplatform" placeholder="Suche nach Plattform">
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
                    <th class="text_center">Rating</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <<<<<<< HEAD <td><button type="submit" id="addBtn" name="addBtn" value="submit" form="add_form"><img
                                src="css/addbtn.png" /></button></td>
                        <td class="placeholderAdd"><input type="text" id="titel" name="titel" form="add_form"
                                placeholder="Titel eingeben" required></td>
                        <td class="placeholderAdd"><input type="number" id="staffeln" name="staffeln" form="add_form"
                                placeholder="Staffeln eingeben" required></td>
                        <td class="placeholderAdd"><input type="text" id="genre" name="genre" form="add_form"
                                placeholder="Genre eingeben" required></td>
                        <td class="placeholderAdd"><input type="text" id="plattform" name="plattform" form="add_form"
                                placeholder="Streaming-Plattform eingeben" required></td>
                        =======
                        <td><button type="submit" id="addBtn" name="addBtn" value="submit" form="add_form"><img
                                    src="css/addbtn.png" /></button></td>
                        <td class="placeholderAdd"><input type="text" id="titel" name="titel" form="add_form"
                                placeholder="Titel eingeben" required></td>
                        <td class="placeholderAdd"><input type="number" id="staffeln" name="staffeln" form="add_form"
                                placeholder="Staffeln eingeben" required></td>
                        <td class="placeholderAdd"><input type="text" id="genre" name="genre" form="add_form"
                                placeholder="Genre eingeben" required></td>
                        <td class="placeholderAdd"><input type="text" id="plattform" name="plattform" form="add_form"
                                placeholder="Streaming-Plattform eingeben" required></td>
                        <td class="placeholderAdd"><input type="range" min="0" max="5" step="1" id="rating"
                                name="rating" form="add_form" placeholder="Rating (0-5) eingeben" required></td>
                        >>>>>>> dc44612 (added rating)
                        <td></td>
                        <td></td>
                </tr>
                <?php
                $watchlist = GetWatchlist($userid, $filter_data);

                $i = 0;
                foreach ($watchlist['watched'] as $row) {
                    $i += 1;
                    echo '
                <tr>
                    <td class="text_center">' . $i . '</td>
                    <td class="text_right">' . $row['title'] . '</td>
                    <td class="text_right">' . $row['seasons'] . '</td>
                    <td class="text_right">' . $row['genre'] . '</td>
                    <td class="text_right">' . $row['platform'] . '</td>
                    <td class="text_right">' . $row['rating'] . '</td>
                    <td> <button onclick="deleteId(' . $row['id'] . ')" id="deleteBtn"><img src="css/delete.png"/></button>  </td>
                    <td> <button onclick="openEditForm(' . $row['id'] . ')" id="editBtn"><img src="css/edit.png"/></button>  </td>
                </tr>';
                }
                ?>

            </tbody>
        </table>
    </div>
    <!-- edit Form -->
    <div class="form-popup" id="myForm">
        <!-- <form action="" class="form-container" method="post"> -->
        <h1>Edit</h1>
        <div class=bittefunktionier>
            <input type="text" placeholder="Titel" name="titel" id="edit_title">
            <input type="number" placeholder="Staffeln" name="staffel" id="edit_staffeln">
            <input type="text" placeholder="Genre" name="genre" id="edit_genre">
            <input type="text" placeholder="Plattform" name="plattform" id="edit_platform">
            <input type="range" min="0" max="5" step="1" placeholder="Rating" name="rating" id="edit_rating">
            <button class="btn" onclick="submit_edit()">Bestätigen</button>
            <button type="button" class="btn cancel" onclick="closeEditForm()">Verwerfen</button>
        </div>
        <!-- </form> -->
    </div>
    <script type="text/javascript">
        var last_edit_id = -1;
        function openEditForm(id) {
            last_edit_id = id;
            let el = document.getElementById("myForm");
            el.style.display = "block";
        }

        function closeEditForm() {
            last_edit_id = -1;
            document.getElementById("myForm").style.display = "none";
        }

        function submit_edit() {
            let series_id = last_edit_id;
            last_edit_id = -1;
            console.assert(series_id != -1);
            let edit_title = document.getElementById("edit_title").value;
            let edit_staffeln = document.getElementById("edit_staffeln").value;
            let edit_genre = document.getElementById("edit_genre").value;
            let edit_platform = document.getElementById("edit_platform").value;
            let edit_rating = document.getElementById("edit_rating").value;

            let changed_form = new FormData();
            changed_form.append('edit_series_id', series_id);
            if (edit_title.length != 0) {
                changed_form.append('edit_title', edit_title);
            }
            if (edit_staffeln.length != 0) {
                changed_form.append('edit_staffeln', edit_staffeln);
            }
            if (edit_genre.length != 0) {
                changed_form.append('edit_genre', edit_genre);
            }
            if (edit_platform.length != 0) {
                changed_form.append('edit_platform', edit_platform);
            }
            if (edit_platform.length != 0) {
                changed_form.append('edit_platform', edit_platform);
            }
            if (edit_rating.length != 0) {
                changed_form.append('edit_rating', edit_rating);
            }
            fetch(window.location.href, { method: "POST", body: changed_form }).then(() => { location.reload(); });
        }

        function deleteId(id) {
            // alert(line.parentNode.parentNode.innerText)
            let form = new FormData();
            form.append("delete_series_id", id);
            fetch(window.location.href, {
                method: "POST",
                body: form,
            }).then(() => { location.reload(); });
        }
    </script>

</body>

</html>