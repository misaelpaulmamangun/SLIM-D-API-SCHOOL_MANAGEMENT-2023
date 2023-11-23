<?php

use App\Controllers\StudentController;
use App\Controllers\CourseController;
use App\Enums\Route;

$app->group('/api', function () use ($app) {
  
  // Students Route
  $app->get('/' . Route::STUDENTS->value, StudentController::class . ':findAll');
  $app->get('/' . Route::STUDENTS->value . '/{id}', StudentController::class . ':findById');
  $app->post('/' . Route::STUDENTS->value, StudentController::class . ':save');
  $app->delete('/' . Route::STUDENTS->value, StudentController::class . ':delete');
  $app->put('/' . Route::STUDENTS->value, StudentController::class . ':update');

  // Course Route
  $app->get('/' . Route::COURSE->value, CourseController::class . ':findAll');
  $app->get('/' . Route::COURSE->value . '/{id}', CourseController::class . ':findById');
  $app->post('/' . Route::COURSE->value, CourseController::class . ':save');
  $app->delete('/' . Route::COURSE->value, CourseController::class . ':delete');
  $app->put('/' . Route::COURSE->value, CourseController::class . ':update');
});
