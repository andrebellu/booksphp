<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        .popup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .popup-inner {
            background-color: #fff;
            padding: 20px;
            max-width: 500px;
            text-align: center;
        }

        #popup-description,
        #popup-author,
        #popup-genre,
        #popup-rating {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center flex-col justify-start">
                <h1 class="text-3xl font-bold">Books Database</h1>
                <?php if (isset($_SESSION['username'])) {
                    echo '<p class="text-gray-500" text-left>Welcome, ' . $_SESSION['username'] . '!</p>';
                } ?>
            </div>
            <div class="relative">
                <button id="userMenuButton"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded">
                    <span class="sr-only">User Menu</span>
                    <i class="material-icons text-xl leading-none">person</i>
                </button>
                <div id="userMenu" class="absolute top-12 right-0 w-40 bg-white rounded-lg shadow-lg hidden">
                    <a href="./register.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Register</a>
                    <a href="./login.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Login</a>
                    <?php
                    if (isset($_SESSION['username'])) {
                        echo '<a href="./logout.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Logout</a>';
                    }
                    ?>
                </div>
            </div>
        </div>

        <?php
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

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

        $user = $_SESSION['username'];
        $user_id = "SELECT id FROM users WHERE username = '$user'";

        $sql_read = "SELECT authors.name, authors.surname, books.title, books.id, books.description, books.cover_image, genres.name as genre, books.publication_date, books.rating as rating FROM books, authors, genres WHERE books.author_id = authors.id AND books.genre_id = genres.id AND books.user_id = ($user_id) AND books.status = 2";

        $result_read = $conn->query($sql_read);

        $sql_not_read = "SELECT authors.name, authors.surname, books.title, books.id, books.description, books.cover_image, genres.name as genre, books.publication_date, books.rating as rating FROM books, authors, genres WHERE books.author_id = authors.id AND books.genre_id = genres.id AND books.user_id = ($user_id) AND books.status = 0";

        $result_not_read = $conn->query($sql_not_read);

        $sql_reading = "SELECT authors.name, authors.surname, books.title, books.id, books.description, books.cover_image, genres.name as genre, books.publication_date, books.rating as rating FROM books, authors, genres WHERE books.author_id = authors.id AND books.genre_id = genres.id AND books.user_id = ($user_id) AND books.status = 1";

        $result_reading = $conn->query($sql_reading);

        if (isset($_SESSION['username'])) {
            echo '<div class="flex justify-center mb-4 flex-row">
                <a href="./bookslist.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Books</a>

                <a href="./authors.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-4">Authors</a>

                <a href="./genres.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-4">Genres</a>
            </div>';

            echo '<h2 class="text-2xl font-bold mb-4">Reading</h2>';

            if ($result_read->num_rows > 0) {
                echo '<div class="flex flex-wrap">';
                while ($row = $result_reading->fetch_assoc()) {
                    $bookId = $row["id"];
                    $title = $row["title"];
                    $description = $row["description"];
                    $coverImage = $row["cover_image"];
                    $author = $row["name"] . " " . $row["surname"];
                    $genre = $row["genre"];
                    $rating = $row["rating"];
                    $date = $row["publication_date"];

                    $bookDetails = urlencode(json_encode([
                        'title' => $title,
                        'description' => $description,
                        'author' => $author,
                        'genre' => $genre,
                        'rating' => $rating,
                        'date' => $date
                    ]));

                    ?>
                    <div class="max-w-sm bg-white rounded-lg overflow-hidden shadow-lg mx-2 my-4">
                        <img class="w-full h-96 object-cover object-center" src="<?php echo $coverImage; ?>"
                            alt="<?php echo $title; ?>">
                        <div class="px-6 py-4">
                            <div class="font-bold text-xl mb-2 text-center">
                                <?php echo $title; ?>
                            </div>
                            <p class="text-gray-600 text-sm mb-2 truncate">
                                <?php echo $description; ?>
                            </p>

                            <p class="text-gray-500 text-xs mb-1">Author:
                                <?php echo $author; ?>
                            </p>
                            <p class="text-gray-500 text-xs">Genre:
                                <?php echo $genre; ?>
                            </p>
                            <div class="side flex flex-row">
                                <button
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4 mx-auto block"
                                    onclick="showPopup('<?php echo $bookDetails; ?>')">View Details</button>

                                <a href="./done.php?id=<?php echo $bookId; ?>"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mt-4 mx-auto block material-icons cursor-pointer">done</a>

                            </div>

                        </div>
                    </div>
                    <?php
                }
                echo '</div>';
            } else {
                echo '<p class="text-center">No books found in "Read" category.</p>';
            }

            echo '<h2 class="text-2xl font-bold mb-4 mt-8">Not Read</h2>';

            if ($result_not_read->num_rows > 0) {
                echo '<div class="flex flex-wrap">';
                while ($row = $result_not_read->fetch_assoc()) {
                    $bookId = $row["id"];
                    $title = $row["title"];
                    $description = $row["description"];
                    $coverImage = $row["cover_image"];
                    $author = $row["name"] . " " . $row["surname"];
                    $genre = $row["genre"];
                    $rating = $row["rating"];
                    $date = $row["publication_date"];

                    $bookDetails = urlencode(json_encode([
                        'title' => $title,
                        'description' => $description,
                        'author' => $author,
                        'genre' => $genre,
                        'rating' => $rating,
                        'date' => $date
                    ]));

                    ?>
                    <div class="max-w-sm bg-white rounded-lg overflow-hidden shadow-lg mx-2 my-4">
                        <img class="w-full h-96 object-cover object-center" src="<?php echo $coverImage; ?>"
                            alt="<?php echo $title; ?>">
                        <div class="px-6 py-4">
                            <div class="font-bold text-xl mb-2 text-center">
                                <?php echo $title; ?>
                            </div>
                            <p class="text-gray-600 text-sm mb-2 truncate">
                                <?php echo $description; ?>
                            </p>

                            <p class="text-gray-500 text-xs mb-1">Author:
                                <?php echo $author; ?>
                            </p>
                            <p class="text-gray-500 text-xs">Genre:
                                <?php echo $genre; ?>
                            </p>
                            <div class="side flex flex-row">
                                <button
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4 mx-auto block"
                                    onclick="showPopup('<?php echo $bookDetails; ?>')">View Details</button>

                                <a href="./reading.php?id=<?php echo $bookId; ?>"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mt-4 mx-auto block material-icons cursor-pointer">play_lesson</a>
                            </div>

                        </div>
                    </div>
                    <?php
                }
                echo '</div>';
            } else {
                echo '<p class="text-center">No books found in "Not Read" category.</p>';
            }

            echo '<h2 class="text-2xl font-bold mb-4 mt-8">Read</h2>';

            if ($result_reading->num_rows > 0) {
                echo '<div class="flex flex-wrap">';
                while ($row = $result_read->fetch_assoc()) {
                    $bookId = $row["id"];
                    $title = $row["title"];
                    $description = $row["description"];
                    $coverImage = $row["cover_image"];
                    $author = $row["name"] . " " . $row["surname"];
                    $genre = $row["genre"];
                    $rating = $row["rating"];
                    $date = $row["publication_date"];

                    $bookDetails = urlencode(json_encode([
                        'title' => $title,
                        'description' => $description,
                        'author' => $author,
                        'genre' => $genre,
                        'rating' => $rating,
                        'date' => $date
                    ]));

                    ?>
                    <div class="max-w-sm bg-white rounded-lg overflow-hidden shadow-lg mx-2 my-4">
                        <img class="w-full h-96 object-cover object-center" src="<?php echo $coverImage; ?>"
                            alt="<?php echo $title; ?>">
                        <div class="px-6 py-4">
                            <div class="font-bold text-xl mb-2 text-center">
                                <?php echo $title; ?>
                            </div>
                            <p class="text-gray-600 text-sm mb-2 truncate">
                                <?php echo $description; ?>
                            </p>

                            <p class="text-gray-500 text-xs mb-1">Author:
                                <?php echo $author; ?>
                            </p>
                            <p class="text-gray-500 text-xs">Genre:
                                <?php echo $genre; ?>
                            </p>
                            <div class="side flex flex-row">
                                <button
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4 mx-auto block"
                                    onclick="showPopup('<?php echo $bookDetails; ?>')">View Details</button>

                                <a href="./undone.php?id=<?php echo $bookId; ?>"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mt-4 mx-auto block material-icons cursor-pointer">undo</a>
                            </div>

                        </div>
                    </div>
                    <?php
                }
                echo '</div>';
            } else {
                echo '<p class="text-center">No books found in "Reading" category.</p>';
            }
        }
        ?>

        <div id="popup" class="popup hidden">
            <div class="popup-inner">
                <span class="material-icons float-right cursor-pointer" onclick="closePopup()">close</span>
                <div class="book-details">
                    <div id="book-title" class="font-bold text-2xl mb-4"></div>
                    <div id="book-description" class="text-gray-600 text-sm mb-4"></div>
                    <div id="book-author" class="text-gray-500 text-xs mb-1"></div>
                    <div id="book-genre" class="text-gray-500 text-xs mb-1"></div>
                    <div id="book-rating" class="text-gray-500 text-xs mb-1"></div>
                    <div id="book-date" class="text-gray-500 text-xs mb-1"></div>
                </div>
            </div>
        </div>

        <script>
            function showPopup(bookDetails) {
                const details = JSON.parse(decodeURIComponent(bookDetails));
                document.getElementById('book-title').textContent = details.title;
                document.getElementById('book-description').textContent = details.description;
                document.getElementById('book-author').textContent = "Author: " + details.author;
                document.getElementById('book-genre').textContent = "Genre: " + details.genre;
                document.getElementById('book-rating').textContent = "Rating: " + details.rating;
                document.getElementById('book-date').textContent = "Publication Date: " + details.date;

                document.getElementById('popup').classList.remove('hidden');
            }

            function closePopup() {
                document.getElementById('popup').classList.add('hidden');
            }

            document.addEventListener('click', function (event) {
                const userMenuButton = document.getElementById('userMenuButton');
                const userMenu = document.getElementById('userMenu');

                if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                    userMenu.classList.add('hidden');
                }
            });

            document.getElementById('userMenuButton').addEventListener('click', function () {
                const userMenu = document.getElementById('userMenu');
                userMenu.classList.toggle('hidden');
            });


        </script>

</body>

</html>