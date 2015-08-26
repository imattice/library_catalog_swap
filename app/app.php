<?php

    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Book.php";

    $app = new Silex\Application();
    $app['debug'] = true;

    $server = 'mysql:host=localhost;dbname=library';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views'
    ));

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    $app->get("/", function() use ($app)
    {
        return $app['twig']->render('index.html.twig', array('books' => Book::getAll()));
    });

    $app->get("/add_book", function() use ($app)
    {
        return $app['twig']->render('add_book.html.twig', array('books' => Book::getAll()));
    });

    $app->post("/add_book", function() use($app)
    {
        $book = new Book($_POST['author_first'], $_POST['author_last'], $_POST['title']);
        $book->save();
        return $app['twig']->render('add_book.html.twig', array('books' => Book::getAll()));
    });

    $app->post("/delete_books", function() use($app)
    {
        Book::deleteAll();
        return $app['twig']->render('add_book.html.twig', array('books' => Book::getAll()));
    });

    $app->get("/books/{id}/edit", function($id) use ($app)
    {
        $book = Book::find($id);
        return $app['twig']->render('book.html.twig', array('book' => $book));
    });

    $app->patch("/book/{id}/authorfirst", function($id) use ($app)
    {
        $author_first = $_POST['author_first'];
        $book = Book::find($id);
        $book->updateAuthorFirst($author_first);
        return $app['twig']->render('add_book.html.twig', array('books' => Book::getAll()));
    });

    $app->patch("/book/{id}/authorlast", function($id) use ($app)
    {
        $author_last = $_POST['author_last'];
        $book = Book::find($id);
        $book->updateAuthorLast($author_last);
        return $app['twig']->render('add_book.html.twig', array('books' => Book::getAll()));
    });

    $app->patch("/book/{id}/title", function($id) use ($app)
    {
        $title = $_POST['title'];
        $book = Book::find($id);
        $book->updateTitle($title);
        return $app['twig']->render('add_book.html.twig', array('books' => Book::getAll()));
    });

    $app->delete("/book/{id}", function($id) use ($app) {
        $book = Book::find($id);
        $book->deleteOne();
        return $app['twig']->render('add_book.html.twig', array('books' => Book::getAll()));
    });

    $app->post("/search/title", function() use ($app)
    {
        $title = $_POST['search_title'];
        $matches = Book::searchByTitle($title);

        return $app['twig']->render('search_results.html.twig', array('matches' => $matches));
    });

    $app->post("/search/author", function() use ($app)
    {
        $author = $_POST['search_author'];
        $matches = Book::searchByAuthorLast($author);

        return $app['twig']->render('search_results.html.twig', array('matches' => $matches));
    });

    return $app;
?>
