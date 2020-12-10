<?php

use Alura\Pdo\Infrastructure\Persistence\CreateConnection;
use Alura\Pdo\Infrastructure\Repository\PdoStudentRepository;

require_once __DIR__ . '/../vendor/autoload.php';

$pdoConnection = CreateConnection::sqlite();

$query = '
    CREATE TABLE IF NOT EXISTS students (
        id INTEGER PRIMARY KEY,
        name TEXT,
        birth_date TEXT
    );

    CREATE TABLE IF NOT EXISTS phones (
        id INTEGER PRIMARY KEY  PRIMARY KEY,
        area_code TEXT,
        number TEXT,
        student_id INTEGER,
        FOREIGN KEY (student_id) REFERENCES students(id)
    );
';
$pdoConnection->exec($query);


//$pdoConnection->exec("INSERT INTO phones (area_code, number, student_id)  VALUES ('1', '222222222', 1);");

$repository = new PdoStudentRepository($pdoConnection);
$students = $repository->allStudents();
var_dump($students);