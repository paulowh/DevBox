<?php

namespace App\Controllers;

use App\Models\Card;

class CardController
{
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
