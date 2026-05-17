<?php

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Http\Request;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

require __DIR__ . '/bootstrap.php';
$request = new Request();

$app->dispatch($request, $entityManager);