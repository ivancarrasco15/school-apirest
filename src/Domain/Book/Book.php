<?php


    namespace App\Domain\Book;

use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;



    #[Entity]
    #[Table(name:'books')]
    class Book{
        #[Id]
        #[Column(type:'string',length:36)]
        private string $id;
        #[Column(type:'string')]
        private string $title;
        #[Column(type:'string')]
        private string $author;
        #[Column(type:'boolean')]
        private bool $available=true;

        function __construct(BookId $id, string $title)
        {
            $this->id=(string)$id;
            $this->title=$title;
        }
        
        public function setTitle(string $title):void
        {
            if(empty(trim($title))){
                throw new \InvalidArgumentException("Title cannot be empty");
            }
            $this->title = $title;

        }

        public function id():BookId{
            return new BookId($this->id);
        }

        public function borrow():void{
            if(!$this->available){
                throw new \DomainException('Book not available');
            }
        }

        public function toArray(): array{
            return [
                'id'=>$this->id,
                'title'=>$this->title,
                'author'=>$this->author,
                'available'=>$this->available
            ];
        }

        public static function fromArray(array $data): self{
            if(!isset($data['id']) || !isset($data['title'])){
                throw new \InvalidArgumentException("Missing required fields: id and title");
            }
            $book = new self(new BookId($data['id']), $data['title']);
            if(isset($data['author'])){
                $book->author = $data['author'];
            }
            if(isset($data['available'])){
                $book->available = (bool)$data['available'];
            }
            return $book;
        }      
        
    }