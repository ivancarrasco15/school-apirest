<?php

namespace App\Http\Controllers;

use App\Http\Request;
use App\Http\Responsejson;
use App\Domain\Subject\Subject;
use App\Domain\Subject\SubjectId;
use App\Domain\Teacher\TeacherId;
use App\Infrastructure\Persistence\Doctrine\DoctrineSubjectRepository;
use Doctrine\ORM\EntityManagerInterface;

class SubjectsController {
    protected Request $request;
    private DoctrineSubjectRepository $sr;

    public function __construct(Request $request, EntityManagerInterface $em) {
        $this->request = $request;
        $this->sr = new DoctrineSubjectRepository($em);
    }

    public function index(): void {
        $subjects = $this->sr->findAll();
        (new Responsejson(200, $subjects))->send();
    }

    public function show(string $id): void {
        $subject = $this->sr->find(new SubjectId($id));
        if ($subject === null) {
            (new Responsejson(404, ['msg' => 'Subject not found']))->send();
            return;
        }
        (new Responsejson(200, $subject->toArray()))->send();
    }

    public function create(): void {
        try {
            $data = $this->request->getBody();
            if (!isset($data['id'], $data['name'])) {
                (new Responsejson(400, ['msg' => 'id and name are required']))->send();
                return;
            }
            $teacherId = null;
            if (!empty($data['teacher_id'])) {
                $teacherId = new TeacherId($data['teacher_id']);
            }
            $subject = new Subject(new SubjectId($data['id']), $data['name'], $teacherId);
            $this->sr->save($subject);
            (new Responsejson(201, $subject->toArray()))->send();
        } catch (\InvalidArgumentException $e) {
            (new Responsejson(400, ['msg' => $e->getMessage()]))->send();
        } catch (\Throwable $e) {
            (new Responsejson(500, ['msg' => 'Error creating subject']))->send();
        }
    }

    public function update(string $id): void {
        try {
            $subject = $this->sr->find(new SubjectId($id));
            if ($subject === null) {
                (new Responsejson(404, ['msg' => 'Subject not found']))->send();
                return;
            }
            $data = $this->request->getBody();
            if (isset($data['name'])) $subject->setName($data['name']);
            if (array_key_exists('teacher_id', $data)) {
                if (empty($data['teacher_id'])) {
                    $subject->unassignTeacher();
                } else {
                    $subject->assignTeacher(new TeacherId($data['teacher_id']));
                }
            }
            $this->sr->save($subject);
            (new Responsejson(200, $subject->toArray()))->send();
        } catch (\InvalidArgumentException $e) {
            (new Responsejson(400, ['msg' => $e->getMessage()]))->send();
        } catch (\Throwable $e) {
            (new Responsejson(500, ['msg' => 'Error updating subject']))->send();
        }
    }

    public function delete(string $id): void {
        try {
            $subject = $this->sr->find(new SubjectId($id));
            if ($subject === null) {
                (new Responsejson(404, ['msg' => 'Subject not found']))->send();
                return;
            }
            $this->sr->delete($subject);
            (new Responsejson(200, ['msg' => 'Subject deleted']))->send();
        } catch (\Throwable $e) {
            (new Responsejson(500, ['msg' => 'Error deleting subject']))->send();
        }
    }
}
