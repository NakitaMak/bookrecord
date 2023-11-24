<?php
require_once __DIR__ . '/classes/book.php';

$book = new Book();
$genreId = $_GET['genre'];
$page = $_GET['p'];
$genres = $book->getAllGenre();

require_once __DIR__ . '/header.php';
?>
<div style="display: flex; justify-content: space-between;">
<h2 style="margin-left:10%;">記録</h2>
<select name="genre" onchange="displayGenreBook(this.value)" style="height:min-content; float:right; margin:auto 6%">
    <option value="0">全ジャンル</option>
    <?php
    foreach ($genres as $genre) {
        if ($genre['ident'] == $genreId) {
            echo '<option value="' . $genre['ident'] . '" selected>' . $genre['name'] . '</option>';
        } else {
            echo '<option value="' . $genre['ident'] . '">' . $genre['name'] . '</option>';
        }
    }
    ?>
</select>
</div>
<div id="booklist">
<table class="book-table">
<?php 
$genre = $book->getGenre($genreId);
$booklist = $book->getGenreBookPage($genreId, $page);
$bookcount = $book->getGenreBookCount($genreId);
echo '<tr><th colspan="8" style="background-color:' . $genre['color'] . ';">' . $genre['name'] . '</th></tr>';
echo '<tr class="even-row"><th>タイトル</th><th>作者</th><th>文字数(万)</th><th>リンク</th><th>アニメ<br>テーマ曲</th><th>朗読<br>ドラマCD</th><th>リマーク</th><th>詳細</th></tr>';
$i = 0;
foreach ($booklist as $bookinfo) {
    if ($i == 1) {
        echo '<tr class="even-row">';
        $i = 0;
    } else {
        echo '<tr>';
        $i++;
    }
    echo '<td>' . $bookinfo['title'] . '</td>';
    echo '<td>' . $bookinfo['author'] . '</td>';
    echo '<td>' . $bookinfo['word_count'] . '</td>';
    echo '<td>' . $bookinfo['url'] . '&emsp;<a href="' . $bookinfo['url'] . '" target="_blank"><img src="link_icon.png" style="width:15px;height:15px;"></a></td>';
    if ($bookinfo['fandom'] == "1") {
        echo '<td>○</td>';
    } else {
        echo '<td>×</td>';
    }
    if ($bookinfo['dramacd'] == "1") {
        echo '<td>○</td>';
    } else {
        echo '<td>×</td>';
    }
    echo '<td>' . $bookinfo['remark'] . '</td>';
    echo '<td><a href="bookdetail.php?ident=' . $bookinfo['ident'] . '"><button type="button">詳細</button></a></td>';
    echo '</tr>';
}
?>
</table>
<br>
<div class="pagination">
    <?php
    if ($page == 1) {
        echo '<a>&laquo;</a>';
    } else {
        echo '<a href="genrebook.php?genre=' .  $genreId . '&p=' . $page-1 . '">&laquo;</a>';
    }
    for ($i = 1; $i <= ($bookcount['count']/20+1); $i++) {
        echo '<a href="genrebook.php?genre=' .  $genreId . '&p=' . $i . '">' . $i . '</a>';
    }
    if ($page < $i-1) {
        echo '<a href="genrebook.php?genre=' .  $genreId . '&p=' . $page+1 . '">&raquo;</a>';
    } else {
        echo '<a>&raquo;</a>';
    }
    ?>
</div>

</div>

<script>
    function displayGenreBook(ident) {
        if (ident == 0) {
            location.replace("booklist.php?p=1")
        } else {
            location.replace("genrebook.php?genre=" + ident + "&p=1")
        }
    }
</script>
<?php
require_once __DIR__ . '/footer.php';
?>