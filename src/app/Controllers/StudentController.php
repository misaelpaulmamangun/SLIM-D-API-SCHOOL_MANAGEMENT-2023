<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Enums\HttpStatus;
use App\Interfaces\StudentInterface;
use App\Models\Student;
use PDOException;

class StudentController extends Controller implements StudentInterface
{
  public function findAll($request, $response): Response
  {
    try {
      $students = Student::select(
        'students.first_name',
        'students.last_name',
        'students.age',
        'year.title as year',
        'course.name as course'
      )
        ->leftJoin('course', 'course.id', '=', 'students.course_id')
        ->leftJoin('year', 'students.year_id', '=', 'year.id')
        ->get();

      $data = [
        'students' => $students,
        'success' => true,
      ];

      return $this->c->ResponseHelper->createSuccessResponse($data);
    } catch (PDOException $e) {
      $data = [
        'success' => false,
        'message' => $e->getMessage()
      ];

      return $response->withJSON($data)->withStatus(HttpStatus::SERVER->value);
    }
  }

  public function findById(Request $request, Response $response, $args): Response
  {
    try {
      $student = Student::select(
        'student.first_name',
        'student.last_name',
        'student.age',
        'year.title as year',
        'course.name as course'
      )
        ->leftJoin('course', 'course.id', '=', 'student.course_id')
        ->leftJoin('year', 'student.year_id', '=', 'year.id')
        ->where('student.id', $args['id'])
        ->first();

      if (!$student) {
        // Handle the case where the student is not found
        return $this->c->ResponseHelper->createErrorResponse($response, 'Student not found', HttpStatus::NOT_FOUND);
      }

      $data = [
        'data' => $student,
        'success' => true,
      ];

      return $response->withJSON($data)->withStatus(HttpStatus::OK->value);
    } catch (PDOException $e) {
      $data = [
        'success' => false,
        'message' => $e->getMessage()
      ];

      return $response->withJSON($data)->withStatus(HttpStatus::SERVER->value);
    }
  }

  public function save(Request $request, Response $response): Response
  {
    try {
      $sql = "
        INSERT INTO student (
          first_name,
          last_name,
          age,
          year_id,
          course_id,
          created_at
        ) VALUES (
          :first_name,
          :last_name,
          :age,
          :year_id,
          :course_id,
          :created_at
        )
      ";

      $stmt = $this->c->db->prepare($sql);

      $stmt->execute([
        ':first_name' => $request->getParam('first_name'),
        ':last_name' => $request->getParam('last_name'),
        ':age' => $request->getParam('age'),
        ':year_id' => $request->getParam('year_id'),
        ':course_id' => $request->getParam('course_id'),
        ':created_at' => date('Y-m-d H:i:s'),
      ]);

      $data = [
        'message' => "student created",
        'success' => true
      ];

      return $response->withJSON($data)->withStatus(HttpStatus::OK->value);
    } catch (PDOException $e) {
      $data = [
        'message' => $e->getMessage(),
        'success' => false
      ];

      return $response->withJSON($data)->withStatus(HttpStatus::SERVER->value);
    }
  }

  public function delete(Request $request, Response $response): Response
  {
    try {
      $sql = "
        DELETE FROM
          student
        WHERE
          id = :id
      ";

      $stmt = $this->c->db->prepare($sql);

      $stmt->execute([
        ':id' => $request->getParam('id')
      ]);

      $data = [
        'success' => true
      ];

      return $response->withJSON($data)->withStatus(HttpStatus::OK->value);
    } catch (PDOException $e) {
      $data = [
        'message' => $e->getMessage(),
        'success' => false
      ];

      return $response->withJSON($data)->withStatus(HttpStatus::SERVER->value);
    }
  }

  public function update(Request $request, Response $response): Response
  {
    try {
      $sql = "
        UPDATE 
          student
        SET (
          first_name = :first_name,
          last_name = :last_name,
          age = :age,
          year_id = :year_id,
          course_id = :course_id
        )
        WHERE
          id = :id
      ";

      $stmt = $this->c->db->prepare($sql);

      $stmt->execute([
        ':id' => $request->getParam('id'),
        ':first_name' => $request->getParam('first_name'),
        ':last_name' => $request->getParam('last_name'),
        ':age' => $request->getParam('age'),
        ':year_id' => $request->getParam('year_id'),
        ':course_id' => $request->getParam('course_id')
      ]);

      $data = [
        'success' => true
      ];

      return $response->withJSON($data)->withStatus(HttpStatus::OK->value);
    } catch (PDOException $e) {
      $data = [
        'message' => $e->getMessage(),
        'success' => false
      ];

      return $response->withJSON($data)->withStatus(HttpStatus::SERVER->value);
    }
  }
}
