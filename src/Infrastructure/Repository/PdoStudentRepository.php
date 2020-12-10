<?php

namespace Alura\Pdo\Infrastructure\Repository;

use Alura\Pdo\Domain\Model\Phone;
use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Domain\Repository\StudentRepository;
use DateTimeImmutable;
use Exception;
use PDO;
use PDOException;
use PDOStatement;
use RuntimeException;

class PdoStudentRepository implements StudentRepository
{

    private PDO $connection;

    public function __construct(PDO $pdo)
    {
        $this->connection = $pdo;
    }

    public function allStudents(): array
    {
        try {
            $query = "SELECT * FROM student;";
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            return $this->hydrateStudentList($stmt);
        } catch (Exception $e) {
            echo $e->getMessage();
            return [];
        }
    }

    public function studentBirthAt(DateTimeImmutable $date): array
    {
        try {
            $query = "SELECT * FROM student WHERE id = ?;";
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            return $this->hydrateStudentList($stmt);
        } catch (Exception $e) {
            echo $e->getMessage();
            return [];
        }
    }

    public function save(Student $student): bool
    {
        try {
            $sqlInsertQuery = "INSERT INTO student (name, birth_date) VALUES ( :name, :birth_date);";
            $stmt = $this->connection->prepare($sqlInsertQuery);

            return $stmt->execute([
                ':name' => $student->name(),
                ':birth_date' => $student->birthDate()->format('Y-m-d')
            ]);

        } catch (PDOException | Exception $ex) {
            echo $ex->getMessage();
            return false;
        }
    }

    public function remove(Student $student): bool
    {
        try {
            $stmt = $this->connection->prepare("DELETE FROM student WHERE id=:id");
            $stmt->bindValue(":id", $student->id(), PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $ex) {
            echo $ex->getMessage();
            return false;
        }
    }

    public function update(Student $student): bool
    {
        try {
            $query = "UPDATE students SET name = :name, birth_date = :birth_date WHERE id = :id";
            $stmt = $this->connection->prepare($query);
            $stmt->bindValue(':name', $student->name());
            $stmt->bindValue(':birth_date', $student->birthDate());
            return $stmt->execute();
        } catch (PDOException | Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * @param $studentData
     * @param array $studentsList
     * @return array
     * @throws Exception
     */
    private function addStudentToArray($studentData, array $studentsList): array
    {
        $student = new Student(
            null,
            $studentData["name"],
            new DateTimeImmutable($studentData["birth_date"])
        );
        array_push($studentsList, $student);
        return $studentsList;
    }

    /**
     * @param PDOStatement $stmt
     * @return array
     * @throws Exception
     */
    private function hydrateStudentList(PDOStatement $stmt): array
    {
        $studentsList = [];
        while ($studentData = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $studentsList = $this->addStudentToArray($studentData, $studentsList);
        }
        return $studentsList;
    }

    private function fillPhonesOfStudents(Student $student): void
    {
        // TODO: CORRIGIR OS ID DAS TABELAS STUDENT E PHONE QUE NÃO ESTÃO SENDO GERADOS CORRETAMENTE, E IMPLEMENTAR ESTE MÉTODO

        $query = 'SELECT id, area_code, number FROM phones WHERE student_id = ?';
        $stmt = $this->connection->prepare($query);
        $stmt->bindValue(1, $student->id(), PDO::PARAM_INT);
        $stmt->execute();

        $phoneDataList = $stmt->fetchAll();
        foreach ($phoneDataList as $phoneData) {
            $phone = new Phone(
                $phoneData['id'],
                $phoneData['area_code'],
                $phoneData['number']
            );
            $student->addPhone($phone);
        }
    }

    public function insertBunch(array $students): bool
    {
        $this->connection->beginTransaction();
        try {
            foreach ($students as $student) {
                if ($this->save($student) === false) {
                    throw new RuntimeException('Erro ao inserir vários students');
                }
            }
            $this->connection->commit();
            return true;
        } catch (Exception | RuntimeException $e) {
            echo $e->getMessage();
            $this->connection->rollBack();
            return false;
        }
    }

    public function studentsWithPhone(): array
    {
        // TODO: Corrigir geração de ID's automáticos no SGDB para que este método funcionar
        $sqlQuery = 'SELECT student.id,
                            student.name,
                            student.birth_date,
                            phone.id AS phone_id,
                            phone.area_code,
                            phone.number
                       FROM student
                       JOIN phone ON student.id = phone.student_id;';
        $stmt = $this->connection->query($sqlQuery);
        $result = $stmt->fetchAll();
        $studentList = [];

        foreach ($result as $row) {
            if (!array_key_exists($row['id'], $studentList)) {
                $studentList[$row['id']] = new Student(
                    $row['id'],
                    $row['name'],
                    new \DateTimeImmutable($row['birth_date'])
                );
            }
            $phone = new Phone($row['phone_id'], $row['area_code'], $row['number']);
            $studentList[$row['id']]->addPhone($phone);
        }

        return $studentList;
    }
}
