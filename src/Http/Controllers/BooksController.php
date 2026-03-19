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

    public function show($id)
    {
        $bookRepo = $this->br;
        $bookId = new BookId($id);

        $book=$bookRepo->find($bookId, $bookRepo);

        $response = new ResponseJson(200, $book->toArray());
        $response->send();
    }

    public function create()
    {
        $data = $this->request->getBody();
        $book = Book::fromArray($data);
        $this->br->save($book);

        $response = new ResponseJson(201, $book->toArray());
        $response->send();
    }

    public function update(string $id)
    {
    }

    public function delete(string $id)
    {
    }
    
}