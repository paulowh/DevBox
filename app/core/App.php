<?php

namespace App\Core;

class App
{
    public function run()
    {
        // Escolha qual router usar:

        // Opção 1: Router customizado (simples)
        // Router::dispatch();

        // Opção 2: Symfony Router (robusto, com grupos)
        SymfonyRouter::dispatch();
    }
}
