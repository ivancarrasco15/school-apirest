<?php 

namespace App\Config;

use App\Http\Controllers\BooksController;
use App\Http\Controllers\TeachersController;
use App\Http\Controllers\StudentsController;

return [
    [
        'method' => 'GET',
        'path' => '/api/books',
        'handler' => [BooksController::class, 'index']
    ],
    [
        'method' => 'GET',
        'path' => '/api/books/{id}',
        'handler' => [BooksController::class, 'show']
    ],
    [
        'method' => 'POST',
        'path' => '/api/books',
        'handler' => [BooksController::class, 'create']
    ],
    [
        'method' => 'PUT',
        'path' => '/api/books/{id}',
        'handler' => [BooksController::class, 'update']
    ],
    [
        'method' => 'DELETE',
        'path' => '/api/books/{id}',
        'handler' => [BooksController::class, 'delete']
    ],

    [
        'method' => 'GET',
        'path' => '/api/teachers',
        'handler' => [TeachersController::class, 'index']
    ],
    [
        'method' => 'GET',
        'path' => '/api/teachers/{id}',
        'handler' => [TeachersController::class, 'show']
    ],
    [
        'method' => 'POST',
        'path' => '/api/teachers',
        'handler' => [TeachersController::class, 'create']
    ],
    [
        'method' => 'PUT',
        'path' => '/api/teachers/{id}',
        'handler' => [TeachersController::class, 'update']
    ],
    [
        'method' => 'DELETE',
        'path' => '/api/teachers/{id}',
        'handler' => [TeachersController::class, 'delete']
    ]
    ,
    [
        'method' => 'GET',
        'path' => '/api/students',
        'handler' => [StudentsController::class, 'index']
    ],
    [
        'method' => 'GET',
        'path' => '/api/students/{id}',
        'handler' => [StudentsController::class, 'show']
    ],
    [
        'method' => 'POST',
        'path' => '/api/students',
        'handler' => [StudentsController::class, 'create']
    ],
    [
        'method' => 'PUT',
        'path' => '/api/students/{id}',
        'handler' => [StudentsController::class, 'update']
    ],
    [
        'method' => 'DELETE',
        'path' => '/api/students/{id}',
        'handler' => [StudentsController::class, 'delete']
    ]
];