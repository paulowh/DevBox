<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class CreateConhecimentosTable
{
    public function up()
    {
        Capsule::schema()->create('conhecimentos', function ($table) {
            $table->increments('id');
            $table->integer('numero_con')->nullable();
            $table->unsignedInteger('uc_id');
            $table->text('descricao')->nullable();

            $table->foreign('uc_id')->references('id')->on('ucs');
            $table->index('uc_id');
        });
    }

    public function down()
    {
        Capsule::schema()->dropIfExists('conhecimentos');
    }
}
