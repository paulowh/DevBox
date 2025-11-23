<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Indicador extends Model
{
  protected $table = 'indicadores';

  protected $fillable = [
    'numero_ind',
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
    return $this->belongsToMany(Card::class, 'card_indicadores', 'indicador_id', 'card_id');
  }
}
