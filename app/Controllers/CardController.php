<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Atitude;
use App\Models\Card;
use App\Models\Conhecimento;
use App\Models\Curso;
use App\Models\Habilidade;
use App\Models\Indicador;
use App\Models\Turma;
use App\Models\Uc;
use Exception;

class CardController
{
  public function create()
  {
    header('Content-Type: application/json');
    try {
      $cursos = Curso::all();
      $turmas = Turma::all();
      $ucs = Uc::all();


      echo View::make('board/modal-card', [
        'titulo' => 'Novo Card',
        'descricao' => '',
        'aula_inicial' => '',
        'aula_final' => '',
        'cursos' => $cursos,
        'turmas' => $turmas,
        'ucs' => $ucs,
        'create' => true,
      ]);
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode([
        'error' => 'Erro ao carregar o modal de criação',
        'message' => $e->getMessage()
      ]);
    }
  }

  public function store()
  {
    header('Content-Type: application/json');
    try {
      $input = json_decode(file_get_contents('php://input'), true);

      $card = new Card();
      $card->titulo = $input['titulo'] ?? 'Novo Card';
      $card->descricao = $input['descricao'] ?? '';
      $card->turma_id = $input['turma_id'];
      $card->curso_id = $input['curso_id'];
      $card->uc_id = $input['uc_id'];
      $card->aula_inicial = $input['aula_inicial'] ?? '';
      $card->aula_final = $input['aula_final'] ?? '';
      // Defina uma ordem padrão, se aplicável
      $card->ordem = Card::where('turma_id', $input['turma_id'])->max('ordem') + 1;
      $card->save();

      if (isset($input['indicadores'])) {
        $card->indicadores()->sync($input['indicadores']);
      }
      if (isset($input['conhecimentos'])) {
        $card->conhecimentos()->sync($input['conhecimentos']);
      }
      if (isset($input['habilidades'])) {
        $card->habilidades()->sync($input['habilidades']);
      }
      if (isset($input['atitudes'])) {
        $card->atitudes()->sync($input['atitudes']);
      }

      // Retorna o card recém-criado para o front-end
      echo json_encode([
        'success' => true,
        'message' => 'Card criado com sucesso',
        'card' => $card
      ]);
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode([
        'error' => 'Erro ao criar o card',
        'message' => $e->getMessage()
      ]);
    }
  }

  public function show($id)
  {
    header('Content-Type: application/json');
    try {
      $card = Card::with([])->find($id);

      $card->indicadores = $card->indicadores()->pluck('id');
      $card->conhecimentos = $card->conhecimentos()->pluck('id');
      $card->habilidades = $card->habilidades()->pluck('id');
      $card->atitudes = $card->atitudes()->pluck('id');


      if (!$card) {
        http_response_code(404);
        echo json_encode(['error' => 'Card não encontrado']);
        return;
      }

      $cursos = Curso::all();
      $turmas = Turma::all();
      $ucs = Uc::all();

      echo json_encode([
        'drop' => [
          'drop-cursos' => $cursos,
          'drop-turmas' => $turmas,
          'drop-ucs' => $ucs,
        ],
        'card' => $card,
      ]);
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode([
        'error' => 'Erro ao buscar dados do card',
        'message' => $e->getMessage()
      ]);
    }
  }

  public function ucRelated($id)
  {
    header('Content-Type: application/json');
    try {
      $indicadores = Indicador::where('uc_id', $id)->get()->map(function ($item) {
        return [
          'value' => $item->id,
          'name' => ($item->numero_ind ?? '') . ' - ' . ($item->descricao ?? '')
        ];
      })->toArray();

      $conhecimentos = Conhecimento::where('uc_id', $id)->get()->map(function ($item) {
        return [
          'value' => $item->id,
          'name' => ($item->numero_con ?? '') . ' - ' . ($item->descricao ?? '')
        ];
      })->toArray();

      $habilidades = Habilidade::where('uc_id', $id)->get()->map(function ($item) {
        return [
          'value' => $item->id,
          'name' => ($item->numero_hab ?? '') . ' - ' . ($item->descricao ?? '')
        ];
      })->toArray();

      $atitudes = Atitude::where('uc_id', $id)->get()->map(function ($item) {
        return [
          'value' => $item->id,
          'name' => ($item->numero_ati ?? '') . ' - ' . ($item->descricao ?? '')
        ];
      })->toArray();

      echo json_encode([
        'indicadores' => $indicadores,
        'conhecimentos' => $conhecimentos,
        'habilidades' => $habilidades,
        'atitudes' => $atitudes,
      ]);
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode([
        'error' => 'Erro ao buscar dados da UC',
        'message' => $e->getMessage()
      ]);
    }
  }

  public function reorder()
  {
    header('Content-Type: application/json');

    try {
      // Obter dados do POST
      $input = json_decode(file_get_contents('php://input'), true);

      if (!isset($input['cards']) || !is_array($input['cards'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Dados inválidos']);
        return;
      }

      $cards = $input['cards'];

      // Atualizar cada card
      foreach ($cards as $cardData) {
        $card = Card::find($cardData['id']);

        if ($card) {
          // Atualizar turma_id se mudou de coluna
          if (isset($cardData['turma_id'])) {
            $card->turma_id = $cardData['turma_id'];
          }

          // Atualizar ordem se você tiver esse campo
          if (isset($cardData['ordem'])) {
            $card->ordem = $cardData['ordem'];
          }

          $card->save();
        }
      }

      echo json_encode([
        'success' => true,
        'message' => 'Cards atualizados com sucesso'
      ]);
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode([
        'error' => 'Erro ao atualizar cards',
        'message' => $e->getMessage()
      ]);
    }
  }

  public function details($id)
  {
    header('Content-Type: application/json');
    try {
      $card = Card::find($id);

      if (!$card) {
        http_response_code(404);
        echo json_encode(['error' => 'Card não encontrado']);
        return;
      }

      echo View::make('board/modal-card', [
        'titulo' => $card->titulo,
        'descricao' => $card->descricao,
        'aula_inicial' => $card->aula_inicial,
        'aula_final' => $card->aula_final,
      ]);
      //            echo json_encode([
      //                'card' => $card
      //            ]);
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode([
        'error' => 'Erro ao buscar detalhes do card',
        'message' => $e->getMessage()
      ]);
    }
  }

  public function update($id)
  {
    header('Content-Type: application/json');
    try {
      $input = json_decode(file_get_contents('php://input'), true);

      $card = Card::find($id);

      if (!$card) {
        http_response_code(404);
        echo json_encode(['error' => 'Card não encontrado']);
        return;
      }

      $card->titulo = $input['titulo'];
      $card->descricao = $input['descricao'];
      $card->turma_id = $input['turma_id'];
      $card->curso_id = $input['curso_id'];
      $card->uc_id = $input['uc_id'];
      $card->aula_inicial = $input['aula_inicial'];
      $card->aula_final = $input['aula_final'];
      $card->save();

      $card->indicadores()->sync($input['indicadores']);
      $card->conhecimentos()->sync($input['conhecimentos']);
      $card->habilidades()->sync($input['habilidades']);
      $card->atitudes()->sync($input['atitudes']);

      echo json_encode([
        'success' => true,
        'message' => 'Card atualizado com sucesso'
      ]);
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode([
        'error' => 'Erro ao atualizar o card',
        'message' => $e->getMessage()
      ]);
    }
  }
}
