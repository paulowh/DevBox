<?php

namespace App\Controllers;

use App\Models\Card;

class CardController
{
    public function show($id)
    {
        header('Content-Type: application/json');
        try {
            $card = Card::with([
                'uc',
                'indicadores',
                'conhecimentos',
                'habilidades',
                'atitudes',
            ])->find($id);

            if (!$card) {
                http_response_code(404);
                echo json_encode(['error' => 'Card não encontrado']);
                return;
            }

            // Carregar opções dos dropdowns (ajuste os models conforme necessário)
            $cursos = \App\Models\Curso::all();
            $turmas = \App\Models\Turma::all();
            $ucs = \App\Models\Uc::all();
            $indicadores = \App\Models\Indicador::all();
            $conhecimentos = \App\Models\Conhecimento::all();
            $habilidades = \App\Models\Habilidade::all();
            $atitudes = \App\Models\Atitude::all();

            echo json_encode([
                'card' => $card,
                'cursos' => $cursos,
                'turmas' => $turmas,
                'ucs' => $ucs,
                'indicadores' => $indicadores,
                'conhecimentos' => $conhecimentos,
                'habilidades' => $habilidades,
                'atitudes' => $atitudes,
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'error' => 'Erro ao buscar dados do card',
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
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'error' => 'Erro ao atualizar cards',
                'message' => $e->getMessage()
            ]);
        }
    }
}
