<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $surname = $_POST['surname'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "books";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed:" . $conn->connect_error);
    }

    $query = "INSERT INTO authors (name, surname) VALUES ('$name', '$surname')";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo "<p class='mt-4 text-red-500'>Something went wrong. Please try again later.</p>";
        exit;
    }

    $conn->close();
    header("Location: authors.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Author</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex items-center justify-center h-screen">
        <div class="w-96 p-8 bg-white rounded shadow">
            <h1 class="text-2xl font-bold mb-6 text-center">Add Author</h1>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <div class="mb-4">
                    <label for="name" class="block mb-1">Name:</label>
                    <input type="text" id="name" name="name" required
                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label for="surname" class="block mb-1">Surname:</label>
                    <input type="text" id="surname" name="surname" required
                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:border-blue-500">
                </div>
                <div class="text-center">
                    <button type="submit"
                        class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Add Author
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>