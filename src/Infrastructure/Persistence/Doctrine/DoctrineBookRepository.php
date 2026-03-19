<?php
namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Book\Book;
use App\Domain\Book\BookId;
use App\Domain\Book\IBookRepository;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineBookRepository implements IBookRepository{

    public function __construct(
        private EntityManagerInterface $em)
    {
    }
    public function find(BookId $id):?Book
    {
        $book = $this->em->find(Book::class,$id);
        return $book;
    }
    public function save(Book $book):void{
        $this->em->persist($book); //para hacerlo otra vez
        $this->em->flush();
    }
    public function findAll():array{
        $repository = $this->em->getRepository(Book::class);
        $books=$repository->findAll();
        $data = array_map(fn($book) => $book->toArray(), $books);
        return $data;
    } 

} 