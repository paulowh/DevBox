<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Uc extends Model
{
    protected $table = 'ucs';

    protected $fillable = [
        'curso_id',
        'numero_uc',
        'sigla',
        'nome_completo',
        'data_criacao'
    ];

    protected $casts = [
        'data_criacao' => 'datetime'
    ];

    public $timestamps = false;

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    public function indicadores()
    {
        return $this->hasMany(Indicador::class, 'uc_id');
    }

    public function conhecimentos()
    {
        return $this->hasMany(Conhecimento::class, 'uc_id');
    }

    public function habilidades()
    {
        return $this->hasMany(Habilidade::class, 'uc_id');
    }

    public function atitudes()
    {
        return $this->hasMany(Atitude::class, 'uc_id');
    }

    public function cards()
    {
        return $this->hasMany(Card::class, 'uc_id');
    }
}
