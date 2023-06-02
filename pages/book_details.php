<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "books";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $bookId = $_GET['id'];

    $sql = "SELECT books.title, books.description, books.cover_image, books.publication_date, books.rating, authors.name, authors.surname, genres.name as genre_name FROM books, authors, genres WHERE books.author_id = authors.id AND books.genre_id = genres.id AND books.id = $bookId";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $title = $row["title"];
        $author = $row["name"] . " " . $row["surname"];
        $description = $row["description"];
        $coverImage = $row["cover_image"];
        $publicationDate = $row["publication_date"];
        $rating = $row["rating"];
        $genre = $row["genre_name"];
    } else {
        echo "Book not found.";
        exit;
    }
} else {
    echo "Invalid book ID.";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $title; ?> - Book Details
    </title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">
            <?php echo $title; ?>
        </h1>

        <div class="flex">
            <div class="mr-4">
                <img src="<?php echo $coverImage; ?>" alt="<?php echo $title; ?>" class="w-48 h-auto">
            </div>
            <div>
                <p class="font-bold text-xl">Author:

                </p>
                <p>
                    <?php echo $author; ?>
                </p>
                <p class="font-bold text-xl">Publication Date:
                </p>
                <p>
                    <?php echo $publicationDate; ?>
                </p>
                <p class="font-bold text-xl">Rating:
                </p>
                <p>
                    <?php echo $rating; ?>
                </p>
                <p class="font-bold text-xl">Genre:
                </p>
                <p>
                    <?php echo $genre; ?>
                </p>
                <p class="font-bold text-xl">Description:</p>
                <p>
                    <?php echo $description; ?>
                </p>
            </div>
        </div>

        <div class="mt-4">
            <a href="books.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Back to
                Books</a>
        </div>
    </div>
</body>

</html>