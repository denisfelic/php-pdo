<?php

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Domain\Repository\StudentRepository;
use Alura\Pdo\Infrastructure\Persistence\CreateConnection;
use Alura\Pdo\Infrastructure\Repository\PdoStudentRepository;

require_once __DIR__ . '/../vendor/autoload.php';

$sqliteConnection = CreateConnection::sqlite();
$mysqlConnection = CreateConnection::mysql();

$userRepositoryFromSQLITE = new PdoStudentRepository($sqliteConnection);
$userRepositoryFromMYSQL = new PdoStudentRepository($mysqlConnection);
seed($userRepositoryFromMYSQL, 5);
var_dump([
    'SQLITE' => $userRepositoryFromSQLITE->allStudents(),
    'MYSQL' => $userRepositoryFromMYSQL->allStudents(),
]);

function seed(StudentRepository $repository, int $qtd = 5)
{
    for ($i = 0; $i < $qtd; $i++) {
        $res = $repository->save(new Student(
            null,
            'Denoca da Silva',
            new DateTimeImmutable('1997-04-21')));

        echo "Insert new pdo object {$i} === ${res}" . PHP_EOL;
    }
}

