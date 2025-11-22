<?php
// Arquivo de proteção - impede listagem de diretório
http_response_code(403);
exit('Forbidden');
