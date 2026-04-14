<?php

namespace App\Http\Controllers;

use App\Http\Request;
use App\Http\Responsejson;
use Doctrine\ORM\EntityManagerInterface;
use App\Domain\Student\Student;
use App\Domain\Student\StudentId;
use App\Infrastructure\Persistence\Doctrine\DoctrineStudentRepository;

class StudentsController
{
    protected Request $request;
    private DoctrineStudentRepository $sr;

    public function __construct(Request $request, EntityManagerInterface $em)
    {
        $this->request = $request;
        $this->sr = new DoctrineStudentRepository($em);
    }

    public function index(): void
    {
        $students = $this->sr->findAll();
        $response = new Responsejson(200, $students);
        $response->send();
    }

    public function show(string $id): void
    {
        $student = $this->sr->find(new StudentId($id));

        if ($student === null) {
            (new Responsejson(404, ['msg' => 'Student not found']))->send();
            return;
        }

        (new Responsejson(200, $student->toArray()))->send();
    }

    public function create(): void
    {
        try {
            $data = $this->request->getBody();

            if (
                !isset($data['id']) ||
                !isset($data['name']) ||
                !isset($data['email'])
            ) {
                (new Responsejson(400, ['msg' => 'id, name and email are required']))->send();
                return;
            }

            $student = new Student(
                new StudentId($data['id']),
                $data['name'],
                $data['email']
            );

            $this->sr->save($student);

            (new Responsejson(201, $student->toArray()))->send();
        } catch (\InvalidArgumentException $e) {
            (new Responsejson(400, ['msg' => $e->getMessage()]))->send();
        } catch (\Throwable $e) {
            (new Responsejson(500, ['msg' => 'Error creating student']))->send();
        }
    }

    public function update(string $id): void
    {
        try {
            $student = $this->sr->find(new StudentId($id));

            if ($student === null) {
                (new Responsejson(404, ['msg' => 'Student not found']))->send();
                return;
            }

            $data = $this->request->getBody();

            if (isset($data['name'])) {
                $student->setName($data['name']);
            }

            if (isset($data['email'])) {
                $student->setEmail($data['email']);
            }

            $this->sr->save($student);

            (new Responsejson(200, $student->toArray()))->send();
        } catch (\InvalidArgumentException $e) {
            (new Responsejson(400, ['msg' => $e->getMessage()]))->send();
        } catch (\Throwable $e) {
            (new Responsejson(500, ['msg' => 'Error updating student']))->send();
        }
    }

    public function delete(string $id): void
    {
        try {
            $student = $this->sr->find(new StudentId($id));

            if ($student === null) {
                (new Responsejson(404, ['msg' => 'Student not found']))->send();
                return;
            }

            $this->sr->delete($student);

            (new Responsejson(200, ['msg' => 'Student deleted successfully']))->send();
        } catch (\Throwable $e) {
            (new Responsejson(500, ['msg' => 'Error deleting student']))->send();
        }
    }
}