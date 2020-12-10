<?php


use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infrastructure\Persistence\CreateConnection;
use Alura\Pdo\Infrastructure\Repository\PdoStudentRepository;

require_once __DIR__ . '/../vendor/autoload.php';

$sqliteConnection = CreateConnection::sqlite();
$mysqlConnection = CreateConnection::mysql();

$userRepositoryFromMYSQL = new PdoStudentRepository($mysqlConnection);

$studentList = [
    new Student(null, "Aluno 7", new DateTimeImmutable('2000-01-12')),
    new Student(null, "Aluno 8", new DateTimeImmutable('2000-01-12')),
    new Student(null, "Aluno 9", new DateTimeImmutable('2000-01-12')),
];

 $userRepositoryFromMYSQL->insertBunch($studentList);
// var_dump([
//    'MYSQL' => $userRepositoryFromMYSQL->allStudents(),
//]);