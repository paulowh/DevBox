<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class CreateCardsTable
{
    public function up()
    {
        Capsule::schema()->create('cards', function ($table) {
            $table->increments('id');
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->unsignedInteger('turma_id');
            $table->unsignedInteger('uc_id');
            $table->timestamp('aula_inicial')->nullable();
            $table->timestamp('aula_final')->nullable();

            $table->foreign('turma_id')->references('id')->on('turmas');
            $table->foreign('uc_id')->references('id')->on('ucs');
            $table->index('turma_id');
            $table->index('uc_id');
        });
    }

    public function down()
    {
        Capsule::schema()->dropIfExists('cards');
    }
}
