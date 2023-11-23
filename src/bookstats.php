<?php
require_once __DIR__ . '/classes/stats.php';
$stats = new Stats();
$word = $stats->wordCount();
$bookCount = $stats->bookCount();
$bookStatusCount = $stats->bookStatusCount();
$bookGenreCount = $stats->bookGenreCount();

require_once __DIR__ . '/header.php';
?>
<h2>統計資料</h2>
<table class="stat-table">
    <tr>
        <th colspan="2">全文字数：</th>
        <th colspan="4"><?= $word['sum'] ?></th>
    </tr>
    <tr>
        <th colspan="2">全本の数：</th>
        <th colspan="4"><?= $bookCount['count'] ?></th>
    </tr>
    <tr>
        <?php
        foreach ($bookStatusCount as $statusCount) {
            if ($statusCount['status'] == "0"){
                $status = "未読";
            } elseif ($statusCount['status'] == "1") {
                $status = "読書中";
            } else {
                $status = "完読";
            }
            echo '<td>' . $status . '：</td><td>' . $statusCount['count'] . '</td>';
        }
        ?>
    </tr>
    <tr>
        <td></td>
        <?php
        $i = 0;
        foreach ($bookGenreCount as $genreCount) {
            echo '<td>' . $genreCount['name'] . '：</td><td>' . $genreCount['count'] . '</td>';
            $i++;
            if ($i == 2) {
                echo '<td></td></tr><tr><td></td>';
                $i = 0;
            }
        }
        ?>
    </tr>
</table>

<?php
require_once __DIR__ . '/footer.php';
?>