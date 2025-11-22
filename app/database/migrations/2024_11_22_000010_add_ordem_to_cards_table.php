<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class AddOrdemToCardsTable
{
    public function up()
    {
        Capsule::schema()->table('cards', function ($table) {
            $table->integer('ordem')->default(0)->after('turma_id');
            $table->index('ordem');
        });
    }

    public function down()
    {
        Capsule::schema()->table('cards', function ($table) {
            $table->dropColumn('ordem');
        });
    }
}
