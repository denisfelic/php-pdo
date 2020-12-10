<?php


namespace Alura\Pdo\Domain\Repository;


use Alura\Pdo\Domain\Model\Student;
use DateTimeImmutable;

interface StudentRepository
{
    public function allStudents(): array;

    public function studentBirthAt(DateTimeImmutable $date): array;

    public function save(Student $student): bool;

    public function remove(Student $student): bool;

    public function update(Student $student): bool;

    public function insertBunch(array $students) : bool;

    public function studentsWithPhone();
}