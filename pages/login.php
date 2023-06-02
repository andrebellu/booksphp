<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_SESSION['username'])) {
    header("Location: books.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $servername = "localhost";
    $username_db = "root";
    $password_db = "";
    $dbname = "books";

    $conn = new mysqli($servername, $username_db, $password_db, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $query = "SELECT password FROM users WHERE username = '$username'";

    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo "<p class='mt-4 text-red-500'>Something went wrong. Please try again later.</p>";
        exit;
    }

    $row = mysqli_fetch_assoc($result);

    if (!password_verify($password, $row['password'])) {
        echo "<p class='mt-4 text-red-500'>Invalid username or password.</p>";
        exit;
    }

    $_SESSION['username'] = $username;
    header("Location: ./books.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex items-center justify-center min-h-screen">
        <div class="flex w-full max-w-screen-xl row-reverse">
            <!-- Left Side - Image -->
            <div class="hidden md:block w-1/2 bg-gray-300">
                <img src="https://images.unsplash.com/photo-1495640452828-3df6795cf69b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=774&q=80
" alt="Registration Image" class="object-cover w-full h-full">
            </div>

            <!-- Right Side - Registration Form -->
            <div class="w-full md:w-1/2 bg-white p-8">
                <h1 class="text-2xl font-bold mb-4">Login</h1>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <div class="mb-4">
                        <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Username:</label>
                        <input type="text" name="username" id="username"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                            required>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password:</label>
                        <input type="password" name="password" id="password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                            required>
                    </div>
                    <div class="flex justify-center">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>