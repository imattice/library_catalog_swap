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

    $app->post("/add_book", function() use($app)
    {
        $book = new Book($_POST['author'], $_POST['title']);
        $book->save();
        return $app['twig']->render('index.html.twig', array('books' => Book::getAll()));
    });

    $app->post("/delete_books", function() use($app)
    {
        Book::deleteAll();
        return $app['twig']->render('index.html.twig', array('books' => Book::getAll()));
    });

    $app->get("/books/{id}/edit", function($id) use ($app)
    {
        $book = Book::find($id);
        return $app['twig']->render('book.html.twig', array('book' => $book));
    });

    $app->patch("/book/{id}/author", function($id) use ($app)
    {
        $author = $_POST['author'];
        $book = Book::find($id);
        $book->updateAuthor($author);
        return $app['twig']->render('index.html.twig', array('books' => Book::getAll()));    });

    $app->patch("/book/{id}/title", function($id) use ($app)
    {
        $title = $_POST['title'];
        $book = Book::find($id);
        $book->updateTitle($title);
        return $app['twig']->render('index.html.twig', array('books' => Book::getAll()));    });

    $app->delete("/book/{id}", function($id) use ($app) {
        $book = Book::find($id);
        $book->deleteOne();
        return $app['twig']->render('index.html.twig', array('books' => Book::getAll()));
    });

    return $app;
?>
