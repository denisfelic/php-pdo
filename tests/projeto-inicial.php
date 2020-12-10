<?php

use Alura\Pdo\Domain\Model\Student;

require_once __DIR__ . '/../vendor/autoload.php';

$student = new Student(
    null,
    'Denis',
    new DateTimeImmutable('1997-04-21')
);

echo $student->age();
