<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class CreateUcsTable
{
    public function up()
    {
        Capsule::schema()->create('ucs', function ($table) {
            $table->increments('id');
            $table->unsignedInteger('curso_id');
            $table->integer('numero_uc')->nullable();
            $table->string('sigla')->nullable();
            $table->string('nome_completo')->nullable();
            $table->timestamp('data_criacao')->useCurrent();

            $table->foreign('curso_id')->references('id')->on('cursos');
        });
    }

    public function down()
    {
        Capsule::schema()->dropIfExists('ucs');
    }
}
