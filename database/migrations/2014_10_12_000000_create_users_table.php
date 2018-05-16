<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    // Both Admin and User tables
    protected $tables = ['admins', 'users'];

    /**
     * Add the ability to run certain functions
     * @param Closure $action
     */
    protected function eachTable(Closure $action)
    {
        foreach ($this->tables as $table) {
            $action($table);
        }
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->eachTable(function ($table) {
            Schema::create($table, function (Blueprint $blueprint) {
                $blueprint->increments('id');
                $blueprint->string('name');
                $blueprint->string('email')->unique();
                $blueprint->string('password', 60);
                $blueprint->rememberToken();
                $blueprint->timestamps();
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->eachTable(function ($table) {
            Schema::dropIfExists($table);
        });
    }
}
