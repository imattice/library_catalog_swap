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
            $title = "The Cat in the Hat";
            $test_book = new Book( $title);

            //Act
            $test_book->save();

            //Assert
            $result = Book::getAll();
            $this->assertEquals($test_book, $result[0]);
        }

        function test_getAll() {
            //Arrange
            $title = "The Cat in the Hat";
            $test_book = new Book( $title);
            $test_book->save();
            $title2 = "Misery";
            $test_book2 = new Book( $title2);
            $test_book2->save();

            //Act
            $result = Book::getAll();

            //Assert
            $this->assertEquals([$test_book, $test_book2], $result);
        }

        function test_deleteAll() {
            //Arrange
            $title = "The Cat in the Hat";
            $test_book = new Book( $title);
            $test_book->save();
            $title2 = "Misery";
            $test_book2 = new Book( $title2);
            $test_book2->save();

            //Act
            Book::deleteAll();

            //Assert
            $result = Book::getAll();
            $this->assertEquals([], $result);
        }

        function test_getId() {
            //Arrange
            $title = "The Cat in the Hat";
            $test_book = new Book( $title);
            $test_book->save();

            //Act
            $result = $test_book->getId();

            //Assert
            $this->assertEquals(true, is_numeric($result));
        }

        function test_find() {
            //Arrange
            $title = "The Cat in the Hat";
            $test_book = new Book( $title);
            $test_book->save();
            $title2 = "Misery";
            $test_book2 = new Book( $title2);
            $test_book2->save();

            //Act
            $id = $test_book->getId();
            $result = Book::find($id);

            //Assert
            $this->assertEquals($test_book, $result);
        }

        function testUpdateTitle() {
            //Arrange
            $title = "The Cat in the Hat";
            $test_book = new Book( $title);
            $test_book->save();
            $new_title = "Green Eggs and Ham";

            //Act
            $test_book->updateTitle($new_title);

            //Assert
            $this->assertEquals("Green Eggs and Ham", $test_book->getTitle());
        }

        function testDeleteOne() {
            //Arrange
            $title = "The Cat in the Hat";
            $test_book = new Book( $title);
            $test_book->save();
            $title2 = "Misery";
            $test_book2 = new Book( $title2);
            $test_book2->save();

            //Act
            $test_book->deleteOne();
            $result = Book::getAll();

            //Assert
            $this->assertEquals($test_book2, $result[0]);
        }

        function testSearchByTitle() {
            //Arrange
            $title = "The Cat in the Hat";
            $test_book = new Book( $title);
            $test_book->save();
            $title2 = "Misery";
            $test_book2 = new Book( $title2);
            $test_book2->save();
            $title3 = "Cat on a Hot Tin Roof";
            $test_book3 = new Book ($title3);
            $test_book3->save();
            $search_string = "Cat";

            //Act
            $result = Book::searchByTitle($search_string);

            //Assert
            $this->assertEquals([$test_book, $test_book3], $result);
        }

        // function testSearchByAuthorLast() {
        //     //Arrange
        //     $author_first = "Dr.";
        //     $author_last = "Seuss";
        //     $title = "The Cat in the Hat";
        //     $test_book = new Book( $title);
        //     $test_book->save();
        //     $author_first2 = "Stephen";
        //     $author_last2 = "King";
        //     $title2 = "Misery";
        //     $test_book2 = new Book( $title2);
        //     $test_book2->save();
        //     $author_first3 = "Stephen";
        //     $author_last3 = "King";
        //     $title3 = "The Dark Tower";
        //     $test_book3 = new Book( $title3);
        //     $test_book3->save();
        //     $search_string = "King";
        //
        //     //Act
        //     $result = Book::searchByAuthorLast($search_string);
        //
        //     //Assert
        //     $this->assertEquals([$test_book2, $test_book3], $result);
        // }
    }
?>
