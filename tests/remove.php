<?php


use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infrastructure\Persistence\CreateConnection;

require_once __DIR__ . '/../vendor/autoload.php';

$pdoConnection = CreateConnection::sqlite();


$preparedStatement = $pdoConnection->prepare("DELETE FROM student WHERE id=:id");
$preparedStatement->bindValue(":id", 0, PDO::PARAM_INT);
var_dump($preparedStatement->execute());