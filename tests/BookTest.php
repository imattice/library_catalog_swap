<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Book.php";

    $server = 'mysql:host=localhost;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class BookTest extends PHPUnit_Framework_TestCase {

        protected function tearDown() {
            Book::deleteAll();
        }

        function test_save() {
            //Arrange
            $author = "Dr. Seuss";
            $title = "The Cat in the Hat";
            $test_book = new Book($author, $title);

            //Act
            $test_book->save();

            //Assert
            $result = Book::getAll();
            $this->assertEquals($test_book, $result[0]);
        }

        function test_getAll() {
            //Arrange
            $author = "Dr. Seuss";
            $title = "The Cat in the Hat";
            $test_book = new Book($author, $title);
            $test_book->save();
            $author2 = "Stephen King";
            $due_date2 = "Misery";
            $test_book2 = new Book($author2, $due_date2);
            $test_book2->save();

            //Act
            $result = Book::getAll();

            //Assert
            $this->assertEquals([$test_book, $test_book2], $result);
        }

        function test_deleteAll() {
            //Arrange
            $author = "Dr. Seuss";
            $title = "The Cat in the Hat";
            $test_book = new Book($author, $title);
            $test_book->save();
            $author2 = "Stephen King";
            $due_date2 = "Misery";
            $test_book2 = new Book($author2, $due_date2);
            $test_book2->save();

            //Act
            Book::deleteAll();

            //Assert
            $result = Book::getAll();
            $this->assertEquals([], $result);
        }
    }
?>
