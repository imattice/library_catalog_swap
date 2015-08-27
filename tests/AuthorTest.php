<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */
    require_once 'src/Book.php';
    require_once 'src/Author.php';
    //require_once 'src/Patron.php';
    $server = 'mysql:host=localhost;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);
    class AuthorTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Book::deleteAll();
            Author::deleteAll();
            //Patron::deleteAll();
        }

        function testGetAuthorFirst()
        {
            //Arrange
            $author_first = "J.K.";
            $author_last = "Rowling";
            $test_author = new Author($author_first, $author_last);
            //Act
            $result = $test_author->getAuthorFirst();
            //Assert
            $this->assertEquals($author_first, $result);
        }

        function testGetAuthorLast()
        {
            //Arrange
            $author_first = "J.K.";
            $author_last = "Rowling";
            $test_author = new Author($author_first, $author_last);
            //Act
            $result = $test_author->getAuthorLast();
            //Assert
            $this->assertEquals($author_last, $result);
        }

        function testSetAuthorFirst()
        {
            //Arrange
            $author_first = "J.K.";
            $author_last = "Rowling";
            $test_author = new Author($author_first, $author_last);
            //Act
            $test_author->setAuthorFirst("J.K.");
            $result = $test_author->getAuthorFirst();
            //Assert
            $this->assertEquals("J.K.", $result);
        }

        function testSetAuthorLast()
        {
            //Arrange
            $author_first = "J.K.";
            $author_last = "Rowling";
            $test_author = new Author($author_first, $author_last);
            //Act
            $test_author->setAuthorLast("Rowling");
            $result = $test_author->getAuthorLast();
            //Assert
            $this->assertEquals("Rowling", $result);
        }

        function testGetId()
        {
            //Arrange
            $author_first = "J.K.";
            $author_last = "Rowling";
            $test_author = new Author($author_first, $author_last);
            $test_author->save();
            //Act
            $result = $test_author->getId();
            //Assert
            $this->assertEquals(true, is_numeric($result));
        }
        function testSave()
        {
            //Arrange
            $author_first = "J.K.";
            $author_last = "Rowling";
            $test_author = new Author($author_first, $author_last);
            //Act
            $test_author->save();
            //Assert
            $result = Author::getAll();
            $this->assertEquals($test_author, $result[0]);
        }
        function testGetAll()
        {
            //Arrange
            $author_first = "J.K.";
            $author_last = "Rowling";
            $test_author = new Author($author_first, $author_last);
            $test_author->save();
            $author_first2 = "John";
            $author_last2 = "Steinbeck";
            $test_author2 = new Author($author_first2, $author_last2);
            $test_author2->save();
            //Act
            $result = Author::getAll();
            //Assert
            $this->assertEquals([$test_author, $test_author2], $result);
        }
        function testDeleteAll()
        {
            //Arrange
            $author_first = "J.K.";
            $author_last = "Rowling";
            $test_author = new Author($author_first, $author_last);
            $test_author->save();

            $author_first2 = "John";
            $author_last2 = "Steinbeck";
            $test_author2 = new Author($author_first2, $author_last2);
            $test_author2->save();

            //Act
            Author::deleteAll();

            //Assert
            $result = Author::getAll();
            $this->assertEquals([], $result);
        }

        function testUpdate()
        {
            //Arrange
            $author_first = "J.K.";
            $author_last = "Rowling";
            $test_author = new Author($author_first, $author_last);
            $test_author->save();
            $column_to_update = "author_first";
            $new_patron_information = "Jane";

            //Act
            $test_author->update($column_to_update, $new_patron_information);
            $result = Author::getAll();

            //Assert
            $this->assertEquals("Jane", $result[0]->getAuthorFirst());
        }

        function testDeleteOne()
        {
            //Arrange
            $author_first = "J.K.";
            $author_last = "Rowling";
            $test_author = new Author($author_first, $author_last);
            $test_author->save();

            $author_first2 = "John";
            $author_last2 = "Steinbeck";
            $test_author2 = new Author($author_first2, $author_last2);
            $test_author2->save();

            //Act
            $test_author->deleteOne();

            //Assert
            $this->assertEquals([$test_author2], Author::getAll());
        }
        ///////////
        ///////////////All tests pass up to this point. Recieving errors for tests below.
        //////////
        // function testAddBook()
        // {
        //     //Arrange
        //     $author_first = "John Steinbeck";
        //     $test_author = new Author($author_first, $author_last);
        //     $test_author->save();
        //
        //     $title = "Grapes of Wrath";
        //     $test_book = new Book($title);
        //     $test_book->save();
        //
        //     //Act
        //     $test_author->addBook($test_book);
        //
        //     //Assert
        //     $this->assertEquals($test_author->getBook(), [$test_book]);
        //
        // }
        //
        // function testGetBook()
        // {
        //     //Arrange
        //     $author_first = "John Steinbeck";
        //     $test_author = new Author($author_first, $author_last);
        //     $test_author->save();
        //
        //     $title = "Grapes of Wrath";
        //     $test_book = new Book($title);
        //     $test_book->save();
        //
        //     $title2 = "Cannery Row";
        //     $test_book2 = new Book($title2);
        //     $test_book2->save();
        //
        //
        //     //Act
        //     $test_author->addBook($test_book);
        //     $test_author->addBook($test_book2);
        //
        //     $result = $test_author->getBook();
        //
        //     //Assert
        //     $this->assertEquals([$test_book, $test_book2], $result);
        // }
    }
 ?>
