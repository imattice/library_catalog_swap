<?php
class Book

{
    private $author_first;
    private $author_last;
    private $title;
    private $id;

    function __construct($author_first, $author_last, $title, $id=null)
    {
        $this->author_first = $author_first;
        $this->author_last = $author_last;
        $this->id = $id;
        $this->title = $title;
    }

    function getAuthorFirst()
    {
        return $this->author_first;
    }

    function getAuthorLast()
    {
        return $this->author_last;
    }

    function getTitle()
    {
        return $this->title;
    }

    function getId()
    {
        return $this->id;
    }

    function setAuthorFirst($new_author_first)
    {
        $this->author_first = $new_author_first;
    }

    function setAuthorLast($new_author_last)
    {
        $this->author_last = $new_author_last;
    }

    function setTitle($new_title)
    {
        $this->title = $new_title;
    }

    function save()
    {
        $GLOBALS['DB']->exec("INSERT INTO books (author_first, author_last, title) VALUES ('{$this->getAuthorFirst()}', '{$this->getAuthorLast()}' '{$this->getTitle()}')");
        $this->id = $GLOBALS['DB']->lastInsertId();
    }

    function updateAuthorFirst($new_author_first)
    {
        $GLOBALS['DB']->exec("UPDATE books set author_first = '{$new_author_first}' WHERE id = {$this->getId()};");
        $this->setAuthorFirst($new_author_first);
    }

    function updateAuthorLast($new_author_last)
    {
        $GLOBALS['DB']->exec("UPDATE books set author_last = '{$new_author_last}' WHERE id = {$this->getId()};");
        $this->setAuthorLast($new_author_last);
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
            $author_first = $book['author_first'];
            $author_last = $book['author_last'];
            $title = $book['title'];
            $id = $book['id'];
            $new_book = new Book($author_first, $author_last, $title, $id);
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
