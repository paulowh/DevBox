<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Card;
use App\Models\Turma;

class HomeController
{
  public function index()
  {
    // Consulta todas as turmas com seus cards e relacionamentos
    $turmas = Turma::with([
      'cards.uc',
      'cards.indicadores',
      'cards.conhecimentos',
      'cards.habilidades',
      'cards.atitudes'
    ])->get();

    // Ou se preferir consultar todos os cards diretamente
    $cards = Card::with([
      'turma',
      'uc',
      'indicadores',
      'conhecimentos',
      'habilidades',
      'atitudes'
    ])->get();

    return View::make('planejamento/board', [
      'title' => 'Planejamento',
      'turmas' => $turmas,
      'cards' => $cards
    ]);
  }
}
