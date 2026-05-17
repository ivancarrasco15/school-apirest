<?php

namespace App\Config;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\TeachersController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\SubjectsController;

return [
    ['method' => 'GET', 'path' => '/auth/login',    'handler' => [AuthController::class, 'login']],
    ['method' => 'GET', 'path' => '/auth/callback', 'handler' => [AuthController::class, 'callback']],

    ['method' => 'GET',    'path' => '/api/teachers',     'handler' => [TeachersController::class, 'index'],  'middleware' => ['auth']],
    ['method' => 'GET',    'path' => '/api/teachers/{id}','handler' => [TeachersController::class, 'show'],   'middleware' => ['auth']],
    ['method' => 'POST',   'path' => '/api/teachers',     'handler' => [TeachersController::class, 'create'], 'middleware' => ['auth']],
    ['method' => 'PUT',    'path' => '/api/teachers/{id}','handler' => [TeachersController::class, 'update'], 'middleware' => ['auth']],
    ['method' => 'DELETE', 'path' => '/api/teachers/{id}','handler' => [TeachersController::class, 'delete'], 'middleware' => ['auth']],

    ['method' => 'GET',    'path' => '/api/students',     'handler' => [StudentsController::class, 'index'],  'middleware' => ['auth']],
    ['method' => 'GET',    'path' => '/api/students/{id}','handler' => [StudentsController::class, 'show'],   'middleware' => ['auth']],
    ['method' => 'POST',   'path' => '/api/students',     'handler' => [StudentsController::class, 'create'], 'middleware' => ['auth']],
    ['method' => 'PUT',    'path' => '/api/students/{id}','handler' => [StudentsController::class, 'update'], 'middleware' => ['auth']],
    ['method' => 'DELETE', 'path' => '/api/students/{id}','handler' => [StudentsController::class, 'delete'], 'middleware' => ['auth']],

    ['method' => 'GET',    'path' => '/api/subjects',     'handler' => [SubjectsController::class, 'index'],  'middleware' => ['auth']],
    ['method' => 'GET',    'path' => '/api/subjects/{id}','handler' => [SubjectsController::class, 'show'],   'middleware' => ['auth']],
    ['method' => 'POST',   'path' => '/api/subjects',     'handler' => [SubjectsController::class, 'create'], 'middleware' => ['auth']],
    ['method' => 'PUT',    'path' => '/api/subjects/{id}','handler' => [SubjectsController::class, 'update'], 'middleware' => ['auth']],
    ['method' => 'DELETE', 'path' => '/api/subjects/{id}','handler' => [SubjectsController::class, 'delete'], 'middleware' => ['auth']],

    ['method' => 'GET',    'path' => '/api/books',     'handler' => [BooksController::class, 'index'],  'middleware' => ['auth']],
    ['method' => 'GET',    'path' => '/api/books/{id}','handler' => [BooksController::class, 'show'],   'middleware' => ['auth']],
    ['method' => 'POST',   'path' => '/api/books',     'handler' => [BooksController::class, 'create'], 'middleware' => ['auth']],
    ['method' => 'PUT',    'path' => '/api/books/{id}','handler' => [BooksController::class, 'update'], 'middleware' => ['auth']],
    ['method' => 'DELETE', 'path' => '/api/books/{id}','handler' => [BooksController::class, 'delete'], 'middleware' => ['auth']],
];