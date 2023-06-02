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

if (isset($_GET['id'])) {
    $bookId = $_GET['id'];

    $sql = "SELECT * FROM books WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bookId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $title = $row["title"];
        $description = $row["description"];
        $coverImage = $row["cover_image"];
        $publicationDate = $row["publication_date"];
        $rating = $row["rating"];
        $status = $row["status"];

    } else {
        echo "Book not found.";
        exit;
    }
} else {
    echo "Invalid book ID.";
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newTitle = $_POST["title"];
    $newDescription = $_POST["description"];
    $newCoverImage = $_POST["cover_image"];
    $newPublicationDate = $_POST["publication_date"];
    $newRating = $_POST["rating"];
    $newStatus = $_POST["status"];

    $updateSql = "UPDATE books SET title = ?, description = ?, 
                  cover_image = ?, publication_date = ?, 
                  rating = ?, status = ? WHERE id = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("sssssii", $newTitle, $newDescription, $newCoverImage, $newPublicationDate, $newRating, $newStatus, $bookId);

    if ($stmt->execute()) {
        echo "<script>alert('Book details updated successfully.');</script>";
    } else {
        echo "<script>alert('Failed to update book details.');</script>";
    }

    // Redirect to the book details page
    header("Location: book_details.php?id=$bookId");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book -
        <?php echo $title; ?>
    </title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Edit Book -
            <?php echo $title; ?>
        </h1>

        <form method="POST">
            <div class="mb-4">
                <label for="title" class="block font-bold mb-2">Title:</label>
                <input type="text" name="title" id="title" value="<?php echo $title; ?>"
                    class="w-full px-3 py-2 border border-gray-300 rounded">
            </div>

            <div class="mb-4">
                <label for="description" class="block font-bold mb-2">Description:</label>
                <textarea name="description" id="description" class="w-full px-3 py-2 border border-gray-300 rounded"
                    rows="5"><?php echo $description; ?></textarea>
            </div>

            <div class="mb-4">
                <label for="cover_image" class="block font-bold mb-2">Cover Image URL:</label>
                <input type="text" name="cover_image" id="cover_image" value="<?php echo $coverImage; ?>"
                    class="w-full px-3 py-2 border border-gray-300 rounded">
            </div>

            <div class="mb-4">
                <label for="publication_date" class="block font-bold mb-2">Publication Date:</label>
                <input type="text" name="publication_date" id="publication_date" value="<?php echo $publicationDate; ?>"
                    class="w-full px-3 py-2 border border-gray-300 rounded">
            </div>

            <div class="mb-4">
                <label for="rating" class="block font-bold mb-2">Rating:</label>
                <input type="text" name="rating" id="rating" value="<?php echo $rating; ?>"
                    class="w-full px-3 py-2 border border-gray-300 rounded">
            </div>

            <div class="mb-4">
                <label for="status" class="block font-bold mb-2">Status:</label>
                <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded">
                    <option value="0" <?php if ($status == 0)
                        echo "selected"; ?>>Not Read</option>
                    <option value="1" <?php if ($status == 1)
                        echo "selected"; ?>>Reading</option>
                    <option value="2" <?php if ($status == 2)
                        echo "selected"; ?>>Read</option>
                </select>
            </div>

            <div class="mt-4">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update</button>
                <a href="book_details.php?id=<?php echo $bookId; ?>"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cancel</a>
            </div>
        </form>
    </div>
</body>

</html>