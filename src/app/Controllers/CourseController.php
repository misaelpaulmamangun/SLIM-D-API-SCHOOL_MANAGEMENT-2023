<?php

namespace App\Controllers;

use App\Enums\HttpStatus;
use App\Interfaces\CourseInterface;
use App\Models\Course;
use Illuminate\Database\Capsule\Manager as DB;
use PDOException;
use Slim\Http\Request;
use Slim\Http\Response;

class CourseController extends Controller implements CourseInterface
{
	public function findAll(Request $request, Response $response): Response
	{
		try {
			$course = Course::leftJoin('students', 'course.id', '=', 'students.course_id')
				->select('course.id', 'course.name', DB::raw('COUNT(students.id) as students'))
				->groupBy('course.id', 'course.name')
				->get();

			return $response->withJSON($course)->withStatus(HttpStatus::OK->value);
		} catch (PDOException $e) {
			$data = [
				'message' => $e->getMessage(),
				'success' => false,
			];

			return $response->withJSON($data)->withStatus(HttpStatus::SERVER->value);
		}
	}

	public function findById(Request $request, Response $response, $args): Response
	{
		try {
			$courseId = $args['id'];
			$course = Course::select('course.id', 'course.name', 'course.code')
				->leftJoin('students', 'course.id', '=', 'students.course_id')
				->where('course.id', '=', DB::raw($courseId)) // Replace $id with the actual ID you want to filter by
				->selectRaw('COUNT(students.id) AS students')
				->groupBy('course.id', 'course.name')
				->first();

			$data = [
				'data' => $course,
				'success' => true,
			];

			return $response->withJSON($data)->withStatus(HttpStatus::OK->value);
		} catch (PDOException $e) {
			$data = [
				'message' => $e->getMessage(),
				'success' => false,
			];

			return $response->withJSON($data)->withStatus(HttpStatus::SERVER->value);
		}
	}

	public function save(Request $request, Response $response): Response
	{
		try {
			$sql = "
  		  INSERT INTO 
          course 
            (
		          id,
  		        name
  		      ) 
  		  VALUES 
          (
    		    :id,
    		    :name
    		  )
  	  ";

			$stmt = $this->c->db->prepare($sql);

			$stmt->execute([
				':id' => $request->getParam('id'),
				':name' => $request->getParam('name')
			]);

			$data = [
				'data' => true,
				'success' => true,
				'message' => 'Created successfully'
			];

			return $response->withJSON($data)->withStatus(HttpStatus::OK->value);
		} catch (PDOException $e) {
			$data = [
				'message' => $e->getMessage(),
				'success' => false,
			];

			return $response->withJSON($data)->withStatus(HttpStatus::SERVER->value);
		}
	}

	public function delete(Request $request, Response $response): Response
	{
		try {
			$sql = "
    		DELETE FROM
    		  course
    		WHERE
    		  id = :id
  	  ";

			$stmt = $this->c->db->prepare($sql);

			$stmt->execute([
				':id' => $request->getParam('id')
			]);

			$data = [
				'data' => true,
				'success' => true,
				'message' => 'Deleted successfully'
			];

			return $response->withJSON($data)->withStatus(HttpStatus::OK->value);
		} catch (PDOException $e) {
			$data = [
				'message' => $e->getMessage(),
				'success' => false,
			];

			return $response->withJSON($data)->withStatus(HttpStatus::SERVER->value);
		}
	}

	public function update(Request $request, Response $response): Response
	{
		try {
			$sql = "
    		UPDATE
    		  course
    		SET
    		  name = :name
    		WHERE
    		  id = :id
  	  ";

			$stmt = $this->c->db->prepare($sql);

			$stmt->execute([
				':id' => $request->getParam('id'),
				':name' => $request->getParam('name')
			]);

			$data = [
				'data' => true,
				'success' => true,
				'message' => 'Updated successfully'
			];

			return $response->withJSON($data)->withStatus(HttpStatus::OK->value);
		} catch (PDOException $e) {
			$data = [
				'message' => $e->getMessage(),
				'success' => false,
			];

			return $response->withJSON($data)->withStatus(HttpStatus::SERVER->value);
		}
	}
}
