<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Curso;

class UcController
{
  public function index()
  {
    // formatar nesse modelo
    $ucs = [
      [
        'id' => 1,
        'nome' => 'UC1',
        'dados' => 'Dados da UC1...',
        'indicadores' => ['UC1 - Indicador 1', 'UC1 - Indicador 2'],
        'conhecimentos' => ['UC1 - Conhecimento 1', 'UC1 - Conhecimento 2'],
        'habilidades' => ['UC1 - Habilidade 1', 'UC1 - Habilidade 2'],
        'atitudes' => ['UC1 - Atitude 1', 'UC1 - Atitude 2'],
      ],
      [
        'id' => 2,
        'nome' => 'UC2',
        'dados' => 'Dados da UC2...',
        'indicadores' => ['UC2 - Indicador 1', 'UC2 - Indicador 2'],
        'conhecimentos' => ['UC2 - Conhecimento 1', 'UC2 - Conhecimento 2'],
        'habilidades' => ['UC2 - Habilidade 1', 'UC2 - Habilidade 2'],
        'atitudes' => ['UC2 - Atitude 1', 'UC2 - Atitude 2'],
      ],
    ];

    echo View::make('uc/listar', ['ucs' => $ucs]);
  }

  public function create($id = null)
  {
    $cursos = Curso::all();
    echo View::make('uc/registro', ['cursos' => $cursos]);

  }
}
