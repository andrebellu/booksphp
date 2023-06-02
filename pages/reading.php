<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "books";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $bookId = $_GET['id'];

    $user = $_SESSION['username'];
    $userQuery = "SELECT id FROM users WHERE username = '$user'";
    $userResult = $conn->query($userQuery);

    if ($userResult->num_rows > 0) {
        $row = $userResult->fetch_assoc();
        $userId = $row["id"];

        $updateSql = "UPDATE books SET status = '1' WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("ii", $bookId, $userId);

        if ($stmt->execute()) {
            echo "<script>alert('Book status updated to read.');</script>";
            header("Location: books.php");
            exit;
        } else {
            echo "<script>alert('Failed to update book status.');</script>";
        }
    } else {
        echo "<script>alert('User not found.');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark as Done</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Mark Book as Done</h1>

        <div class="bg-white rounded-lg p-4 shadow-lg">
            <p>Are you sure you want to mark this book as read?</p>
            <div class="mt-4">
                <a href="done.php?id=<?php echo $_GET['id']; ?>"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Mark as Read</a>
                <a href="books.php"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded ml-2">Cancel</a>
            </div>
        </div>
    </div>
</body>

</html>