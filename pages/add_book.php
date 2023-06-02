<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "books";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $title = $_POST['title'];
    $author_id = $_POST['author_id'];
    $genre_id = $_POST['genre_id'];
    $publication_date = $_POST['publication_date'];
    $description = $_POST['description'];
    $rating = $_POST['rating'];
    $status = $_POST['status'];

    $id_user = $_SESSION['username'];
    $query = "SELECT id FROM users WHERE username = '$id_user'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $id_user = $row['id'];

    $cover = $_POST['cover'];
    if (empty($cover)) {
        $cover = "https://images.unsplash.com/photo-1519681393784-d120267933ba?ixid=MnwxMjA3fDB8MHxzZWFyY2h8Mnx8Ym9va3xlbnwwfHwwfHw%3D&ixlib=rb-1.2.1&w=1000&q=80";
    }

    if (empty($publication_date)) {
        $publication_date = "0000-00-00";
    }


    if (empty($description)) {
        $description = "No description available.";
    }


    if (empty($rating)) {
        $rating = 0.0;
    }

    if (empty($title)) {
        echo "<p class='mt-4 text-red-500'>Title is required.</p>";
        exit;
    }

    if (empty($author_id)) {
        echo "<p class='mt-4 text-red-500'>Author is required.</p>";
        exit;
    }

    if (empty($genre_id)) {
        echo "<p class='mt-4 text-red-500'>Genre is required.</p>";
        exit;
    }

    print($id_user);
    $query = "INSERT INTO books (title, author_id, genre_id, publication_date, description, rating, user_id) VALUES ('$title', '$author_id', '$genre_id', '$publication_date', '$description', '$rating', '$id_user')";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo "<p class='mt-4 text-red-500'>Something went wrong. Please try again later.</p>";
        exit;
    }

    echo "<p class='mt-4 text-green-500'>Book added successfully!</p>";

    header("Location: books.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .search-dropdown {
            display: none;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="flex items-center justify-center h-screen">
        <div class="w-96 p-8 bg-white rounded shadow">
            <h1 class="text-2xl font-bold mb-2 text-center">Add Book</h1>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <div class="mb-4">
                    <label for="title" class="block mb-1">Title:</label>
                    <input type="text" id="title" name="title" required
                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label for="author_id" class="block mb-1">Author:</label>
                    <div class="relative">
                        <input type="text" id="author_search" placeholder="Search authors"
                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:border-blue-500">
                        <select id="author_id" name="author_id" required
                            class="mt-2 w-full px-3 py-2 border rounded-md focus:outline-none focus:border-blue-500">
                            <?php
                            $servername = "localhost";
                            $username = "root";
                            $password = "";
                            $dbname = "books";

                            $conn = new mysqli($servername, $username, $password, $dbname);

                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }

                            $sql = "SELECT id, name, surname FROM authors";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<option value="' . $row['id'] . '">' . $row['name'] . " " . $row['surname'] . '</option>';
                                }
                            }

                            $conn->close();
                            ?> </select>
                        <a href="./add_author.php" class="text-blue-400 text-sm p-1">add author!</a>

                    </div>
                    <div class="mb-4 mt-3">
                        <label for="genre_id" class="block mb-1">Genre:</label>
                        <div class="relative">
                            <input type="text" id="genre_search" placeholder="Search genres"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:border-blue-500">
                            <select id="genre_id" name="genre_id" required
                                class="mt-2 w-full px-3 py-2 border rounded-md focus:outline-none focus:border-blue-500">
                                <?php
                                $servername = "localhost";
                                $username = "root";
                                $password = "";
                                $dbname = "books";

                                $conn = new mysqli($servername, $username, $password, $dbname);

                                if ($conn->connect_error) {
                                    die("Connection failed: " . $conn->connect_error);
                                }

                                $sql = "SELECT id, name FROM genres";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                                    }
                                }

                                $conn->close();
                                ?>
                            </select>
                            <a href="./add_genre.php" class="text-blue-400 text-sm p-1">add genre!</a>
                        </div>
                        <div class="mb-4 mt-3">
                            <label for="publication_date" class="block mb-1">Publication Date:</label>
                            <input type="date" id="publication_date" name="publication_date"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:border-blue-500">
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block mb-1">Description:</label>
                            <textarea id="description" name="description" rows="1"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:border-blue-500"></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="rating" class="block mb-1">Rating:</label>
                            <input type="number" id="rating" name="rating" step="0.5" min="0" max="10"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:border-blue-500">
                        </div>
                        <div class="mb-4">
                            <label for="cover" class="block mb-1">Cover Image (image link):</label>
                            <input type="text" id="cover" name="cover"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:border-blue-500">

                        </div>
                        <div class="mb-4">
                            <label for="status" class="block font-bold mb-2">Status:</label>
                            <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded">
                                <option value="0" <?php if ($status === "unread")
                                    echo "selected"; ?>>Unread</option>
                                <option value="1" <?php if ($status === "reading")
                                    echo "selected"; ?>>Reading
                                </option>
                                <option value="2" <?php if ($status === "read")
                                    echo "selected"; ?>>Read</option>
                            </select>
                        </div>
                        <div class="text-center">
                            <button type="submit"
                                class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Add Book
                            </button>
                        </div>
            </form>
        </div>
    </div>

    <script>
        function filterAuthors() {
            const input = document.getElementById('author_search');
            const filter = input.value.toUpperCase();
            const select = document.getElementById('author_id');
            const options = select.getElementsByTagName('option');

            for (let i = 0; i < options.length; i++) {
                const text = options[i].text.toUpperCase();
                const display = text.indexOf(filter) > -1 ? 'block' : 'none';
                options[i].style.display = display;
            }
        }

        function filterGenres() {
            const input = document.getElementById('genre_search');
            const filter = input.value.toUpperCase();
            const select = document.getElementById('genre_id');
            const options = select.getElementsByTagName('option');

            for (let i = 0; i < options.length; i++) {
                const text = options[i].text.toUpperCase();
                const display = text.indexOf(filter) > -1 ? 'block' : 'none';
                options[i].style.display = display;
            }
        }

        document.getElementById('author_search').addEventListener('input', filterAuthors);
        document.getElementById('genre_search').addEventListener('input', filterGenres);
    </script>
</body>

</html>