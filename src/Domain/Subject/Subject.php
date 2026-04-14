<?php

namespace App\Domain\Subject;

use App\Domain\Teacher\TeacherId;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;

#[Entity]
#[Table(name: 'subjects')]
class Subject
{
    #[Id]
    #[Column(type: 'string', length: 36)]
    private string $id;

    #[Column(type: 'string')]
    private string $name;

    #[Column(name: 'teacher_id', type: 'string', length: 36, nullable: true)]
    private ?string $teacherId = null;

    public function __construct(SubjectId $id, string $name, ?TeacherId $teacherId = null)
    {
        $this->id = (string) $id;
        $this->setName($name);

        if ($teacherId !== null) {
            $this->assignTeacher($teacherId);
        }
    }

    public function id(): SubjectId
    {
        return new SubjectId($this->id);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function teacherId(): ?TeacherId
    {
        return $this->teacherId ? new TeacherId($this->teacherId) : null;
    }

    public function setName(string $name): void
    {
        if (empty(trim($name))) {
            throw new \InvalidArgumentException('Name cannot be empty');
        }

        $this->name = $name;
    }

    public function assignTeacher(TeacherId $teacherId): void
    {
        $this->teacherId = $teacherId->value();
    }

    public function unassignTeacher(): void
    {
        $this->teacherId = null;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'teacher_id' => $this->teacherId
        ];
    }
}