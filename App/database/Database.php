<?php
namespace App\Database;

use Illuminate\Database\Capsule\Manager as Capsule;

class Database {
    protected  Capsule $capsule;

    public function __construct()
    {
        $this->capsule = new Capsule;

        $this->capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => '127.0.0.1',
            'database'  => 'portal',
            'port'      => 3306,
            'username'  => 'root',
            'password'  => '',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ], 'default');
        $this->capsule->bootEloquent();
        $this->capsule->setAsGlobal();
    }

    public function getDatabaseConnection(): Capsule
    {
        return $this->capsule;
    }


}