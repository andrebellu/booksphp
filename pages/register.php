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
        <div class="flex w-full max-w-screen-xl">
            <!-- Left Side - Image -->
            <div class="hidden md:block w-1/2 bg-gray-300">
                <img src="https://images.unsplash.com/photo-1535905557558-afc4877a26fc?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=774&q=80"
                    alt="Registration Image" class="object-cover w-full h-full">
            </div>

            <!-- Right Side - Registration Form -->
            <div class="w-full md:w-1/2 bg-white p-8">
                <h1 class="text-2xl font-bold mb-4">Register</h1>
                <form action="" method="post">
                    <div class="mb-4">
                        <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Username:</label>
                        <input type="text" name="username" id="username"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                            required>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                        <input type="email" name="email" id="email"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                            required>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password:</label>
                        <input type="password" name="password" id="password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                            required>
                    </div>
                    <div class="mb-4">
                        <label for="confirm_password" class="block text-gray-700 text-sm font-bold mb-2">Confirm
                            Password:</label>
                        <input type="password" name="confirm_password" id="confirm_password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                            required>
                    </div>
                    <div class="flex justify-center">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Register</button>
                    </div>
                </form>
                <?php
                error_reporting(E_ALL);
                ini_set('display_errors', 1);
                if ($_SERVER["REQUEST_METHOD"] == "POST") {

                    $username = $_POST["username"];
                    $email = $_POST["email"];
                    $password = $_POST["password"];
                    $confirmPassword = $_POST["confirm_password"];


                    if ($password !== $confirmPassword) {
                        echo "<p class='mt-4 text-red-500'>Password and Confirm Password do not match.</p>";
                        exit;
                    }


                    $servername = "localhost";
                    $username_db = "root";
                    $password_db = "";
                    $dbname = "books";

                    $conn = new mysqli($servername, $username_db, $password_db, $dbname);

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }


                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);


                    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
                    $result = mysqli_query($conn, $query);

                    if (!$result) {
                        echo "<p class='mt-4 text-red-500'>Something went wrong. Please try again later.</p>";
                        exit;
                    }

                    echo "<p class='mt-4 text-green-500'>Registration successful!</p>";

                    $conn->close();

                    header("Location: login.php");
                    exit;
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>