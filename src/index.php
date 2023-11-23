<!DOCTYPE html>
<html>
    <head>
        <title>Book Shelf</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/book.css">
    </head>

    <body>
        <div class="animation-container">
            <img src="frame_0.jpg" id="opening">
        </div>
        <div class="content-container">
            <div class="topnav">
                <a href="index.php" class="home">ホーム</a>
                <a href="bookstats.php" class="split">統計</a>
                <a href="addbook.php" class="split">追加</a>
                <a href="booklist.php?p=1" class="split">一覧</a>
            </div>
            <h2>閲覧履歴</h2>
            <?php
            require_once __DIR__ . '/classes/history.php';
            $history = new History();
            $historyList = $history->getHistory();
            $i = 0;

            if (empty($historyList)) {
                echo '閲覧履歴がありません。';
            } else {
                echo '<table class="history-table">';
                echo '<tr>';
                foreach ($historyList as $historyInfo) {
                    if ($i == 5) {
                        echo '</tr><tr>';
                        $i = 0;
                    }
            ?>
            <td><div class="history-book">
                <p>タイトル：<?= $historyInfo['title'] ?></p>
                <p>作者：<?= $historyInfo['author'] ?></p>
                <p><a href="bookdetail.php?ident=<?= $historyInfo['ident'] ?>"><button type="button">詳細</button></a></p>
            </div></td>
            <?php
                    $i++;
                }
            }
            ?>
            </tr>
            </table>
            <br>
            <form action="clearhistory.php">
                <input type="submit" name="clear" value="閲覧履歴をクリア">
            </form>
        </div>

        <script>
            var animation = new Image();
            animation.src = 'opening.gif';
            animation.onload = function () {
                document.getElementById('opening').src = animation.src;
            };
            document.getElementById('opening').addEventListener('load', function () {
                setTimeout(function () {
                    document.querySelector('.animation-container').style.display = 'none';
                    document.querySelector('.content-container').style.display = 'block';
                }, 1800);
            });

        </script>
<?php
require_once __DIR__ . '/footer.php';
?>