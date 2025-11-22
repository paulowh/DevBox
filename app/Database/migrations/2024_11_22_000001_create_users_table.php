<?php

namespace App\Database\Migrations;

use App\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->createTable('users', function ($table) {
            $table->id();
            $table->string('name')->notNullable();
            $table->string('email')->unique()->notNullable();
            $table->string('password')->notNullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        $this->dropTable('users');
    }
}
