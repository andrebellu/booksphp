<?php
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "books";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM authors";
$result = $conn->query($sql);

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $authorId = $_GET['id'];

    $deleteSql = "DELETE FROM authors WHERE id = $authorId";
    $deleteResult = $conn->query($deleteSql);

    if ($deleteResult) {
        echo "<script>alert('Author deleted successfully.');</script>";
        header("Location: " . $_SERVER['PHP_SELF']);
    } else {
        echo "<script>alert('Failed to delete author.');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authors</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

</head>

<body class="bg-gray-100">

    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Authors</h1>

        <a href="./books.php"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded material-icons mb-4">home</a>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $authorId = $row["id"];
                    $name = $row["name"];
                    $surname = $row["surname"];
                    ?>
                    <div class="bg-white rounded-lg overflow-hidden shadow-lg">
                        <div class="p-4">
                            <h2 class="text-xl font-bold mb-2">
                                <?php echo $name . " " . $surname; ?>
                            </h2>
                            <div class="flex space-x-2">
                                <a href="author_details.php?id=<?php echo $authorId; ?>"
                                    class="text-blue-500 hover:underline">View Details</a>
                                <a href="author_edit.php?id=<?php echo $authorId; ?>"
                                    class="text-green-500 hover:underline">Edit</a>
                                <a href="authors.php?action=delete&id=<?php echo $authorId; ?>"
                                    onclick="return confirm('Are you sure you want to delete this author?')"
                                    class="text-red-500 hover:underline">Delete</a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<p class="text-center">No authors found.</p>';
            }
            ?>
        </div>
    </div>
</body>

</html>