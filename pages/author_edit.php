<?php
// Prevent caching of the page
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "books";

// Create a new connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $authorId = $_POST["id"];
    $name = $_POST["name"];
    $surname = $_POST["surname"];

    // Update the author details in the database
    $sql = "UPDATE authors SET name = '$name', surname = '$surname' WHERE id = $authorId";
    $result = $conn->query($sql);

    if ($result) {
        echo "<script>alert('Author details updated successfully.');</script>";
        header("Location: ./authors.php");
    } else {
        echo "<script>alert('Failed to update author details.');</script>";
    }
}

// Fetch the author details from the database
$authorId = $_GET["id"];
$sql = "SELECT * FROM authors WHERE id = $authorId";
$result = $conn->query($sql);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Author</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Edit Author</h1>

        <?php
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $authorId = $row["id"];
            $name = $row["name"];
            $surname = $row["surname"];
            ?>
            <form action="" method="post" class="max-w-md bg-white rounded-lg overflow-hidden shadow-lg p-4">
                <input type="hidden" name="id" value="<?php echo $authorId; ?>">
                <div class="mb-4">
                    <label for="name" class="block font-bold mb-2">Name:</label>
                    <input type="text" name="name" id="name" value="<?php echo $name; ?>"
                        class="w-full border border-gray-300 rounded-md p-2">
                </div>
                <div class="mb-4">
                    <label for="surname" class="block font-bold mb-2">Surname:</label>
                    <input type="text" name="surname" id="surname" value="<?php echo $surname; ?>"
                        class="w-full border border-gray-300 rounded-md p-2">
                </div>
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update</button>
            </form>
            <?php
        } else {
            echo '<p class="text-center">Author not found.</p>';
        }
        ?>
    </div>
</body>

</html>