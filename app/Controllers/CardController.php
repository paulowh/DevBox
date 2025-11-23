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
  public function show($id)
  {
    header('Content-Type: application/json');
    try {

      $card = Card::find($id);
      $cursos = Curso::all();
      $turmas = Turma::all();
      $ucs = Uc::all();

      echo json_encode([
        'drop' => [
          'drop-cursos' => $cursos,
          'drop-turmas' => $turmas,
          'drop-ucs' => $ucs,
        ],
        'card' => $card
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
      $card = Card::with(['indicadores', 'conhecimentos', 'habilidades', 'atitudes'])->find($id);

      if (!$card) {
        http_response_code(404);
        echo json_encode(['error' => 'Card não encontrado']);
        return;
      }

      echo View::make('board/modal-card', [
        'card' => $card,
        'titulo' => $card->titulo,
        'descricao' => $card->descricao,
        'turma' => $card->turma,
        'uc' => $card->uc,
        'aula_inicial' => $card->aula_inicial,
        'aula_final' => $card->aula_final,
        'indicadores' => $card->indicadores,
        'conhecimentos' => $card->conhecimentos,
        'habilidades' => $card->habilidades,
        'atitudes' => $card->atitudes
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
}
