<?php

namespace App\Http\Controllers;

use App\Http\Request;
use App\Http\Responsejson;
use Doctrine\ORM\EntityManagerInterface;
use App\Domain\Teacher\Teacher;
use App\Domain\Teacher\TeacherId;
use App\Infrastructure\Persistence\Doctrine\DoctrineTeacherRepository;

class TeachersController
{
    protected Request $request;
    private DoctrineTeacherRepository $tr;

    public function __construct(Request $request, EntityManagerInterface $em)
    {
        $this->request = $request;
        $this->tr = new DoctrineTeacherRepository($em);
    }

    public function index(): void
    {
        $teachers = $this->tr->findAll();
        $response = new Responsejson(200, $teachers);
        $response->send();
    }

    public function show(string $id): void
    {
        $teacher = $this->tr->find(new TeacherId($id));

        if ($teacher === null) {
            (new Responsejson(404, ['msg' => 'Teacher not found']))->send();
            return;
        }

        (new Responsejson(200, $teacher->toArray()))->send();
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

            $teacher = new Teacher(
                new TeacherId($data['id']),
                $data['name'],
                $data['email']
            );

            $this->tr->save($teacher);

            (new Responsejson(201, $teacher->toArray()))->send();
        } catch (\InvalidArgumentException $e) {
            (new Responsejson(400, ['msg' => $e->getMessage()]))->send();
        } catch (\Throwable $e) {
            (new Responsejson(500, ['msg' => 'Error creating teacher']))->send();
        }
    }

    public function update(string $id): void
    {
        try {
            $teacher = $this->tr->find(new TeacherId($id));

            if ($teacher === null) {
                (new Responsejson(404, ['msg' => 'Teacher not found']))->send();
                return;
            }

            $data = $this->request->getBody();

            if (isset($data['name'])) {
                $teacher->setName($data['name']);
            }

            if (isset($data['email'])) {
                $teacher->setEmail($data['email']);
            }

            $this->tr->save($teacher);

            (new Responsejson(200, $teacher->toArray()))->send();
        } catch (\InvalidArgumentException $e) {
            (new Responsejson(400, ['msg' => $e->getMessage()]))->send();
        } catch (\Throwable $e) {
            (new Responsejson(500, ['msg' => 'Error updating teacher']))->send();
        }
    }

    public function delete(string $id): void
    {
        try {
            $teacher = $this->tr->find(new TeacherId($id));

            if ($teacher === null) {
                (new Responsejson(404, ['msg' => 'Teacher not found']))->send();
                return;
            }

            $this->tr->delete($teacher);

            (new Responsejson(200, ['msg' => 'Teacher deleted successfully']))->send();
        } catch (\Throwable $e) {
            (new Responsejson(500, ['msg' => 'Error deleting teacher']))->send();
        }
    }
}