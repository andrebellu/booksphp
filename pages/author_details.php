<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "books";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$authorId = $_GET['id'];


$sql = "SELECT * FROM authors WHERE id = $authorId";
$result = $conn->query($sql);


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Author Details</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <?php

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $name = $row["name"];
            $surname = $row["surname"];
            ?>
            <h1 class="text-3xl font-bold mb-4">
                <?php echo $name . " " . $surname; ?>
            </h1>

            <div class="bg-white rounded-lg overflow-hidden shadow-lg">
                <div class="p-4">
                    <h2 class="text-xl font-bold mb-2">Author Details</h2>
                    <p><strong>Name:</strong>
                        <?php echo $name . " " . $surname; ?>
                    </p>
                    <p><strong>ID:</strong>
                        <?php echo $authorId; ?>
                    </p>
                </div>
            </div>
            <?php
        } else {
            echo '<p class="text-center">Author not found.</p>';
        }
        ?>
    </div>
</body>

</html>