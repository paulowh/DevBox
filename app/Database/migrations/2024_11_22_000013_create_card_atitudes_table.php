<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class CreateCardAtitudesTable
{
    public function up()
    {
        Capsule::schema()->create('card_atitudes', function ($table) {
            $table->unsignedInteger('card_id');
            $table->unsignedInteger('atitude_id');

            $table->foreign('card_id')->references('id')->on('cards');
            $table->foreign('atitude_id')->references('id')->on('atitudes');
            $table->primary(['card_id', 'atitude_id']);
        });
    }

    public function down()
    {
        Capsule::schema()->dropIfExists('card_atitudes');
    }
}
