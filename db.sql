DROP DATABASE IF EXISTS books;
CREATE DATABASE books;

USE books;

-- Table for authors
CREATE TABLE authors
(
    id            INT PRIMARY KEY AUTO_INCREMENT,
    name          VARCHAR(255) NOT NULL,
    surname       VARCHAR(255) NOT NULL,
    nationality   VARCHAR(255),
    date_of_birth DATE
);

-- Table for genres
CREATE TABLE genres
(
    id   INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL
);

-- Table for users
CREATE TABLE users
(
    id       INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email    VARCHAR(255),
    UNIQUE (username)
);

-- Table for books
CREATE TABLE books
(
    id               INT PRIMARY KEY AUTO_INCREMENT,
    title            VARCHAR(255) NOT NULL,
    author_id        INT,
    genre_id         INT,
    publication_date DATE,
    description      TEXT,
    rating           DECIMAL(3, 1),
    cover_image      VARCHAR(255),
    user_id          INT,
    status           VARCHAR(20),
    FOREIGN KEY (author_id) REFERENCES authors (id),
    FOREIGN KEY (genre_id) REFERENCES genres (id),
    FOREIGN KEY (user_id) REFERENCES users (id)
);

-- insert data into authors table
INSERT INTO authors (name, surname, nationality, date_of_birth)
VALUES ('J. R. R.', 'Tolkien', 'British', '1892-01-03'),
       ('J. K.', 'Rowling', 'British', '1965-07-31'),
       ('George R. R.', 'Martin', 'American', '1948-09-20'),
       ('Stephen', 'King', 'American', '1947-09-21'),
       ('Andrzej', 'Sapkowski', 'Polish', '1948-06-21'),
       ('J. D.', 'Salinger', 'American', '1919-01-01'),
       ('Ernest', 'Hemingway', 'American', '1899-07-21'),
       ('F. Scott', 'Fitzgerald', 'American', '1896-09-24'),
       ('William', 'Shakespeare', 'British', '1564-04-26'),
       ('Jane', 'Austen', 'British', '1775-12-16'),
       ('Charlotte', 'Brontë', 'British', '1816-04-21'),
       ('Emily', 'Brontë', 'British', '1818-07-30'),
       ('Anne', 'Brontë', 'British', '1820-01-17'),
       ('Leo', 'Tolstoy', 'Russian', '1828-09-09'),
       ('Fyodor', 'Dostoevsky', 'Russian', '1821-11-11'),
       ('Mikhail', 'Bulgakov', 'Russian', '1891-05-15'),
       ('Antoine de', 'Saint-Exupéry', 'French', '1900-06-29'),
       ('Gabriel García', 'Márquez', 'Colombian', '1927-03-06'),
       ('Harper', 'Lee', 'American', '1926-04-28'),
       ('Mark', 'Twain', 'American', '1835-11-30'),
       ('Johann Wolfgang von', 'Goethe', 'German', '1749-08-28'),
       ('Franz', 'Kafka', 'Czech', '1883-07-03'),
       ('Herman', 'Melville', 'American', '1819-08-01'),
       ('Charles', 'Dickens', 'British', '1812-02-07'),
       ('Edgar Allan', 'Poe', 'American', '1809-01-19'),
       ('Arthur Conan', 'Doyle', 'British', '1859-05-22');

-- insert data into genres table
INSERT INTO genres (name)
VALUES ('Fantasy'),
       ('Horror'),
       ('Science fiction'),
       ('Satire'),
       ('Drama'),
       ('Romance'),
       ('Tragedy'),
       ('Tragicomedy'),
       ('Comedy'),
       ('Non-fiction'),
       ('Fiction'),
       ('Poetry'),
       ('Crime'),
       ('Mystery'),
       ('Thriller'),
       ('Historical fiction'),
       ('Biography'),
       ('Autobiography'),
       ('Essay'),
       ('Memoir'),
       ('Self-help'),
       ('Health'),
       ('Guide'),
       ('Travel'),
       ('Children''s'),
       ('Religion, Spirituality & New Age'),
       ('Science'),
       ('History'),
       ('Math'),
       ('Anthology'),
       ('Encyclopedias'),
       ('Dictionaries'),
       ('Comics'),
       ('Art'),
       ('Cookbooks'),
       ('Diaries'),
       ('Journals'),
       ('Prayer books'),
       ('Series'),
       ('Trilogy'),
       ('Young adult fiction'),
       ('Realistic fiction'),
       ('Short story'),
       ('Suspense/Thriller'),
       ('Action and Adventure'),
       ('Classics'),
       ('Comic Book or Graphic Novel'),
       ('Detective and Mystery');

-- insert data into users table
INSERT INTO users (username, password)
VALUES ('user1', 'password1'),
       ('user2', 'password2'),
       ('user3', 'password3');


-- insert data into books table
INSERT INTO books (title, author_id, genre_id, publication_date, description, rating, cover_image, user_id, status)
VALUES ('The Lord of the Rings: the Fellowship of the Ring', 1, 1, '1954-07-29',
        'The first volume in J.R.R. Tolkien''s epic adventure THE LORD OF THE RINGS', 8.8,
        'https://images.gr-assets.com/books/1298411339l/34.jpg', 4, 'to read'),
       ('The Lord of the Rings: the Two Towers', 1, 1, '1954-11-11',
        'The second volume in J.R.R. Tolkien''s epic adventure THE LORD OF THE RINGS', 8.7,
        'https://m.media-amazon.com/images/I/A1y0jd28riL._AC_UF1000,1000_QL80_.jpg', 4, 'to read'),
       ('The Lord of the Rings: the Return of the King', 1, 1, '1955-10-20',
        'The third volume in J.R.R. Tolkien''s epic adventure THE LORD OF THE RINGS', 8.9,
        'https://kbimages1-a.akamaihd.net/58255b54-ad3e-4e7b-9522-fb57ee51b942/1200/1200/False/the-return-of-the-king-the-lord-of-the-rings-book-3-1.jpg',
        4, 'to read'),
       ('The Hobbit', 1, 1, '1937-09-21',
        'Bilbo Baggins is a hobbit who enjoys a comfortable, unambitious life, rarely traveling any farther than his pantry or cellar. But his contentment is disturbed when the wizard Gandalf and a company of dwarves arrive on his doorstep one day to whisk him away on an adventure. They have launched a plot to raid the treasure hoard guarded by Smaug the Magnificent, a large and very dangerous dragon. Bilbo reluctantly joins their quest, unaware that on his journey to the Lonely Mountain he will encounter both a magic ring and a frightening creature known as Gollum.',
        8.2, 'https://images.gr-assets.com/books/1372847500l/5907.jpg', 1, 'done'),
       ('Harry Potter and the Philosopher''s Stone', 2, 1, '1997-06-26',
        'Harry Potter''s life is miserable. His parents are dead and he''s stuck with his heartless relatives, who force him to live in a tiny closet under the stairs. But his fortune changes when he receives a letter that tells him the truth about himself: he''s a wizard. A mysterious visitor rescues him from his relatives and takes him to his new home, Hogwarts School of Witchcraft and Wizardry.',
        8.7, 'https://images.gr-assets.com/books/1474154022l/3.jpg', 2, 'reading'),
       ('Metamorphosis', 22, 1, '1915-01-01',
        'The story begins with a traveling salesman, Gregor Samsa, waking to find himself transformed into a "monstrous vermin".',
        8.2, 'https://miro.medium.com/v2/resize:fit:500/1*acGO1ByIz_G7kG9IspqP9Q.jpeg', 4, 'done');




