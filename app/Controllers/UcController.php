<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Atitude;
use App\Models\Conhecimento;
use App\Models\Curso;
use App\Models\Habilidade;
use App\Models\Indicador;
use App\Models\Uc;

class UcController
{
  public function index()
  {
    $ucs = Uc::all();

    foreach ($ucs as $uc) {
      $uc->indicadores = Indicador::where('uc_id', $uc->id)->get();
      $uc->conhecimentos = Conhecimento::where('uc_id', $uc->id)->get();
      $uc->habilidades = Habilidade::where('uc_id', $uc->id)->get();
      $uc->atitudes = Atitude::where('uc_id', $uc->id)->get();
    }
    echo View::make('uc/listar', ['ucs' => $ucs]);
  }

  public function create($id = null)
  {
    $cursos = Curso::all();
    echo View::make('uc/registro', ['cursos' => $cursos]);

  }
}
