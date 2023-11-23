<?php
require_once __DIR__ . '/classes/book.php';
$book = new Book();

$bookId = $_POST['bookid'];
$title = $_POST['title'];
$author = $_POST['author'];
$genre = $_POST['genre'];

if ($genre == "new") {
    $genre_name = $_POST['new_genre'];
    $genre_color = $_POST['genre_color'];
    $book->insertGenre($genre_name, $genre_color);
    $genre = $book->getGenreId($genre_name);
}

if (isset($_POST['tag'])) {
    $tag = $_POST['tag'];
} else {
    $tag = [];
}
if (isset($_POST['new_tag'])) {
    $new_tag = $_POST['new_tag'];
    foreach ($new_tag as $tag_name) {
        if (!empty($tag_name)) {
            $tag_id = $book->insertTag($tag_name);
            array_push($tag, $tag_id);
        }
    }
}

$word = $_POST['word'];
$url = $_POST['url'];
$status = $_POST['status'];
$cp = $_POST['cp'];

$fandom = $_POST['fandom'];
if ($fandom == "1") {
    $fandom_platform = $_POST['fandom_platform'];
    for ($i = 0, $j = 0; $i < sizeof($fandom_platform); $i++) {
        if ($fandom_platform[$i] == "new_platform") {
            $platform_name = $_POST['new_fandom_platform'];
            $book->insertPlatform($platform_name[$j]);
            $platform = $book->getPlatformId($platform_name[$j]);
            $fandom_platform[$i] = $platform['ident'];
            $j++;
        }
    }
    $fandom_url = $_POST['fandom_url'];
    $fandom_memo = $_POST['fandom_memo'];
}

$dramacd = $_POST['dramacd'];
if ($dramacd == "1") {
    $dramacd_platform = $_POST['dramacd_platform'];
    for ($i = 0, $j = 0; $i < sizeof($dramacd_platform); $i++) {
        if ($dramacd_platform[$i] == "new_platform") {
            $platform_name = $_POST['new_fandom_platform'];
            $book->insertPlatform($platform_name[$j]);
            $platform = $book->getPlatformId($platform_name[$j]);
            $dramacd_platform[$i] = $platform['ident'];
            $j++;
        }
    }
    $dramacd_url = $_POST['dramacd_url'];
    $dramacd_code = $_POST['dramacd_code'];
}

$mascot = $_POST['mascot'];
$remark = $_POST['remark'];
$report = $_POST['report'];

$book->updateBook($bookId, $title, $author, $genre, $word, $url, $status, $cp, $fandom, $dramacd, $mascot, $remark);
if (!empty($tag)) {
    $book->updateTagJunction($bookId, $tag);
} else {
    $book->deleteTagJunction($bookId);
}

$book->updateReport($bookId, $report);

if ($fandom == "1") {
    $book->updateFandom($bookId, $fandom_platform, $fandom_memo, $fandom_url);
} else {
    $book->deleteFandom($bookId);
}

if ($dramacd == "1") {
    $book->updateDramacd($bookId, $dramacd_platform, $dramacd_url, $dramacd_code);
} else {
    $book->deleteDramacd($bookId);
}

header('Location:' . 'bookdetail.php?ident=' . $bookId);

?>