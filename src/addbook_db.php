<?php
require_once __DIR__ . '/classes/book.php';
$book = new Book();

$title = $_POST['title']; // タイトル
$author = $_POST['author']; // 作者
$genre = $_POST['genre']; // ジャンル

// 新しいジャンルを入れる
if ($genre == "new") {
    $genre_name = $_POST['new_genre'];
    $genre_color = $_POST['genre_color'];
    $book->insertGenre($genre_name, $genre_color);
    $genre = $book->getGenreId($genre_name);
}

// タグ
if (isset($_POST['tag'])) {
    $tag = $_POST['tag'];
} else {
    $tag = [];
}
// 新しいタグを入れる
if (isset($_POST['new_tag'])) {
    $new_tag = $_POST['new_tag'];
    foreach ($new_tag as $tag_name) {
        if (!empty($tag_name)) {
            $tag_id = $book->insertTag($tag_name);
            array_push($tag, $tag_id);
        }
    }
}

$word = $_POST['word']; // 文字数
$url = $_POST['url']; // リンク
$status = $_POST['status']; // ステータス
$cp = $_POST['cp']; // 主人公名

// 映像作品
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

// 音声作品
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

$mascot = $_POST['mascot']; // 代表的なもの
$remark = $_POST['remark']; // リマーク
$report = $_POST['report']; // 感想

// SQLを実行
$book->insertBook($title, $author, $genre, $word, $url, $status, $cp, $fandom, $dramacd, $mascot, $remark);
$bookId = $book->getBookId($title);
$book->insertReport($bookId, $report);

if (!empty($tag)) {
    $book->insertTagJunction($bookId, $tag);
}

if ($fandom == "1") {
    $book->insertFandom($bookId, $fandom_platform, $fandom_memo, $fandom_url);
}

if ($dramacd == "1") {
    $book->insertDramacd($bookId, $dramacd_platform, $dramacd_url, $dramacd_code);
}

// 詳細ページに移動
header('Location:' . 'bookdetail.php?ident=' . $bookId);

?>