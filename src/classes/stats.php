<?php
require_once __DIR__ . '/dbdata.php';

class Stats extends Dbdata
{
    // 完読した本の合計文字数
    public function wordCount()
    {
        $sql = "SELECT SUM(word_count) AS sum FROM book WHERE status = '2'";
        $stmt = $this->query($sql, []);
        $result = $stmt->fetch();
        return $result;
    }

    // 本の合計数
    public function bookCount()
    {
        $sql = "SELECT COUNT(*) AS count FROM book";
        $stmt = $this->query($sql, []);
        $result = $stmt->fetch();
        return $result;
    }

    // ステータス別の本の数
    public function bookStatusCount()
    {
        $sql = "SELECT status, COUNT(*) AS count FROM book GROUP BY status";
        $stmt = $this->query($sql, []);
        $result = $stmt->fetchAll();
        return $result;
    }

    // ジャンル別の本の数
    public function bookGenreCount()
    {
        $sql = "SELECT genre.name, COUNT(*) as count FROM book JOIN genre ON book.genre_id = genre.ident GROUP BY book.genre_id;";
        $stmt = $this->query($sql, []);
        $result = $stmt->fetchAll();
        return $result;
    }
}