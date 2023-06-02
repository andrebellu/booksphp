<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Genres</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Genres</h1>
        <a href="./books.php"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded material-icons mb-4">home</a>

        <?php

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "books";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if (!isset($_SESSION['username'])) {
            header("Location: ./login.php");
            exit;
        }

        $sql = "SELECT * FROM genres";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo '<div class="container mx-auto">';
            while ($row = $result->fetch_assoc()) {
                $genreName = $row["name"];
                $genreDescription = $row["description"];

                echo '<div class="bg-gray-100 p-4 rounded-md mb-4">
                    <h2 class="text-2xl font-bold mb-2">' . $genreName . '</h2>
                    <p class="text-gray-500">' . $genreDescription . '</p>
                  </div>';
            }
            echo '</div>';
        } else {
            echo '<p>No genres found.</p>';
        }

        $conn->close();
        ?>
    </div>

</body>

</html>