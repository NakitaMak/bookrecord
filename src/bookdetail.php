<?php
$bookId = $_GET['ident'];
require_once __DIR__ . '/classes/book.php';
$book = new Book();
$bookInfo = $book->getBookInfo($bookId);
$tags = $book->getBookTags($bookId);

require_once __DIR__ . '/classes/history.php';
$history = new History();
$history->updateHistory($bookId);

require_once __DIR__ . '/header.php';
?>
<div style="display: flex; justify-content: space-between;">
<h2 style="margin-left:10%;">詳細</h2>
<a href="edit.php?bookid=<?= $bookInfo['ident'] ?>" style="float:right; margin:auto 10%"><button>編集</button></a>
</div>
<div>
    <table class="detail-table">
        <tr><th>タイトル：</th><td><?= $bookInfo['title'] ?></td></tr>
        <tr><th>作者：</th><td><?= $bookInfo['author'] ?></td></tr>
        <tr><th>ジャンル：</th><td><?= $bookInfo['name'] ?></td></tr>
        <tr><th>タグ：</th><td>&emsp;
        <?php
        if (!empty($tags)) {
            foreach ($tags as $tag) {
                echo '<span class="tag-span">' . $tag['name'] . '</span>&emsp;';
            }
        }
        ?>
        </td></tr>
        <tr><th>文字数：</th><td><?= $bookInfo['word_count'] ?></td></tr>
        <tr><th>リンク：</th><td><?= $bookInfo['url'] ?>&emsp;<a href="<?= $bookInfo['url'] ?>" target="_blank"><img src="link_icon.png" class="link-icon"></a></td></tr>
        <?php
        if ($bookInfo['status'] == "2") {
            echo '<tr><th>狀態：</th><td>完読</td></tr>';
        } elseif ($bookInfo['status'] == "1") {
            echo '<tr><th>狀態：</th><td>読書中</td></tr>';
        } else {
            echo '<tr><th>狀態：</th><td>未読</td></tr>';
        }
        ?>
        <tr><th>主人公：</th><td><?= $bookInfo['cp'] ?></td></tr>
        <?php
        if ($bookInfo['fandom'] == "1") {
            $fandoms = $book->getFandom($bookInfo['ident']);
            echo '<tr><th>アニメ/テーマ曲：</th><td><table>';
            foreach ($fandoms as $fandom) {
                echo '<tr><td>' . $fandom['name'] . '&emsp;<a href="' . $fandom['url'] . '" target="_blank"><img src="link_icon.png" class="link-icon"></a></td><td>／&emsp;' . $fandom['memo'] . '</td></tr>';
            }
            echo '</table></td></tr>';
        } else {
            echo '<tr><th>アニメ/テーマ曲：</th><td>作品がありません。</td></tr>';
        }
        if ($bookInfo['dramacd'] == "1") {
            $dramacds = $book->getDramacd($bookInfo['ident']);
            echo '<tr><th>朗読/ドラマCD：</th><td><table>';
            foreach ($dramacds as $dramacd) {
                echo '<tr><td>' . $dramacd['name'] . '&emsp;<a href="' . $dramacd['url'] . '" target="_blank"><img src="link_icon.png" class="link-icon"></a></td><td>／&emsp;' . $dramacd['code'] . '<button style="border:none;background:transparent;" value="' . $dramacd['code'] . '" onclick="copyCode(this.value)"><img src="copy_icon.png" class="link-icon"></button></td></tr>';
            }
            echo '</table></td></tr>';
        } else {
            echo '<tr><th>朗読/ドラマCD：</th><td>作品がありません。</td></tr>';
        }
        ?>
        <tr><th>代表的なもの：</th><td><?= $bookInfo['mascot'] ?></td></tr>
        <tr><th>リマーク：</th><td><?= $bookInfo['remark'] ?></td></tr>
        <tr><th>感想：</th><td><?= $bookInfo['content'] ?></td></tr>
    </table>
</div>

<script>

function copyCode(e) {
    var code = e
    navigator.clipboard.writeText(code);
}

</script>
