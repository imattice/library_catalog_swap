<?php
class Book

{
    private $author;
    private $title;
    private $id;

    function __construct($author, $title, $id=null)
    {
        $this->author = $author;
        $this->id = $id;
        $this->title = $title;
    }

    function getAuthor()
    {
        return $this->author;
    }

    function getTitle()
    {
        return $this->title;
    }

    function getId()
    {
        return $this->id;
    }

    function setAuthor($new_author)
    {
        $this->author = $new_author;
    }

    function setTitle($new_title)
    {
        $this->title = $new_title;
    }

    function save()
    {
        $GLOBALS['DB']->exec("INSERT INTO books (author, title) VALUES ('{$this->getAuthor()}', '{$this->getTitle()}')");
        $this->id = $GLOBALS['DB']->lastInsertId();
    }

    function updateAuthor($new_author)
    {
        $GLOBALS['DB']->exec("UPDATE books set author = '{$new_author}' WHERE id = {$this->getId()};");
        $this->setAuthor($new_author);
    }

    function updateTitle($new_title)
    {
        $GLOBALS['DB']->exec("UPDATE books set title = '{$new_title}' WHERE id = {$this->getId()};");
        $this->setTitle($new_title);
    }

    function deleteOne()
    {
        $GLOBALS['DB']->exec("DELETE FROM books WHERE id = {$this->getId()};");
    }

    static function getAll()
    {
        $returned_books = $GLOBALS['DB']->query("SELECT * FROM books;");
        $books = array();
        foreach($returned_books as $book) {
            $author = $book['author'];
            $title = $book['title'];
            $id = $book['id'];
            $new_book = new Book($author, $title, $id);
            array_push($books, $new_book);
        }
        return $books;
    }

    static function deleteAll()
    {
        $GLOBALS['DB']->exec("DELETE FROM books;");
    }

    static function find($search_id)
    {
        $found_book = null;
        $books = Book::getAll();
        foreach($books as $book) {
            $book_id = $book->getId();
            if ($book_id == $search_id) {
                $found_book = $book;
            }
        }
        return $found_book;
    }
}
?>
