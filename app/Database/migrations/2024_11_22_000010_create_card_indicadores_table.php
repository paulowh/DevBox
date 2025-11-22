<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class CreateCardIndicadoresTable
{
    public function up()
    {
        Capsule::schema()->create('card_indicadores', function ($table) {
            $table->unsignedInteger('card_id');
            $table->unsignedInteger('indicador_id');

            $table->foreign('card_id')->references('id')->on('cards');
            $table->foreign('indicador_id')->references('id')->on('indicadores');
            $table->primary(['card_id', 'indicador_id']);
        });
    }

    public function down()
    {
        Capsule::schema()->dropIfExists('card_indicadores');
    }
}
