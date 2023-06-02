<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "books";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['username'];
$user_id = "SELECT id FROM users WHERE username = '$user_id'";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT authors.name, authors.surname, books.id, books.title, books.description, books.cover_image, genres.name as genre, books.publication_date, books.rating as rating FROM books, authors, genres WHERE books.author_id = authors.id AND books.genre_id = genres.id AND books.user_id = ($user_id)";
$result = $conn->query($sql);

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $bookId = $_GET['id'];

    // check if the user owns the book
    $checkSql = "SELECT * FROM books WHERE id = ? AND user_id = ($user_id)";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("i", $bookId);
    $stmt->execute();
    $checkResult = $stmt->get_result();

    if ($checkResult->num_rows === 0) {
        echo "<script>alert('You do not have permission to delete this book.');</script>";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    $deleteSql = "DELETE FROM books WHERE id = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("i", $bookId);
    $stmt->execute();
    $deleteResult = $stmt->get_result();


    if ($deleteResult) {
        echo "<script>alert('Book deleted successfully.');</script>";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "<script>alert('Failed to delete book.');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-4">

        <h1 class="text-3xl font-bold mb-4">Books</h1>

        <a href="./books.php"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded material-icons">home</a>
        <a href="add_book.php"
            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded material-icons">add</a>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $bookId = $row["id"];
                    $title = $row["title"];
                    $author = $row["name"] . " " . $row["surname"];
                    ?>
                    <div class="bg-white rounded-lg overflow-hidden shadow-lg">
                        <div class="p-4">
                            <h2 class="text-xl font-bold mb-2">
                                <?php echo $title; ?>
                            </h2>
                            <p class="mb-2">Author:
                                <?php echo $author; ?>
                            </p>
                            <div class="flex space-x-2">
                                <a href="book_details.php?id=<?php echo $bookId; ?>" class="text-blue-500 hover:underline">View
                                    Details</a>
                                <a href="book_edit.php?id=<?php echo $bookId; ?>"
                                    class="text-green-500 hover:underline">Edit</a>
                                <a href="bookslist.php?action=delete&id=<?php echo $bookId; ?>"
                                    onclick="return confirm('Are you sure you want to delete this book?')"
                                    class="text-red-500 hover:underline">Delete</a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<p class="text-center">No books found.</p>';
            }
            ?>
        </div>
    </div>
</body>

</html>