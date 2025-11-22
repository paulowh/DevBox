<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Habilidade extends Model
{
    protected $table = 'habilidades';

    protected $fillable = [
        'numero_hab',
        'uc_id',
        'descricao'
    ];

    public $timestamps = false;

    public function uc()
    {
        return $this->belongsTo(Uc::class, 'uc_id');
    }

    public function cards()
    {
        return $this->belongsToMany(Card::class, 'card_habilidades', 'habilidade_id', 'card_id');
    }
}
