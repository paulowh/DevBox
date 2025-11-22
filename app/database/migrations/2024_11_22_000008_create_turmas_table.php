<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class CreateTurmasTable
{
    public function up()
    {
        Capsule::schema()->create('turmas', function ($table) {
            $table->increments('id');
            $table->string('nome');
        });
    }

    public function down()
    {
        Capsule::schema()->dropIfExists('turmas');
    }
}
