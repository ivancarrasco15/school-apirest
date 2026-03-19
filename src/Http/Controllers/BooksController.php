<?php

namespace App\Http\Controllers;
use App\Http\Request;
use App\Http\Responsejson;
use App\Infrastructure\Persistence\Doctrine\DoctrineBookRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Domain\Book\BookId;
use App\Domain\Book\Book;

class BooksController {
    protected Request $request;
    private DoctrineBookRepository $br;

    public function __construct(Request $request, EntityManagerInterface $em)
    {
        $this->request = $request;
        $this->br = new DoctrineBookRepository($em);
    }
    public function index(){
        $bookRepo = $this->br;
        $books = $bookRepo->findAll();

        $response = new ResponseJson(200, $books);
        $response->send();
        //genero respuesta 200 ok + libros JSON
    }

    public function show(string $id): void
    {
        $book = $this->br->find(new BookId($id));

        if ($book === null) {
            (new ResponseJson(404, ['msg' => 'Book not found']))->send();
            return;
        }

        (new ResponseJson(200, $book->toArray()))->send();
    }

    public function create()
    {
        $data = $this->request->getBody();
        $book = new Book(new BookId($data['id']), $data['title'], $data['author'], $data['available']);
        $this->br->save($book);
    }

    public function update(string $id)
    {
            $book = $this->br->find(new BookId($id));
    
            if ($book === null) {
                (new ResponseJson(404, ['msg' => 'Book not found']))->send();
                return;
            }
    
            $data = $this->request->getBody();
            if (isset($data['title'])) {
                $book->setTitle($data['title']);
            }
            if (isset($data['author'])) {
                $book->setAuthor($data['author']);
            }
            if (isset($data['available'])) {
                $book->setAvailable($data['available']);
            }
    
            $this->br->save($book);
    }

    public function delete(string $id)
    {
        $book = $this->br->find(new BookId($id));

        if ($book === null) {
            (new ResponseJson(404, ['msg' => 'Book not found']))->send();
            return;
        }

        $this->br->delete($book);
    }
    
}