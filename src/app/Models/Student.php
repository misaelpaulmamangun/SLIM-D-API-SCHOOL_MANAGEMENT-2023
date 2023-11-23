<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
  protected $table = 'students';

  public function course()
  {
    return $this->belongsTo('Course', 'course_id');
  }

  public function year()
  {
    return $this->belongsTo('Year', 'year_id');
  }
}
