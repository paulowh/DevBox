<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Turma extends Model
{
    protected $table = 'turmas';

    protected $fillable = [
        'turma'
    ];

    public $timestamps = false;

    public function cards()
    {
        return $this->hasMany(Card::class, 'turma_id');
    }
}
