<?php


use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infrastructure\Persistence\CreateConnection;

require_once __DIR__ . '/../vendor/autoload.php';

$pdoConnection = CreateConnection::sqlite();

$res = $pdoConnection->query("SELECT * FROM student;");

// Pega todos os dados de uma vez (perigo de sobrecarregar a memória)
//$studentList = $res->fetchAll(PDO::FETCH_ASSOC);
//$studentList = $res->fetchAll(PDO::FETCH_NUM);
// Busca apenas o índice da coluna solicitado
//var_dump($res->fetchColumn(1));

// Pega um dado de cada vez
while ($studentList = $res->fetch(PDO::FETCH_ASSOC)) {
    try {
        $student = new Student(null,
            $studentList["name"],
            new DateTimeImmutable($studentList["birth_date"]));

        var_dump($student);

    } catch (Exception $e) {
        echo $e->getMessage();
    }
}


