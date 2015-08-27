<?php
class Author
{
    private $first_name;
    private $last_name;
    private $id;

    function __construct($first_name, $last_name, $id=null)
    {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->id = $id;
    }

    function getFirstName()
    {
        return $this->first_name;
    }

    function getLastName()
    {
        return $this->last_name;
    }

    function setFirstName($new_first_name)
    {
        $this->first_name = $new_first_name;
    }

    function setLastName($new_last_name)
    {
        $this->last_name = $new_last_name;
    }

    function getId()
    {
        return $this->id;
    }

    function save()
    {
        $GLOBALS['DB']->exec("INSERT INTO t_authors (first_name, last_name) VALUES ('{$this->getFirstName()}', '{$this->getLastName()}');");
        $this->id = $GLOBALS['DB']->lastInsertId();
    }

    function update($column_to_update, $new_author_information)
     {
         $GLOBALS['DB']->exec("UPDATE t_authors SET {$column_to_update} = '{$new_author_information}' WHERE id = {$this->getId()};");
     }

    function deleteOne()
    {
        $GLOBALS['DB']->exec("DELETE FROM t_authors WHERE id = {$this->getId()};");
    }
    ///////////
    ///////////////All tests pass up to this point. Recieving errors for tests pertaining to functions commented-out below.
    //////////
    function addBook($book)
    {
        $GLOBALS['DB']->exec("INSERT INTO authors_books (author_id, book_id) VALUES ({$this->getId()}, {$book->getId()});");
    }

    function getBook()
    {
        $query = $GLOBALS['DB']->query("SELECT book_id FROM authors_books WHERE author_id = {$this->getId()};");
        $book_ids = $query->fetchAll(PDO::FETCH_ASSOC);

        $books = array();
        foreach ($book_ids as $id) {
            $book_id = $id['book_id'];
            $result = $GLOBALS['DB']->query("SELECT * FROM t_books WHERE id = {$book_id};");
            $returned_book = $result->fetchAll(PDO::FETCH_ASSOC);

            $title = $returned_book[0]['title'];
            $id = $returned_book[0]['id'];
            $new_book = new Book($title, $id);
            array_push($books, $new_book);
        }
        return $books;
    }
    static function getAll()
    {
        $returned_authors = $GLOBALS['DB']->query("SELECT * FROM t_authors;");
        $authors = array();
        foreach($returned_authors as $author) {
            $first_name = $author['first_name'];
            $last_name = $author['last_name'];
            $id = $author['id'];
            $new_author = new Author($first_name, $last_name, $id);
            array_push($authors, $new_author);
        }
        return $authors;
    }

    static function deleteAll()
    {
        $GLOBALS['DB']->exec("DELETE FROM t_authors;");
    }
}
?>
