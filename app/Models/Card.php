<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $table = 'cards';

    protected $fillable = [
        'titulo',
        'descricao',
        'turma_id',
        'uc_id',
        'curso_id',
        'aula_inicial',
        'aula_final',
        'ordem'
    ];

    protected $casts = [
        'aula_inicial' => 'datetime',
        'aula_final' => 'datetime'
    ];

    public $timestamps = false;

    public function turma()
    {
        return $this->belongsTo(Turma::class, 'turma_id');
    }

    public function uc()
    {
        return $this->belongsTo(Uc::class, 'uc_id');
    }
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    public function indicadores()
    {
        return $this->belongsToMany(Indicador::class, 'card_indicadores', 'card_id', 'indicador_id');
    }

    public function conhecimentos()
    {
        return $this->belongsToMany(Conhecimento::class, 'card_conhecimentos', 'card_id', 'conhecimento_id');
    }

    public function habilidades()
    {
        return $this->belongsToMany(Habilidade::class, 'card_habilidades', 'card_id', 'habilidade_id');
    }

    public function atitudes()
    {
        return $this->belongsToMany(Atitude::class, 'card_atitudes', 'card_id', 'atitude_id');
    }
}
