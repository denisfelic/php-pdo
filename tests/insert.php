<?php

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infrastructure\Persistence\CreateConnection;

require_once __DIR__ . '/../vendor/autoload.php';

$pdoConnection = CreateConnection::sqlite();

$student = new Student(null, "Test Name 2", new DateTimeImmutable("2020-07-02"));

//$sqlInsertQuery = "INSERT INTO student (name, birth_date) VALUES ( ?, ?);";
$sqlInsertQuery = "INSERT INTO student (name, birth_date) VALUES ( :name, :birth_date);";
$sqlStatement = $pdoConnection->prepare($sqlInsertQuery);
//$sqlStatement->bindValue(1, $student->name());
$sqlStatement->bindValue(':name', $student->name());
//$sqlStatement->bindValue(2, $student->birthDate()->format('Y-m-d'));
$sqlStatement->bindValue(':birth_date', $student->birthDate()->format('Y-m-d'));

echo  $sqlStatement->execute();