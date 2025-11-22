<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class CreateCursosTable
{
    public function up()
    {
        Capsule::schema()->create('cursos', function ($table) {
            $table->increments('id');
            $table->string('nome_curso');
            $table->timestamp('data_criacao')->useCurrent();
        });
    }

    public function down()
    {
        Capsule::schema()->dropIfExists('cursos');
    }
}
