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

        function testGetFirstName()
        {
            //Arrange
            $first_name = "J.K.";
            $last_name = "Rowling";
            $test_author = new Author($first_name, $last_name);
            //Act
            $result = $test_author->getFirstName();
            //Assert
            $this->assertEquals($first_name, $result);
        }

        function testGetLastName()
        {
            //Arrange
            $first_name = "J.K.";
            $last_name = "Rowling";
            $test_author = new Author($first_name, $last_name);
            //Act
            $result = $test_author->getLastName();
            //Assert
            $this->assertEquals($last_name, $result);
        }

        function testSetFirstName()
        {
            //Arrange
            $first_name = "J.K.";
            $last_name = "Rowling";
            $test_author = new Author($first_name, $last_name);
            //Act
            $test_author->setFirstName("J.K.");
            $result = $test_author->getFirstName();
            //Assert
            $this->assertEquals("J.K.", $result);
        }

        function testSetLastName()
        {
            //Arrange
            $first_name = "J.K.";
            $last_name = "Rowling";
            $test_author = new Author($first_name, $last_name);
            //Act
            $test_author->setLastName("Rowling");
            $result = $test_author->getLastName();
            //Assert
            $this->assertEquals("Rowling", $result);
        }

        function testGetId()
        {
            //Arrange
            $first_name = "J.K.";
            $last_name = "Rowling";
            $test_author = new Author($first_name, $last_name);
            $test_author->save();
            //Act
            $result = $test_author->getId();
            //Assert
            $this->assertEquals(true, is_numeric($result));
        }
        function testSave()
        {
            //Arrange
            $first_name = "J.K.";
            $last_name = "Rowling";
            $test_author = new Author($first_name, $last_name);
            //Act
            $test_author->save();
            //Assert
            $result = Author::getAll();
            $this->assertEquals($test_author, $result[0]);
        }
        function testGetAll()
        {
            //Arrange
            $first_name = "J.K.";
            $last_name = "Rowling";
            $test_author = new Author($first_name, $last_name);
            $test_author->save();
            $first_name2 = "John";
            $last_name2 = "Steinbeck";
            $test_author2 = new Author($first_name2, $last_name2);
            $test_author2->save();
            //Act
            $result = Author::getAll();
            //Assert
            $this->assertEquals([$test_author, $test_author2], $result);
        }
        function testDeleteAll()
        {
            //Arrange
            $first_name = "J.K.";
            $last_name = "Rowling";
            $test_author = new Author($first_name, $last_name);
            $test_author->save();

            $first_name2 = "John";
            $last_name2 = "Steinbeck";
            $test_author2 = new Author($first_name2, $last_name2);
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
            $first_name = "J.K.";
            $last_name = "Rowling";
            $test_author = new Author($first_name, $last_name);
            $test_author->save();
            $column_to_update = "first_name";
            $new_patron_information = "Jane";

            //Act
            $test_author->update($column_to_update, $new_patron_information);
            $result = Author::getAll();

            //Assert
            $this->assertEquals("Jane", $result[0]->getFirstName());
        }

        function testDeleteOne()
        {
            //Arrange
            $first_name = "J.K.";
            $last_name = "Rowling";
            $test_author = new Author($first_name, $last_name);
            $test_author->save();

            $first_name2 = "John";
            $last_name2 = "Steinbeck";
            $test_author2 = new Author($first_name2, $last_name2);
            $test_author2->save();

            //Act
            $test_author->deleteOne();

            //Assert
            $this->assertEquals([$test_author2], Author::getAll());
        }
        ///////////
        ///////////////All tests pass up to this point. Recieving errors for tests below.
        //////////
        function testAddBook()
        {
            //Arrange
            $first_name = "John Steinbeck";
            $test_author = new Author($first_name, $last_name);
            $test_author->save();

            $title = "Grapes of Wrath";
            $test_book = new Book($title);
            $test_book->save();

            //Act
            $test_author->addBook($test_book);

            //Assert
            $this->assertEquals($test_author->getBook(), [$test_book]);

        }

        function testGetBook()
        {
            //Arrange
            $first_name = "John Steinbeck";
            $test_author = new Author($first_name, $last_name);
            $test_author->save();

            $title = "Grapes of Wrath";
            $test_book = new Book($title);
            $test_book->save();

            $title2 = "Cannery Row";
            $test_book2 = new Book($title2);
            $test_book2->save();


            //Act
            $test_author->addBook($test_book);
            $test_author->addBook($test_book2);

            $result = $test_author->getBook();

            //Assert
            $this->assertEquals([$test_book, $test_book2], $result);
        }
    }
 ?>
