<?php
require_once __DIR__ . '/dbdata.php';

class Book extends Dbdata
{
    public function getGenreBook($genreId)
    {
        $sql = "SELECT * FROM book WHERE genre_id = ?";
        $stmt = $this->query($sql, [$genreId]);
        $result = $stmt->fetchAll();
        return $result;
    }

    public function getGenreBookPage($genreId, $page)
    {
        $offset = ($page-1) * 15;
        $sql = "SELECT * FROM book WHERE genre_id = ? LIMIT 15 OFFSET $offset;";
        $stmt = $this->query($sql, [$genreId]);
        $result = $stmt->fetchAll();
        return $result;
    }

    public function getGenreBookCount($genreId)
    {
        $sql = "SELECT COUNT(*) AS count FROM book WHERE genre_id = ?";
        $stmt = $this->query($sql, [$genreId]);
        $result = $stmt->fetch();
        return $result;
    }

    public function getAllGenre()
    {
        $sql = "SELECT * FROM genre";
        $stmt = $this->query($sql, []);
        $result = $stmt->fetchAll();
        return $result;
    }

    public function getAllBook($page)
    {
        $offset = ($page-1) * 15;
        $sql = "SELECT * FROM book ORDER BY genre_id LIMIT 15 OFFSET $offset";
        $stmt = $this->query($sql, []);
        $result = $stmt->fetchAll();
        return $result;
    }

    public function getBookCount()
    {
        $sql = "SELECT COUNT(*) AS count FROM book";
        $stmt = $this->query($sql, []);
        $result = $stmt->fetch();
        return $result;
    }

    public function getBookInfo($bookId)
    {
        $sql = "SELECT * FROM book = a JOIN genre = b ON a.genre_id = b.ident JOIN review = c ON a.ident = c.book_id WHERE a.ident = ?";
        $stmt = $this->query($sql, [$bookId]);
        $result = $stmt->fetch();
        return $result;
    }

    public function insertGenre($name, $color)
    {
        $sql = "INSERT INTO genre VALUES (null, ?, ?)";
        $this->exec($sql, [$name, $color]);
    }

    public function getGenreId($name)
    {
        $sql = "SELECT * FROM genre WHERE name = ?";
        $stmt = $this->query($sql, [$name]);
        $result = $stmt->fetch();
        return $result['ident'];
    }

    public function getGenre($genreId)
    {
        $sql = "SELECT * FROM genre WHERE ident = ?";
        $stmt = $this->query($sql, [$genreId]);
        $result = $stmt->fetch();
        return $result;
    }

    public function getAllPlatform()
    {
        $sql = "SELECT * FROM platform";
        $stmt = $this->query($sql, []);
        $result = $stmt->fetchAll();
        return $result;
    }

    public function getPlatformId($name)
    {
        $sql = "SELECT * FROM platform WHERE name = ?";
        $stmt = $this->query($sql, [$name]);
        $result = $stmt->fetch();
        return $result;
    }

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

    public function getAllTag()
    {
        $sql = "SELECT * FROM tag";
        $stmt = $this->query($sql, []);
        $result = $stmt->fetchAll();
        return $result;
    }

    public function insertTag($tag)
    {
        $sql = "INSERT INTO tag VALUES (null, ?)";
        $stmt = $this->exec($sql, [$tag]);
        $result = $this->getTagId($tag);
        return $result;
    }

    public function getTagId($name)
    {
        $sql = "SELECT * FROM tag WHERE name = ?";
        $stmt = $this->query($sql, [$name]);
        $result = $stmt->fetch();
        return $result['ident'];
    }

    public function getBookId($title)
    {
        $sql = "SELECT * FROM book WHERE title = ?";
        $stmt = $this->query($sql, [$title]);
        $result = $stmt->fetch();
        return $result['ident'];
    }

    public function insertBook($title, $author, $genre, $word, $url, $status, $cp, $fandom, $dramacd, $mascot, $remark)
    {
        $sql = "INSERT INTO book VALUES (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $this->exec($sql, [$title, $author, $genre, $word, $url, $status, $cp, $fandom, $dramacd, $mascot, $remark]);
    }

    public function insertReport($bookId, $review)
    {
        $sql = "INSERT INTO review VALUES (null, ?, ?)";
        $this->exec($sql, [$bookId, $review]);
    }

    public function insertTagJunction($bookId, $tagIds)
    {
        $sql = "INSERT INTO tag_junction VALUES (null, ?, ?)";
        foreach ($tagIds as $tagId) {
            $this->exec($sql, [$bookId, $tagId]);
        }
    }

    public function insertFandom($bookId, $platformId, $memo, $url)
    {
        $sql = "INSERT INTO work VALUES (null, ?, ?, ?, ?)";
        for ($i = 0; $i < sizeof($platformId); $i++) {
            $this->exec($sql, [$bookId, $platformId[$i], $memo[$i], $url[$i]]);
        }
    }

    public function insertDramacd($bookId, $platformId, $url, $code)
    {
        $sql = "INSERT INTO audio VALUES (null, ?, ?, ?, ?)";
        for ($i = 0; $i < sizeof($platformId); $i++) {
            $this->exec($sql, [$bookId, $platformId[$i], $url[$i], $code[$i]]);
        }
    }

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

    public function getDramacd($bookId)
    {
        $sql = "SELECT * FROM audio JOIN platform ON audio.platform_id = platform.ident WHERE audio.book_id = ?";
        $stmt = $this->query($sql, [$bookId]);
        $result = $stmt->fetchAll();
        return $result;
    }

    public function getFandom($bookId)
    {
        $sql = "SELECT * FROM work JOIN platform ON work.platform_id = platform.ident WHERE work.book_id = ?";
        $stmt = $this->query($sql, [$bookId]);
        $result = $stmt->fetchAll();
        return $result;
    }

    public function updateBook($bookId, $title, $author, $genre, $word, $url, $status, $cp, $fandom, $dramacd, $mascot, $remark)
    {
        $sql = "UPDATE book SET title = ?, author = ?, genre_id = ?, word_count = ?, url = ?, status = ?, cp = ?, fandom = ?, dramacd = ?, mascot = ?, remark = ? WHERE ident = ?";
        $this->exec($sql, [$title, $author, $genre, $word, $url, $status, $cp, $fandom, $dramacd, $mascot, $remark, $bookId]);
    }

    public function updateTagJunction($bookId, $tagIds)
    {
        $this->deleteTagJunction($bookId);
        $this->insertTagJunction($bookId, $tagIds);
    }

    public function updateReport($bookId, $report)
    {
        $sql = "UPDATE review SET content = ? WHERE book_id = ?";
        $this->exec($sql, [$report, $bookId]);
    }

    public function updateFandom($bookId, $platformId, $memo, $url)
    {
        $this->deleteFandom($bookId);
        $this->insertFandom($bookId, $platformId, $memo, $url);
    }

    public function updateDramacd($bookId, $platformId, $url, $code)
    {
        $this->deleteDramacd($bookId);
        $this->insertDramacd($bookId, $platformId, $url, $code);
    }

    public function deleteTagJunction($bookId)
    {
        $sql = "DELETE FROM tag_junction WHERE book_id = ?";
        $this->exec($sql, [$bookId]);
    }

    public function deleteFandom($bookId)
    {
        $sql = "DELETE FROM work WHERE book_id = ?";
        $this->exec($sql, [$bookId]);
    }

    public function deleteDramacd($bookId)
    {
        $sql = "DELETE FROM audio WHERE book_id = ?";
        $this->exec($sql, [$bookId]);
    }
}
