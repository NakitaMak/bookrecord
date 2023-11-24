<?php
require_once __DIR__ . '/dbdata.php';

class Book extends Dbdata
{
    // ジャンル別の本を取得
    public function getGenreBook($genreId)
    {
        $sql = "SELECT * FROM book WHERE genre_id = ?";
        $stmt = $this->query($sql, [$genreId]);
        $result = $stmt->fetchAll();
        return $result;
    }

    // ジャンル別ページ別の本を取得
    public function getGenreBookPage($genreId, $page)
    {
        $offset = ($page-1) * 15;
        $sql = "SELECT * FROM book WHERE genre_id = ? LIMIT 15 OFFSET $offset;";
        $stmt = $this->query($sql, [$genreId]);
        $result = $stmt->fetchAll();
        return $result;
    }

    // ジャンル別の本の数
    public function getGenreBookCount($genreId)
    {
        $sql = "SELECT COUNT(*) AS count FROM book WHERE genre_id = ?";
        $stmt = $this->query($sql, [$genreId]);
        $result = $stmt->fetch();
        return $result;
    }

    // 全てのジャンルを取得
    public function getAllGenre()
    {
        $sql = "SELECT * FROM genre";
        $stmt = $this->query($sql, []);
        $result = $stmt->fetchAll();
        return $result;
    }

    // 全ての本を取得
    public function getAllBook($page)
    {
        $offset = ($page-1) * 15;
        $sql = "SELECT * FROM book ORDER BY genre_id LIMIT 15 OFFSET $offset";
        $stmt = $this->query($sql, []);
        $result = $stmt->fetchAll();
        return $result;
    }

    // 全ての本の数
    public function getBookCount()
    {
        $sql = "SELECT COUNT(*) AS count FROM book";
        $stmt = $this->query($sql, []);
        $result = $stmt->fetch();
        return $result;
    }

    // 本の詳細
    public function getBookInfo($bookId)
    {
        $sql = "SELECT * FROM book = a JOIN genre = b ON a.genre_id = b.ident JOIN review = c ON a.ident = c.book_id WHERE a.ident = ?";
        $stmt = $this->query($sql, [$bookId]);
        $result = $stmt->fetch();
        return $result;
    }

    // ジャンルを追加
    public function insertGenre($name, $color)
    {
        $sql = "INSERT INTO genre VALUES (null, ?, ?)";
        $this->exec($sql, [$name, $color]);
    }

    // ジャンルIDを取得
    public function getGenreId($name)
    {
        $sql = "SELECT * FROM genre WHERE name = ?";
        $stmt = $this->query($sql, [$name]);
        $result = $stmt->fetch();
        return $result['ident'];
    }

    // ジャンルを取得
    public function getGenre($genreId)
    {
        $sql = "SELECT * FROM genre WHERE ident = ?";
        $stmt = $this->query($sql, [$genreId]);
        $result = $stmt->fetch();
        return $result;
    }

    // 全てのプラットフォームを取得
    public function getAllPlatform()
    {
        $sql = "SELECT * FROM platform";
        $stmt = $this->query($sql, []);
        $result = $stmt->fetchAll();
        return $result;
    }

    // プラットフォームIDを取得
    public function getPlatformId($name)
    {
        $sql = "SELECT * FROM platform WHERE name = ?";
        $stmt = $this->query($sql, [$name]);
        $result = $stmt->fetch();
        return $result;
    }

    // プラットフォームを追加
    public function insertPlatform($name)
    {
        $platform = $this->getPlatformId($name);
        if (empty($platform)) {
            $sql = "INSERT INTO platform VALUES (null, ?)";
            $this->exec($sql, [$name]);
            $result = $this->getPlatformId($name);
        }
        return $result;        
    }

    // 全てのタグを取得
    public function getAllTag()
    {
        $sql = "SELECT * FROM tag";
        $stmt = $this->query($sql, []);
        $result = $stmt->fetchAll();
        return $result;
    }

    // タグを追加
    public function insertTag($tag)
    {
        $sql = "INSERT INTO tag VALUES (null, ?)";
        $stmt = $this->exec($sql, [$tag]);
        $result = $this->getTagId($tag);
        return $result;
    }

    // タグIDを取得
    public function getTagId($name)
    {
        $sql = "SELECT * FROM tag WHERE name = ?";
        $stmt = $this->query($sql, [$name]);
        $result = $stmt->fetch();
        return $result['ident'];
    }

    // 本のIDを取得
    public function getBookId($title)
    {
        $sql = "SELECT * FROM book WHERE title = ?";
        $stmt = $this->query($sql, [$title]);
        $result = $stmt->fetch();
        return $result['ident'];
    }

    // 本を追加
    public function insertBook($title, $author, $genre, $word, $url, $status, $cp, $fandom, $dramacd, $mascot, $remark)
    {
        $sql = "INSERT INTO book VALUES (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $this->exec($sql, [$title, $author, $genre, $word, $url, $status, $cp, $fandom, $dramacd, $mascot, $remark]);
    }

    // 感想を追加
    public function insertReport($bookId, $review)
    {
        $sql = "INSERT INTO review VALUES (null, ?, ?)";
        $this->exec($sql, [$bookId, $review]);
    }

    // 本のタグを追加
    public function insertTagJunction($bookId, $tagIds)
    {
        $sql = "INSERT INTO tag_junction VALUES (null, ?, ?)";
        foreach ($tagIds as $tagId) {
            $this->exec($sql, [$bookId, $tagId]);
        }
    }

    // 映像作品を追加
    public function insertFandom($bookId, $platformId, $memo, $url)
    {
        $sql = "INSERT INTO work VALUES (null, ?, ?, ?, ?)";
        for ($i = 0; $i < sizeof($platformId); $i++) {
            $this->exec($sql, [$bookId, $platformId[$i], $memo[$i], $url[$i]]);
        }
    }

    // 音声作品を追加
    public function insertDramacd($bookId, $platformId, $url, $code)
    {
        $sql = "INSERT INTO audio VALUES (null, ?, ?, ?, ?)";
        for ($i = 0; $i < sizeof($platformId); $i++) {
            $this->exec($sql, [$bookId, $platformId[$i], $url[$i], $code[$i]]);
        }
    }

    // 本のタグを取得
    public function getBookTags($bookId)
    {
        $tags = [];

        $sql = "SELECT * FROM tag_junction WHERE book_id = ?";
        $stmt = $this->query($sql, [$bookId]);
        $results = $stmt->fetchAll();
        $sql = "SELECT * FROM tag WHERE ident = ?";
        foreach ($results as $tag) {
            $stmt = $this->query($sql, [$tag['tag_id']]);
            $result = $stmt->fetch();
            array_push($tags, $result);
        }
        return $tags;
    }

    // 音声作品を取得
    public function getDramacd($bookId)
    {
        $sql = "SELECT * FROM audio JOIN platform ON audio.platform_id = platform.ident WHERE audio.book_id = ?";
        $stmt = $this->query($sql, [$bookId]);
        $result = $stmt->fetchAll();
        return $result;
    }

    // 映像作品を取得
    public function getFandom($bookId)
    {
        $sql = "SELECT * FROM work JOIN platform ON work.platform_id = platform.ident WHERE work.book_id = ?";
        $stmt = $this->query($sql, [$bookId]);
        $result = $stmt->fetchAll();
        return $result;
    }

    // 本の情報を更新
    public function updateBook($bookId, $title, $author, $genre, $word, $url, $status, $cp, $fandom, $dramacd, $mascot, $remark)
    {
        $sql = "UPDATE book SET title = ?, author = ?, genre_id = ?, word_count = ?, url = ?, status = ?, cp = ?, fandom = ?, dramacd = ?, mascot = ?, remark = ? WHERE ident = ?";
        $this->exec($sql, [$title, $author, $genre, $word, $url, $status, $cp, $fandom, $dramacd, $mascot, $remark, $bookId]);
    }

    // 本のタグを更新
    public function updateTagJunction($bookId, $tagIds)
    {
        $this->deleteTagJunction($bookId);
        $this->insertTagJunction($bookId, $tagIds);
    }

    // 感想を更新
    public function updateReport($bookId, $report)
    {
        $sql = "UPDATE review SET content = ? WHERE book_id = ?";
        $this->exec($sql, [$report, $bookId]);
    }

    // 映像作品を更新
    public function updateFandom($bookId, $platformId, $memo, $url)
    {
        $this->deleteFandom($bookId);
        $this->insertFandom($bookId, $platformId, $memo, $url);
    }

    // 音声作品を更新
    public function updateDramacd($bookId, $platformId, $url, $code)
    {
        $this->deleteDramacd($bookId);
        $this->insertDramacd($bookId, $platformId, $url, $code);
    }

    // 本のタグを削除
    public function deleteTagJunction($bookId)
    {
        $sql = "DELETE FROM tag_junction WHERE book_id = ?";
        $this->exec($sql, [$bookId]);
    }

    // 映像作品を削除
    public function deleteFandom($bookId)
    {
        $sql = "DELETE FROM work WHERE book_id = ?";
        $this->exec($sql, [$bookId]);
    }

    // 音声作品を削除
    public function deleteDramacd($bookId)
    {
        $sql = "DELETE FROM audio WHERE book_id = ?";
        $this->exec($sql, [$bookId]);
    }
}
