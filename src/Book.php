<?php
class Book

{
    private $id;
    private $title;

    function __construct($title, $id=null)
    {
        $this->id = $id;
        $this->title = $title;
    }

    function getTitle()
    {
        return $this->title;
    }

    function getId()
    {
        return $this->id;
    }

    function setTitle($new_title)
    {
        $this->title = $new_title;
    }

    function save()
    {
        $GLOBALS['DB']->exec("INSERT INTO t_books (title) VALUES
            ('{$this->getTitle()}');");
        $this->id = $GLOBALS['DB']->lastInsertId();
    }

    function updateTitle($new_title)
    {
        $GLOBALS['DB']->exec("UPDATE t_books set title = '{$new_title}' WHERE id = {$this->getId()};");
        $this->setTitle($new_title);
    }

    function deleteOne()
    {
        $GLOBALS['DB']->exec("DELETE FROM t_books WHERE id = {$this->getId()};");
    }

    static function getAll()
    {
        $returned_books = $GLOBALS['DB']->query("SELECT * FROM t_books;");
        $books = array();
        foreach($returned_books as $book) {
            $title = $book['title'];
            $id = $book['id'];
            $new_book = new Book($title, $id);
            array_push($books, $new_book);
        }
        return $books;
    }

    static function deleteAll()
    {
        $GLOBALS['DB']->exec("DELETE FROM t_books;");
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

    static function searchByTitle($search_string)
    {
        $clean_search_string = preg_replace('/[^A-Za-z0-9\s]/', '', $search_string);
        $lower_clean_search_string = strtolower($clean_search_string);
        $exploded_lower_clean_search_string = explode(' ', $lower_clean_search_string);
        $books = Book::getAll();
        $matches = array();
        foreach ($exploded_lower_clean_search_string as $word) {
            foreach ($books as $book) {
                $title = $book->getTitle();
                $clean_title= preg_replace('/[^A-Za-z0-9\s]/', '', $title);
                $lower_clean_title = strtolower($clean_title);
                $explode_lower_clean_title = explode(' ', $lower_clean_title);
                foreach ($explode_lower_clean_title as $title_pieces){
                    if($title_pieces == $word) {
                        array_push($matches, $book);
                    }
                }
            }
        }
        return $matches;
    }

    function addAuthor($author)
   {
       $GLOBALS['DB']->exec("INSERT INTO authors_books (authors_id, books_id) VALUES ({$author->getId()}, {$this->getId()});");
   }

   function getAuthor()
   {
       //join statement which starts at t_books and links each table by id's, step by step.
       //Starts@ t_books w/ id for books -> authors_books w/ book_id and matches it to the associated author_id -> takes the author id and links it to the original author id in t_authors -> looks for this specific instance of author.  This results in an associated array with keys first_name, last_name, and id
       $returned_authors = $GLOBALS['DB']->query("SELECT t_authors.* FROM t_books
           JOIN authors_books ON (t_books.id = authors_books.books_id)
           JOIN t_authors ON (authors_books.authors_id = t_authors.id)
           WHERE t_books.id = {$this->getId()};");
       //creates empty array to insert the linked author information
       $authors = array();
       //goes through the associated array created by the join statement and builds a new author object from that information
       foreach ($returned_authors as $author) {
           $first_name = $author['first_name'];
           $last_name = $author['last_name'];
           $id = $author['id'];
           $new_author = new Author($first_name, $last_name, $id);
           //pushes the newly created author objects to the empty array
           array_push($authors, $new_author);
       }
       //returns the newly filled array with the resulting author objects
       return $authors;
   }

    ////////////////////
    ////////Search functionality not yet functioning.
    ////////////////////
    // static function searchByLastName($search_string)
    // {
    //     //Takes in search query (ex: John Steinbeck) and removes foreign characters
    //     $clean_search_string = preg_replace('/[^A-Za-z0-9\s]/', '', $search_string);
    //     //Takes search query and makes it all lowercase.
    //     $lower_clean_search_string = strtolower($clean_search_string);
    //     //Gets all the authors.
    //     $authors = Author::getAll();
    //     //Creates an empty array.
    //     $matches = array();
    //     //Takes each author from the list of authors...
    //     foreach ($authors as $author) {
    //         //...and only looks at their last name.
    //         $author_last_name = $author->getLastName();
    //         // $clean_author = preg_replace('/[^A-Za-z0-9\s]/', '', $author);
    //         // $lower_clean_author = strtolower($clean_author);
    //         $coffee = $author_last_name->getId();
    //
    //
    //         //if the author that is found is equal to the search string, then it will look for the book.
    //         if($lower_clean_author == $lower_clean_search_string) {
    //
    //             //takes author id and looks for books in the database that match that author_id through the join table
    //             $book_match = $GLOBALS['DB']->query("SELECT t_books.* FROM
    //             t_authors JOIN t_authors ON (authors.id = authors_books.author_id)
    //             JOIN t_books ON (authors_books.book_id = books.id)
    //             WHERE authors.id = {coffee};");
    //
    //             //takes the resulting book id and finds the book title
    //             $book_result = $book_match->getTitle();
    //
    //             //pushes the resulting titles into the result array
    //             array_push($matches, $book_result);
    //         }
    //     }
    //
    //     return $matches;
    // }
}
?>
