<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class CreateCardConhecimentosTable
{
    public function up()
    {
        Capsule::schema()->create('card_conhecimentos', function ($table) {
            $table->unsignedInteger('card_id');
            $table->unsignedInteger('conhecimento_id');

            $table->foreign('card_id')->references('id')->on('cards');
            $table->foreign('conhecimento_id')->references('id')->on('conhecimentos');
            $table->primary(['card_id', 'conhecimento_id']);
        });
    }

    public function down()
    {
        Capsule::schema()->dropIfExists('card_conhecimentos');
    }
}
