<?php
require_once __DIR__ . '/dbdata.php';

class History extends Dbdata
{
    // 閲覧履歴を更新
    public function updateHistory($bookId)
    {
        $sql = "SELECT * FROM history WHERE book_id = ?";
        $stmt = $this->query($sql, [$bookId]);
        $result = $stmt->fetch();
        if (empty($result)) {
            $sql = "UPDATE history SET history = history + 1";
            $stmt = $this->exec($sql, []);

            $sql = "INSERT INTO history VALUES (null, 1, ?)";
            $stmt = $this->exec($sql, [$bookId]);

            $sql = "SELECT COUNT(*) AS count FROM history";
            $stmt = $this->query($sql, []);
            $result = $stmt->fetch();
            if ($result['count'] > 20) {
                $sql = "DELETE FROM history WHERE history > 20";
                $stmt = $this->exec($sql, []);
            }
        } else {
            $history = $result['history'];
            $sql = "UPDATE history SET history = 100 WHERE history = ?";
            $stmt = $this->exec($sql, [$history]);

            $sql = "UPDATE history SET history = history + 1 WHERE history < ?";
            $stmt = $this->exec($sql, [$history]);

            $sql = "UPDATE history SET history = 1 WHERE history = 100";
            $stmt = $this->exec($sql, []);
        }
    }

    // 履歴を取得
    public function getHistory()
    {
        $sql = "SELECT history.history, book.ident, book.title, book.author, book.url FROM history JOIN book ON history.book_id = book.ident ORDER BY history.history";
        $stmt = $this->query($sql, []);
        $result = $stmt->fetchAll();
        return $result;
    }

    // 履歴をクリア
    public function clearHistory()
    {
        $sql = "TRUNCATE history";
        $this->exec($sql, []);
    }
}