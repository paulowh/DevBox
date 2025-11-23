<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conhecimento extends Model
{
  protected $table = 'conhecimentos';

  protected $fillable = [
    'numero_con',
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
    return $this->belongsToMany(Card::class, 'card_conhecimentos', 'conhecimento_id', 'card_id');
  }
}
