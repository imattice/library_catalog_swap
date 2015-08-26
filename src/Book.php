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

    // function getId()
    // {
    //     return $this->id;
    // }

    function save()
    {
        $GLOBALS['DB']->exec("INSERT INTO books (author, title) VALUES ('{$this->getAuthor()}', '{$this->getTitle()}')");
        $this->id = $GLOBALS['DB']->lastInsertId();
    }

    static function getAll() {
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

    static function deleteAll() {
        $GLOBALS['DB']->exec("DELETE FROM books;");
    }
}
?>
