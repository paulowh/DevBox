<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
  protected $table = 'cursos';

  protected $fillable = [
    'nome_curso',
    'data_criacao'
  ];

  protected $casts = [
    'data_criacao' => 'datetime'
  ];

  public $timestamps = false;

  public function ucs()
  {
    return $this->hasMany(Uc::class, 'curso_id');
  }
}
