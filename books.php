<?php
// books.php - Books Collection with Futuristic Glassmorphism Design
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: index.php');
    exit();
}
require_once 'config/data.php';

$genres_config = [
    'Fantasy' => ['display' => 'Fantasy', 'icon' => '🐉'],
    'Science Fiction' => ['display' => 'Science Fiction', 'icon' => '🚀'],
    'Thriller' => ['display' => 'Thriller', 'icon' => '🔪'],
    'Romance' => ['display' => 'Romance', 'icon' => '❤️'],
    'Mystery' => ['display' => 'Mystery', 'icon' => '🔍'],
    'Biography' => ['display' => 'Biography', 'icon' => '📖'],
    'History' => ['display' => 'History', 'icon' => '🏛️'],
    'Horror' => ['display' => 'Horror', 'icon' => '👻'],
    'Adventure' => ['display' => 'Adventure', 'icon' => '🗺️'],
    'Drama' => ['display' => 'Drama', 'icon' => '🎭'],
    'Comedy' => ['display' => 'Comedy', 'icon' => '😂'],
    'Young Adult' => ['display' => 'Young Adult', 'icon' => '🧸'],
    'Self-Help' => ['display' => 'Self-Help', 'icon' => '💪'],
    'Finance' => ['display' => 'Finance', 'icon' => '📈'],
    'Philosophy' => ['display' => 'Philosophy', 'icon' => '🤔'],
    'Science' => ['display' => 'Science', 'icon' => '🔬'],
    'Cooking' => ['display' => 'Cooking', 'icon' => '🍳'],
    'Travel' => ['display' => 'Travel', 'icon' => '✈️'],
    'Autobiography' => ['display' => 'Autobiography', 'icon' => '📝'],
    'Children' => ['display' => 'Children', 'icon' => '🧸'],
    'Graphic Novel' => ['display' => 'Graphic Novel', 'icon' => '🎨'],
    'Cyberpunk' => ['display' => 'Cyberpunk', 'icon' => '🤖'],
    'Survival' => ['display' => 'Survival', 'icon' => '🏝️'],
    'Motivational' => ['display' => 'Motivational', 'icon' => '⭐'],
    'Classic' => ['display' => 'Classic', 'icon' => '📚']
];

// 50 Books Dataset
$master_books_list = [
    ['title' => 'Harry Potter and the Sorcerer\'s Stone', 'author' => 'J.K. Rowling', 'publisher' => 'Scholastic', 'genre' => 'Fantasy', 'year' => 1997, 'pages' => 309, 'description' => 'A young boy discovers he is a wizard and begins his magical journey at Hogwarts School.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780590353427-L.jpg'],
    ['title' => 'The Hobbit', 'author' => 'J.R.R. Tolkien', 'publisher' => 'Houghton Mifflin', 'genre' => 'Fantasy', 'year' => 1937, 'pages' => 310, 'description' => 'Bilbo Baggins joins a dangerous quest to reclaim a treasure guarded by a dragon.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780547928227-L.jpg'],
    ['title' => 'Dune', 'author' => 'Frank Herbert', 'publisher' => 'Ace Books', 'genre' => 'Science Fiction', 'year' => 1965, 'pages' => 688, 'description' => 'Paul Atreides navigates politics, prophecy, and war on the desert planet Arrakis.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780441172719-L.jpg'],
    ['title' => 'Ender\'s Game', 'author' => 'Orson Scott Card', 'publisher' => 'Tor Books', 'genre' => 'Science Fiction', 'year' => 1985, 'pages' => 324, 'description' => 'A gifted child is trained through simulations to defend humanity from alien threats.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780812550702-L.jpg'],
    ['title' => 'Gone Girl', 'author' => 'Gillian Flynn', 'publisher' => 'Crown Publishing', 'genre' => 'Thriller', 'year' => 2012, 'pages' => 432, 'description' => 'A husband becomes the prime suspect after his wife mysteriously disappears.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780307588371-L.jpg'],
    ['title' => 'The Girl with the Dragon Tattoo', 'author' => 'Stieg Larsson', 'publisher' => 'Norstedts Förlag', 'genre' => 'Thriller', 'year' => 2005, 'pages' => 465, 'description' => 'A journalist and hacker investigate a decades-old disappearance tied to a wealthy family.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780307454546-L.jpg'],
    ['title' => 'Pride and Prejudice', 'author' => 'Jane Austen', 'publisher' => 'T. Egerton', 'genre' => 'Romance', 'year' => 1813, 'pages' => 432, 'description' => 'Elizabeth Bennet navigates love, class, and misunderstandings with Mr. Darcy.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780141439518-L.jpg'],
    ['title' => 'Me Before You', 'author' => 'Jojo Moyes', 'publisher' => 'Pamela Dorman Books', 'genre' => 'Romance', 'year' => 2012, 'pages' => 369, 'description' => 'A young caregiver forms a life-changing relationship with a paralyzed man.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780143124542-L.jpg'],
    ['title' => 'Sherlock Holmes: A Study in Scarlet', 'author' => 'Arthur Conan Doyle', 'publisher' => 'Ward Lock & Co.', 'genre' => 'Mystery', 'year' => 1887, 'pages' => 188, 'description' => 'Sherlock Holmes investigates a puzzling murder with his sharp deductive skills.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780486474915-L.jpg'],
    ['title' => 'The Da Vinci Code', 'author' => 'Dan Brown', 'publisher' => 'Doubleday', 'genre' => 'Mystery', 'year' => 2003, 'pages' => 489, 'description' => 'A symbologist uncovers secret clues hidden in famous artworks.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780307474278-L.jpg'],
    ['title' => 'The Diary of a Young Girl', 'author' => 'Anne Frank', 'publisher' => 'Contact Publishing', 'genre' => 'Biography', 'year' => 1947, 'pages' => 283, 'description' => 'Anne Frank documents her life hiding during World War II.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780553296983-L.jpg'],
    ['title' => 'Steve Jobs', 'author' => 'Walter Isaacson', 'publisher' => 'Simon & Schuster', 'genre' => 'Biography', 'year' => 2011, 'pages' => 656, 'description' => 'A detailed biography of Apple co-founder Steve Jobs and his innovations.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9781451648539-L.jpg'],
    ['title' => 'Sapiens: A Brief History of Humankind', 'author' => 'Yuval Noah Harari', 'publisher' => 'Harper', 'genre' => 'History', 'year' => 2011, 'pages' => 443, 'description' => 'An exploration of human evolution, civilizations, and societies.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780062316097-L.jpg'],
    ['title' => 'Guns, Germs, and Steel', 'author' => 'Jared Diamond', 'publisher' => 'W.W. Norton', 'genre' => 'History', 'year' => 1997, 'pages' => 480, 'description' => 'Examines how geography and environment shaped world civilizations.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780393317558-L.jpg'],
    ['title' => 'The Shining', 'author' => 'Stephen King', 'publisher' => 'Doubleday', 'genre' => 'Horror', 'year' => 1977, 'pages' => 447, 'description' => 'A family becomes trapped in a haunted hotel with terrifying supernatural forces.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780307743657-L.jpg'],
    ['title' => 'Dracula', 'author' => 'Bram Stoker', 'publisher' => 'Archibald Constable', 'genre' => 'Horror', 'year' => 1897, 'pages' => 418, 'description' => 'Count Dracula travels from Transylvania to England, spreading fear and darkness.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780486411095-L.jpg'],
    ['title' => 'The Alchemist', 'author' => 'Paulo Coelho', 'publisher' => 'HarperOne', 'genre' => 'Adventure', 'year' => 1988, 'pages' => 208, 'description' => 'A shepherd boy journeys across deserts searching for treasure and purpose.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780061122415-L.jpg'],
    ['title' => 'Treasure Island', 'author' => 'Robert Louis Stevenson', 'publisher' => 'Cassell & Company', 'genre' => 'Adventure', 'year' => 1883, 'pages' => 240, 'description' => 'A young boy embarks on a dangerous pirate treasure hunt.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780141321004-L.jpg'],
    ['title' => 'To Kill a Mockingbird', 'author' => 'Harper Lee', 'publisher' => 'J.B. Lippincott & Co.', 'genre' => 'Drama', 'year' => 1960, 'pages' => 336, 'description' => 'A young girl witnesses racial injustice in the American South.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780061120084-L.jpg'],
    ['title' => 'Hamlet', 'author' => 'William Shakespeare', 'publisher' => 'Simon & Schuster', 'genre' => 'Drama', 'year' => 1603, 'pages' => 342, 'description' => 'Prince Hamlet seeks revenge for his father\'s murder while struggling with morality.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780743477123-L.jpg'],
    ['title' => 'The Adventures of Tom Sawyer', 'author' => 'Mark Twain', 'publisher' => 'American Publishing Company', 'genre' => 'Comedy', 'year' => 1876, 'pages' => 274, 'description' => 'Tom Sawyer gets into humorous adventures along the Mississippi River.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780486400778-L.jpg'],
    ['title' => 'Good Omens', 'author' => 'Neil Gaiman & Terry Pratchett', 'publisher' => 'Workman Publishing', 'genre' => 'Comedy', 'year' => 1990, 'pages' => 432, 'description' => 'An angel and demon team up to prevent the apocalypse.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780060853983-L.jpg'],
    ['title' => 'The Hunger Games', 'author' => 'Suzanne Collins', 'publisher' => 'Scholastic Press', 'genre' => 'Young Adult', 'year' => 2008, 'pages' => 374, 'description' => 'Katniss Everdeen fights for survival in a deadly televised competition.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780439023481-L.jpg'],
    ['title' => 'The Fault in Our Stars', 'author' => 'John Green', 'publisher' => 'Dutton Books', 'genre' => 'Young Adult', 'year' => 2012, 'pages' => 313, 'description' => 'Two teenagers with cancer fall in love while facing life\'s challenges.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780525478812-L.jpg'],
    ['title' => 'Rich Dad Poor Dad', 'author' => 'Robert T. Kiyosaki', 'publisher' => 'Plata Publishing', 'genre' => 'Self-Help', 'year' => 1997, 'pages' => 336, 'description' => 'Explains financial literacy and wealth-building through contrasting mindsets.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9781612680194-L.jpg'],
    ['title' => 'Atomic Habits', 'author' => 'James Clear', 'publisher' => 'Avery', 'genre' => 'Self-Help', 'year' => 2018, 'pages' => 320, 'description' => 'Provides practical methods for building good habits and breaking bad ones.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780735211292-L.jpg'],
    ['title' => 'The Intelligent Investor', 'author' => 'Benjamin Graham', 'publisher' => 'Harper Business', 'genre' => 'Finance', 'year' => 1949, 'pages' => 640, 'description' => 'A classic guide to value investing and financial discipline.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780060555665-L.jpg'],
    ['title' => 'Think and Grow Rich', 'author' => 'Napoleon Hill', 'publisher' => 'The Ralston Society', 'genre' => 'Finance', 'year' => 1937, 'pages' => 238, 'description' => 'Discusses success principles inspired by wealthy entrepreneurs.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9781585424337-L.jpg'],
    ['title' => 'The Art of War', 'author' => 'Sun Tzu', 'publisher' => 'Shambhala', 'genre' => 'Philosophy', 'year' => 500, 'pages' => 273, 'description' => 'Ancient military strategies applied to leadership and conflict resolution.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9781590302255-L.jpg'],
    ['title' => 'Meditations', 'author' => 'Marcus Aurelius', 'publisher' => 'Modern Library', 'genre' => 'Philosophy', 'year' => 180, 'pages' => 304, 'description' => 'Stoic reflections on discipline, wisdom, and human nature.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780812968255-L.jpg'],
    ['title' => 'Cosmos', 'author' => 'Carl Sagan', 'publisher' => 'Random House', 'genre' => 'Science', 'year' => 1980, 'pages' => 396, 'description' => 'Explores astronomy, space, and humanity\'s place in the universe.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780345539434-L.jpg'],
    ['title' => 'A Brief History of Time', 'author' => 'Stephen Hawking', 'publisher' => 'Bantam Dell Publishing', 'genre' => 'Science', 'year' => 1988, 'pages' => 256, 'description' => 'Introduces concepts of black holes, relativity, and cosmology.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780553380163-L.jpg'],
    ['title' => 'The Joy of Cooking', 'author' => 'Irma S. Rombauer', 'publisher' => 'Scribner', 'genre' => 'Cooking', 'year' => 1931, 'pages' => 1152, 'description' => 'A comprehensive cookbook with recipes and cooking techniques.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780743246262-L.jpg'],
    ['title' => 'Salt, Fat, Acid, Heat', 'author' => 'Samin Nosrat', 'publisher' => 'Simon & Schuster', 'genre' => 'Cooking', 'year' => 2017, 'pages' => 480, 'description' => 'Explains the four essential elements of successful cooking.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9781476753836-L.jpg'],
    ['title' => 'Into the Wild', 'author' => 'Jon Krakauer', 'publisher' => 'Villard', 'genre' => 'Travel', 'year' => 1996, 'pages' => 224, 'description' => 'Chronicles the real-life journey of Christopher McCandless into the Alaskan wilderness.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780385486804-L.jpg'],
    ['title' => 'Eat, Pray, Love', 'author' => 'Elizabeth Gilbert', 'publisher' => 'Viking Press', 'genre' => 'Travel', 'year' => 2006, 'pages' => 352, 'description' => 'A memoir about self-discovery through travel across Italy, India, and Bali.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780143038412-L.jpg'],
    ['title' => 'Long Walk to Freedom', 'author' => 'Nelson Mandela', 'publisher' => 'Little, Brown and Company', 'genre' => 'Autobiography', 'year' => 1994, 'pages' => 656, 'description' => 'Nelson Mandela recounts his struggle against apartheid in South Africa.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780316548182-L.jpg'],
    ['title' => 'I Am Malala', 'author' => 'Malala Yousafzai', 'publisher' => 'Little, Brown and Company', 'genre' => 'Autobiography', 'year' => 2013, 'pages' => 327, 'description' => 'Malala shares her fight for girls\' education and human rights.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780316322409-L.jpg'],
    ['title' => 'The Cat in the Hat', 'author' => 'Dr. Seuss', 'publisher' => 'Random House', 'genre' => 'Children', 'year' => 1957, 'pages' => 61, 'description' => 'A mischievous cat brings chaos and fun to two children on a rainy day.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780394800011-L.jpg'],
    ['title' => 'Charlotte\'s Web', 'author' => 'E.B. White', 'publisher' => 'Harper & Brothers', 'genre' => 'Children', 'year' => 1952, 'pages' => 192, 'description' => 'A pig named Wilbur forms a touching friendship with a spider named Charlotte.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780064400558-L.jpg'],
    ['title' => 'Maus', 'author' => 'Art Spiegelman', 'publisher' => 'Pantheon Books', 'genre' => 'Graphic Novel', 'year' => 1986, 'pages' => 296, 'description' => 'A Holocaust survivor\'s story is told through symbolic animal characters.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780679406419-L.jpg'],
    ['title' => 'Watchmen', 'author' => 'Alan Moore', 'publisher' => 'DC Comics', 'genre' => 'Graphic Novel', 'year' => 1986, 'pages' => 416, 'description' => 'Former superheroes investigate a conspiracy in an alternate America.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780930289232-L.jpg'],
    ['title' => 'Digital Fortress', 'author' => 'Dan Brown', 'publisher' => 'St. Martin\'s Press', 'genre' => 'Cyberpunk', 'year' => 1998, 'pages' => 384, 'description' => 'A cryptographer uncovers dangerous secrets involving government surveillance.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780312944926-L.jpg'],
    ['title' => 'Neuromancer', 'author' => 'William Gibson', 'publisher' => 'Ace Books', 'genre' => 'Cyberpunk', 'year' => 1984, 'pages' => 271, 'description' => 'A hacker is recruited for a high-tech mission in a futuristic world.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780441569595-L.jpg'],
    ['title' => 'The Martian', 'author' => 'Andy Weir', 'publisher' => 'Crown Publishing', 'genre' => 'Survival', 'year' => 2011, 'pages' => 387, 'description' => 'An astronaut stranded on Mars fights to survive alone.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780553418026-L.jpg'],
    ['title' => 'Life of Pi', 'author' => 'Yann Martel', 'publisher' => 'Knopf Canada', 'genre' => 'Survival', 'year' => 2001, 'pages' => 331, 'description' => 'A boy survives a shipwreck while stranded on a lifeboat with a tiger.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780156027328-L.jpg'],
    ['title' => 'The 7 Habits of Highly Effective People', 'author' => 'Stephen R. Covey', 'publisher' => 'Free Press', 'genre' => 'Motivational', 'year' => 1989, 'pages' => 381, 'description' => 'Introduces habits and principles for personal and professional success.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9781982137274-L.jpg'],
    ['title' => 'Man\'s Search for Meaning', 'author' => 'Viktor E. Frankl', 'publisher' => 'Beacon Press', 'genre' => 'Motivational', 'year' => 1946, 'pages' => 184, 'description' => 'A Holocaust survivor explores the importance of purpose in life.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780807014295-L.jpg'],
    ['title' => 'The Old Man and the Sea', 'author' => 'Ernest Hemingway', 'publisher' => 'Charles Scribner\'s Sons', 'genre' => 'Classic', 'year' => 1952, 'pages' => 127, 'description' => 'An aging fisherman battles a giant marlin far out at sea.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780684801223-L.jpg'],
    ['title' => '1984', 'author' => 'George Orwell', 'publisher' => 'Secker & Warburg', 'genre' => 'Classic', 'year' => 1949, 'pages' => 328, 'description' => 'A man struggles against a totalitarian regime that controls truth and freedom.', 'cover' => 'https://covers.openlibrary.org/b/isbn/9780451524935-L.jpg']
];

// Check if session books data exists and has the correct count
if (!isset($_SESSION['books_data']) || empty($_SESSION['books_data']) || count($_SESSION['books_data']) != 50) {
    $_SESSION['books_data'] = [];
    $book_id = 1;
    
    foreach ($master_books_list as $book) {
        $statuses = ['Available', 'Borrowed', 'Reserved'];
        $status = $statuses[array_rand($statuses)];
        $_SESSION['books_data'][] = [
            'id' => 'BK' . str_pad($book_id++, 4, '0', STR_PAD_LEFT),
            'title' => $book['title'],
            'author' => $book['author'],
            'category' => $book['genre'],
            'genre' => $book['genre'],
            'description' => $book['description'],
            'cover_image' => $book['cover'],
            'publisher' => $book['publisher'],
            'publishedDate' => $book['year'] . '-01-01',
            'pageCount' => $book['pages'],
            'language' => 'en',
            'previewLink' => '#',
            'status' => $status,
            'copies' => rand(1, 5)
        ];
    }
}

// Handle Edit Book POST Request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_book'])) {
    $edit_id = $_POST['edit_id'];
    foreach ($_SESSION['books_data'] as $key => $book) {
        if ($book['id'] === $edit_id) {
            $_SESSION['books_data'][$key]['title'] = trim($_POST['title']);
            $_SESSION['books_data'][$key]['author'] = trim($_POST['author']);
            $_SESSION['books_data'][$key]['category'] = trim($_POST['category']);
            $_SESSION['books_data'][$key]['genre'] = trim($_POST['genre']);
            $_SESSION['books_data'][$key]['description'] = trim($_POST['description']);
            $_SESSION['books_data'][$key]['cover_image'] = trim($_POST['cover_image']);
            $_SESSION['books_data'][$key]['publisher'] = trim($_POST['publisher']);
            $_SESSION['books_data'][$key]['pageCount'] = (int)$_POST['pageCount'];
            $_SESSION['books_data'][$key]['status'] = $_POST['status'];
            $_SESSION['books_data'][$key]['copies'] = (int)$_POST['copies'];
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Book updated successfully!'];
            break;
        }
    }
    header('Location: books.php');
    exit();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    foreach ($_SESSION['books_data'] as $key => $book) {
        if ($book['id'] === $id) {
            unset($_SESSION['books_data'][$key]);
            $_SESSION['books_data'] = array_values($_SESSION['books_data']);
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Book deleted successfully!'];
            break;
        }
    }
    header('Location: books.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_book'])) {
    $new_id = 'BK' . str_pad(count($_SESSION['books_data']) + 1, 4, '0', STR_PAD_LEFT);
    $cover_image = $_POST['cover_image'] ?: 'https://picsum.photos/id/' . rand(1, 200) . '/300/400';
    $_SESSION['books_data'][] = [
        'id' => $new_id,
        'title' => $_POST['title'],
        'author' => $_POST['author'],
        'category' => $_POST['category'],
        'genre' => $_POST['genre'],
        'description' => $_POST['description'],
        'cover_image' => $cover_image,
        'publisher' => $_POST['publisher'],
        'publishedDate' => date('Y-m-d'),
        'pageCount' => (int)$_POST['pageCount'],
        'language' => 'en',
        'previewLink' => '#',
        'status' => $_POST['status'],
        'copies' => (int)$_POST['copies']
    ];
    $_SESSION['message'] = ['type' => 'success', 'text' => 'New book added successfully!'];
    header('Location: books.php');
    exit();
}

$books_data = $_SESSION['books_data'];
$message = $_SESSION['message'] ?? null;
unset($_SESSION['message']);

$total_books_count = count($books_data);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books Collection | SmartLib - Futuristic Bookstore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/books.css">
    <link rel="stylesheet" href="css/books-fix.css">
</head>
<body data-bs-theme="light">
<div class="wrapper">
    <nav class="sidebar">
        <div class="sidebar-header">
            <div class="d-flex align-items-center gap-2">
                <div class="logo-icon-small"><i class="bi bi-book-half"></i></div>
                <div><h3>SmartLib</h3><p>Library Management</p></div>
            </div>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
            <li class="nav-item"><a class="nav-link active" href="books.php"><i class="bi bi-journal-bookmark-fill"></i> Books Collection</a></li>
            <li class="nav-item"><a class="nav-link" href="members.php"><i class="bi bi-people-fill"></i> Active Members</a></li>
            <li class="nav-item"><a class="nav-link" href="transactions.php"><i class="bi bi-arrow-left-right"></i> Transactions</a></li>
            <li class="nav-item"><a class="nav-link" href="insights.php"><i class="bi bi-graph-up"></i> Insights</a></li>
        </ul>
        <div class="sidebar-footer">
            <div class="theme-toggle-wrapper"><i class="bi bi-sun-fill"></i><div class="form-check form-switch"><input class="form-check-input" type="checkbox" id="darkModeSwitch"></div><i class="bi bi-moon-fill"></i></div>
            <a href="logout.php" class="btn btn-outline-danger btn-sm w-100 mt-2"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>
    </nav>

    <main class="main-content">
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $message['type']; ?> alert-dismissible fade show m-3" role="alert">
                <i class="bi bi-<?php echo $message['type'] === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill'; ?> me-2"></i>
                <?php echo $message['text']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="futuristic-hero">
            <div class="hero-background">
                <div class="orb orb-1"></div>
                <div class="orb orb-2"></div>
                <div class="orb orb-3"></div>
            </div>
            <div class="hero-content">
                <div class="hero-badge-glass">
                    <span class="badge-dot"></span>
                    <span><?php echo number_format($total_books_count); ?>+ Books Available</span>
                </div>
                <h1 class="hero-title-glass">Immersive<br>Reading Experience</h1>
                <p class="hero-subtitle-glass">Explore our vast digital library with cutting-edge technology</p>
                <div class="hero-stats-glass">
                    <div class="stat-glass"><span class="stat-number"><?php echo number_format($total_books_count); ?></span><span class="stat-label">Total Books</span></div>
                    <div class="stat-glass"><span class="stat-number"><?php echo count($genres_config); ?></span><span class="stat-label">Genres</span></div>
                    <div class="stat-glass"><span class="stat-number">24/7</span><span class="stat-label">Access</span></div>
                </div>
            </div>
        </div>

        <div class="books-container-glass">
            <div class="floating-search">
                <div class="search-glass">
                    <i class="bi bi-search"></i>
                    <input type="text" id="searchInput" placeholder="Search books...">
                    <div class="search-actions">
                        <select id="genreFilter">
                            <option value="">All Genres</option>
                            <?php foreach ($genres_config as $genre_name => $config): ?>
                                <option value="<?php echo htmlspecialchars($genre_name); ?>"><?php echo $config['icon']; ?> <?php echo htmlspecialchars($config['display']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select id="statusFilter">
                            <option value="">All Status</option>
                            <option value="Available">Available</option>
                            <option value="Borrowed">Borrowed</option>
                            <option value="Reserved">Reserved</option>
                        </select>
                        <button id="resetFilters" class="reset-glass"><i class="bi bi-arrow-repeat"></i></button>
                        <button class="add-glass" data-bs-toggle="modal" data-bs-target="#addBookModal"><i class="bi bi-plus-lg"></i></button>
                    </div>
                </div>
            </div>

            <div class="results-bar">
                <div class="results-count"><i class="bi bi-grid-3x3-gap-fill"></i> <span id="resultCount"><?php echo number_format($total_books_count); ?></span> books found</div>
                <div class="sort-bar"><label>Sort by</label><select id="sortBy"><option value="title_asc">Title A-Z</option><option value="title_desc">Title Z-A</option><option value="author_asc">Author A-Z</option><option value="year_desc">Newest First</option></select></div>
            </div>

            <div class="books-grid-futuristic" id="booksGrid"></div>
            
            <div class="load-more" id="loadMoreContainer" style="display: none;"><button class="btn-load" id="loadMoreBtn">Load More <i class="bi bi-arrow-down"></i></button></div>
            <div class="empty-state" id="noResults" style="display: none;"><i class="bi bi-binoculars"></i><h3>No books found</h3><p>Try different search criteria</p><button class="btn-reset-empty" onclick="resetAllFilters()">Reset Filters</button></div>
        </div>

        <footer class="footer-glass"><div class="container"><span>© 2025 SmartLib System — Futuristic Bookstore | <?php echo number_format($total_books_count); ?>+ Books</span></div></footer>
    </main>
</div>

<!-- Book Detail Modal -->
<div class="modal fade" id="bookDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modal-glass">
            <div class="modal-header modal-glass-header">
                <h5 class="modal-title"><i class="bi bi-book-half"></i> Book Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body modal-glass-body" id="bookDetailContent"></div>
            <div class="modal-footer modal-glass-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="#" id="previewLinkBtn" class="btn-glass-primary" target="_blank">Preview <i class="bi bi-box-arrow-up-right"></i></a>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editBookModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title"><i class="bi bi-pencil-square"></i> Edit Book</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="" id="editBookForm">
                <div class="modal-body">
                    <input type="hidden" name="edit_id" id="edit_id">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3"><label>Title</label><input type="text" name="title" id="edit_title" class="form-control" required></div>
                            <div class="mb-3"><label>Author</label><input type="text" name="author" id="edit_author" class="form-control" required></div>
                            <div class="row">
                                <div class="col-md-6"><div class="mb-3"><label>Category</label><input type="text" name="category" id="edit_category" class="form-control" required></div></div>
                                <div class="col-md-6"><div class="mb-3"><label>Genre</label>
                                    <select name="genre" id="edit_genre" class="form-select">
                                        <?php foreach($genres_config as $genre_name => $config): ?>
                                            <option value="<?php echo htmlspecialchars($genre_name); ?>"><?php echo $config['icon']; ?> <?php echo htmlspecialchars($config['display']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div></div>
                            </div>
                            <div class="mb-3"><label>Description</label><textarea name="description" id="edit_description" class="form-control" rows="3"></textarea></div>
                            <div class="row">
                                <div class="col-md-6"><div class="mb-3"><label>Publisher</label><input type="text" name="publisher" id="edit_publisher" class="form-control"></div></div>
                                <div class="col-md-6"><div class="mb-3"><label>Page Count</label><input type="number" name="pageCount" id="edit_pageCount" class="form-control"></div></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6"><div class="mb-3"><label>Status</label>
                                    <select name="status" id="edit_status" class="form-select">
                                        <option value="Available">Available</option>
                                        <option value="Borrowed">Borrowed</option>
                                        <option value="Reserved">Reserved</option>
                                    </select>
                                </div></div>
                                <div class="col-md-6"><div class="mb-3"><label>Copies</label><input type="number" name="copies" id="edit_copies" class="form-control" required></div></div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <label class="fw-bold">Cover Image URL</label>
                            <input type="text" name="cover_image" id="edit_cover_image" class="form-control mb-2" placeholder="https://example.com/book-cover.jpg">
                            <div class="image-preview-container">
                                <img id="edit_cover_preview" src="https://picsum.photos/id/1/150/200" class="cover-preview-large mt-2" style="max-width: 100%; height: auto; border-radius: 8px;">
                                <small class="preview-label">Live Preview</small>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="testImageUrl()">Test URL</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="edit_book" class="btn btn-warning">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addBookModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-plus-circle"></i> Add New Book</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="" id="addBookForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3"><label>Title</label><input type="text" name="title" class="form-control" required></div>
                            <div class="mb-3"><label>Author</label><input type="text" name="author" class="form-control" required></div>
                            <div class="row">
                                <div class="col-md-6"><div class="mb-3"><label>Category</label><input type="text" name="category" class="form-control" required></div></div>
                                <div class="col-md-6"><div class="mb-3"><label>Genre</label>
                                    <select name="genre" class="form-select">
                                        <?php foreach($genres_config as $genre_name => $config): ?>
                                            <option value="<?php echo htmlspecialchars($genre_name); ?>"><?php echo $config['icon']; ?> <?php echo htmlspecialchars($config['display']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div></div>
                            </div>
                            <div class="mb-3"><label>Description</label><textarea name="description" class="form-control" rows="3"></textarea></div>
                            <div class="row">
                                <div class="col-md-6"><div class="mb-3"><label>Publisher</label><input type="text" name="publisher" class="form-control"></div></div>
                                <div class="col-md-6"><div class="mb-3"><label>Page Count</label><input type="number" name="pageCount" class="form-control"></div></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6"><div class="mb-3"><label>Status</label>
                                    <select name="status" class="form-select">
                                        <option value="Available">Available</option>
                                        <option value="Borrowed">Borrowed</option>
                                        <option value="Reserved">Reserved</option>
                                    </select>
                                </div></div>
                                <div class="col-md-6"><div class="mb-3"><label>Copies</label><input type="number" name="copies" class="form-control" value="1" required></div></div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <label>Cover URL</label>
                            <input type="text" name="cover_image" id="add_cover_image" class="form-control mb-2">
                            <img id="add_cover_preview" src="https://picsum.photos/id/1/120/160" class="cover-preview-large mt-2" style="max-width: 100%; height: auto;">
                            <small class="text-muted d-block">Leave empty for auto cover</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_book" class="btn btn-success">Add Book</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const booksData = <?php echo json_encode($books_data); ?>;
    let currentPage = 1;
    const booksPerPage = 24;
    let filteredBooks = [...booksData];
    
    function fixGoogleBooksUrl(url) {
        if (!url) return null;
        if (url.includes('books.google.com')) {
            const match = url.match(/id=([^&]+)/);
            if (match) {
                return `https://books.google.com/books/content?id=${match[1]}&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api`;
            }
        }
        return url;
    }
    
    function viewBook(id) {
        const book = booksData.find(b => b.id === id);
        if(book) {
            const coverUrl = fixGoogleBooksUrl(book.cover_image) || 'https://picsum.photos/id/1/300/400';
            const googleSearchUrl = `https://www.google.com/search?q=${encodeURIComponent(book.title + ' ' + book.author)}&tbm=shop`;
            document.getElementById('previewLinkBtn').href = googleSearchUrl;
            document.getElementById('bookDetailContent').innerHTML = `
                <div class="modal-book-layout">
                    <div class="modal-cover">
                        <img src="${escapeHtml(coverUrl)}" alt="${escapeHtml(book.title)}" onerror="this.src='https://picsum.photos/id/1/300/400'">
                        <div class="cover-ring"></div>
                    </div>
                    <div class="modal-info">
                        <h2>${escapeHtml(book.title)}</h2>
                        <p class="modal-author">by ${escapeHtml(book.author)}</p>
                        <div class="modal-tags">
                            <span><i class="bi bi-tag"></i> ${escapeHtml(book.genre || 'General')}</span>
                            <span><i class="bi bi-calendar"></i> ${escapeHtml(book.publishedDate || 'Unknown')}</span>
                            <span><i class="bi bi-files"></i> ${book.pageCount || 'N/A'} pages</span>
                        </div>
                        <div class="modal-status">
                            <span class="badge bg-${book.status === 'Available' ? 'success' : (book.status === 'Borrowed' ? 'warning' : 'danger')}">${book.status}</span>
                            <span><i class="bi bi-copy"></i> ${book.copies} copies</span>
                        </div>
                        <div class="modal-desc">
                            <strong>Synopsis</strong>
                            <p>${escapeHtml(book.description || 'No description available.')}</p>
                        </div>
                    </div>
                </div>
            `;
            new bootstrap.Modal(document.getElementById('bookDetailModal')).show();
        }
    }
    
    function editBookModal(id) {
        const book = booksData.find(b => b.id === id);
        if(book) {
            document.getElementById('edit_id').value = book.id;
            document.getElementById('edit_title').value = book.title;
            document.getElementById('edit_author').value = book.author;
            document.getElementById('edit_category').value = book.category;
            document.getElementById('edit_genre').value = book.genre || 'Fantasy';
            document.getElementById('edit_description').value = book.description || '';
            document.getElementById('edit_publisher').value = book.publisher || '';
            document.getElementById('edit_pageCount').value = book.pageCount || '';
            document.getElementById('edit_status').value = book.status;
            document.getElementById('edit_copies').value = book.copies;
            
            let coverUrl = book.cover_image || 'https://picsum.photos/id/1/150/200';
            coverUrl = fixGoogleBooksUrl(coverUrl) || coverUrl;
            
            document.getElementById('edit_cover_image').value = book.cover_image || '';
            const previewImg = document.getElementById('edit_cover_preview');
            previewImg.src = coverUrl;
            previewImg.onerror = function() { this.src = 'https://picsum.photos/id/1/150/200'; };
            
            new bootstrap.Modal(document.getElementById('editBookModal')).show();
        }
    }
    
    function testImageUrl() {
        const url = document.getElementById('edit_cover_image').value;
        if (!url) {
            alert('Please enter a URL first');
            return;
        }
        const previewImg = document.getElementById('edit_cover_preview');
        previewImg.src = url;
        previewImg.onerror = function() {
            alert('Image cannot be loaded. The URL might be invalid or blocked.');
            this.src = 'https://picsum.photos/id/1/150/200';
        };
    }
    
    function applyFilters() {
        const searchTerm = document.getElementById('searchInput')?.value.toLowerCase() || '';
        const genre = document.getElementById('genreFilter')?.value || '';
        const status = document.getElementById('statusFilter')?.value || '';
        filteredBooks = booksData.filter(book => {
            if (searchTerm && !book.title.toLowerCase().includes(searchTerm) && !book.author.toLowerCase().includes(searchTerm)) return false;
            if (genre && book.genre !== genre) return false;
            if (status && book.status !== status) return false;
            return true;
        });
        applySorting();
        currentPage = 1;
        renderBooks();
        updateResultCount();
    }
    
    function applySorting() {
        const sortBy = document.getElementById('sortBy')?.value || 'title_asc';
        filteredBooks.sort((a, b) => {
            switch(sortBy) {
                case 'title_asc': return a.title.localeCompare(b.title);
                case 'title_desc': return b.title.localeCompare(a.title);
                case 'author_asc': return a.author.localeCompare(b.author);
                case 'year_desc': return (b.publishedDate || 0) - (a.publishedDate || 0);
                default: return 0;
            }
        });
    }
    
    function renderBooks() {
        const start = (currentPage - 1) * booksPerPage;
        const end = start + booksPerPage;
        const pageBooks = filteredBooks.slice(start, end);
        const container = document.getElementById('booksGrid');
        const loadMoreContainer = document.getElementById('loadMoreContainer');
        const noResults = document.getElementById('noResults');
        
        if (pageBooks.length === 0 && filteredBooks.length === 0) { 
            container.innerHTML = ''; 
            noResults.style.display = 'block'; 
            loadMoreContainer.style.display = 'none'; 
            return; 
        }
        noResults.style.display = 'none';
        
        container.innerHTML = pageBooks.map(book => {
            let coverUrl = fixGoogleBooksUrl(book.cover_image) || 'https://picsum.photos/id/1/300/400';
            return `
            <div class="book-card-futuristic" data-id="${book.id}">
                <div class="card-front">
                    <div class="book-cover-futuristic">
                        <img src="${escapeHtml(coverUrl)}" alt="${escapeHtml(book.title)}" onerror="this.src='https://picsum.photos/id/1/300/400'">
                        <div class="cover-gloss"></div>
                    </div>
                    <div class="book-info-futuristic">
                        <h4>${escapeHtml(book.title)}</h4>
                        <p>${escapeHtml(book.author)}</p>
                        <div class="book-meta-futuristic">
                            <span>${escapeHtml(book.publishedDate || 'N/A')}</span>
                            <span class="status-${book.status === 'Available' ? 'available' : (book.status === 'Borrowed' ? 'borrowed' : 'reserved')}">${book.status}</span>
                        </div>
                    </div>
                </div>
                <div class="card-back">
                    <div class="back-content">
                        <p>${escapeHtml(book.description ? book.description.substring(0, 100) + '...' : 'No description available.')}</p>
                        <div class="back-actions">
                            <button onclick="viewBook('${book.id}')"><i class="bi bi-eye"></i> View</button>
                            <button onclick="editBookModal('${book.id}')"><i class="bi bi-pencil"></i> Edit</button>
                            <a href="?delete=${book.id}" onclick="return confirm('Delete this book?')"><i class="bi bi-trash"></i> Delete</a>
                        </div>
                    </div>
                </div>
            </div>
        `}).join('');
        loadMoreContainer.style.display = end < filteredBooks.length ? 'flex' : 'none';
    }
    
    function updateResultCount() { 
        document.getElementById('resultCount').textContent = filteredBooks.length.toLocaleString(); 
    }
    
    function loadMore() { 
        currentPage++; 
        renderBooks(); 
    }
    
    function resetAllFilters() { 
        document.getElementById('searchInput').value = ''; 
        document.getElementById('genreFilter').value = ''; 
        document.getElementById('statusFilter').value = ''; 
        document.getElementById('sortBy').value = 'title_asc'; 
        applyFilters(); 
    }
    
    function escapeHtml(text) { 
        if(!text) return ''; 
        const div = document.createElement('div'); 
        div.textContent = text; 
        return div.innerHTML; 
    }
    
    // Event Listeners
    document.getElementById('searchInput')?.addEventListener('input', applyFilters);
    document.getElementById('genreFilter')?.addEventListener('change', applyFilters);
    document.getElementById('statusFilter')?.addEventListener('change', applyFilters);
    document.getElementById('sortBy')?.addEventListener('change', () => { applySorting(); renderBooks(); });
    document.getElementById('loadMoreBtn')?.addEventListener('click', loadMore);
    document.getElementById('resetFilters')?.addEventListener('click', resetAllFilters);
    
    // Live preview for add modal
    const addCoverImage = document.getElementById('add_cover_image');
    const addCoverPreview = document.getElementById('add_cover_preview');
    if (addCoverImage && addCoverPreview) {
        addCoverImage.addEventListener('input', function() { 
            const url = this.value.trim();
            if (url) {
                let fixedUrl = url;
                if (url.includes('books.google.com')) {
                    const match = url.match(/id=([^&]+)/);
                    if (match) {
                        fixedUrl = `https://books.google.com/books/content?id=${match[1]}&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api`;
                    }
                }
                addCoverPreview.src = fixedUrl;
                addCoverPreview.onerror = function() {
                    this.src = 'https://picsum.photos/id/1/120/160';
                };
            } else {
                addCoverPreview.src = 'https://picsum.photos/id/1/120/160';
            }
        });
    }
    
    // Live preview for edit modal
    const editCoverImage = document.getElementById('edit_cover_image');
    const editCoverPreview = document.getElementById('edit_cover_preview');
    if (editCoverImage && editCoverPreview) {
        editCoverImage.addEventListener('input', function() { 
            const url = this.value.trim();
            if (url) {
                let fixedUrl = url;
                if (url.includes('books.google.com')) {
                    const match = url.match(/id=([^&]+)/);
                    if (match) {
                        fixedUrl = `https://books.google.com/books/content?id=${match[1]}&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api`;
                    }
                }
                editCoverPreview.src = fixedUrl;
                editCoverPreview.onerror = function() {
                    this.src = 'https://picsum.photos/id/1/150/200';
                };
            } else {
                editCoverPreview.src = 'https://picsum.photos/id/1/150/200';
            }
        });
    }
    
    // Auto-hide alert after 3 seconds
    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if (alert) {
            alert.style.display = 'none';
        }
    }, 3000);
    
    applyFilters();
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script> 
    const toggle = document.getElementById('darkModeSwitch'); 
    const theme = localStorage.getItem('libTheme') || 'light'; 
    document.body.setAttribute('data-bs-theme', theme); 
    if(toggle) { 
        if(theme === 'dark') toggle.checked = true; 
        toggle.addEventListener('change', (e) => { 
            const nt = e.target.checked ? 'dark' : 'light'; 
            document.body.setAttribute('data-bs-theme', nt); 
            localStorage.setItem('libTheme', nt); 
        }); 
    } 
</script>
</body>
</html>