<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class CreateCardHabilidadesTable
{
    public function up()
    {
        Capsule::schema()->create('card_habilidades', function ($table) {
            $table->unsignedInteger('card_id');
            $table->unsignedInteger('habilidade_id');

            $table->foreign('card_id')->references('id')->on('cards');
            $table->foreign('habilidade_id')->references('id')->on('habilidades');
            $table->primary(['card_id', 'habilidade_id']);
        });
    }

    public function down()
    {
        Capsule::schema()->dropIfExists('card_habilidades');
    }
}
