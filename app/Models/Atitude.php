<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Atitude extends Model
{
    protected $table = 'atitudes';

    protected $fillable = [
        'numero_ati',
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
        return $this->belongsToMany(Card::class, 'card_atitudes', 'atitude_id', 'card_id');
    }
}
