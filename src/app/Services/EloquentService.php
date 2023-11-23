<?php

namespace App\Services;

use Illuminate\Database\Capsule\Manager as Capsule;

class EloquentService
{
    protected $capsule;

    public function __construct(array $settings)
    {
        $this->capsule = new Capsule;
        $this->capsule->addConnection($settings);
        $this->capsule->setAsGlobal();
        $this->capsule->bootEloquent();
    }
}
