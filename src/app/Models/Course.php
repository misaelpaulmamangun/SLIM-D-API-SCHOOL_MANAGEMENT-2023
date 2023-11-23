<?php

namespace App\Models;

// Course.php
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
  protected $table = 'course';

  public function students()
  {
    return $this->hasMany(Student::class);
  }
}
