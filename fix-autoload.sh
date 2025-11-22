#!/bin/bash
# ================================================
# Diagnóstico e Fix - Autoload do Composer
# Execute via SSH: bash fix-autoload.sh
# ================================================

echo "🔍 Diagnóstico do Autoload - DevBox"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

# 1. Verificar diretório
if [ ! -f "composer.json" ]; then
    echo "❌ Erro: Execute na raiz do projeto (public_html/devbox/)"
    exit 1
fi

echo "📁 Diretório: $(pwd)"
echo ""

# 2. Verificar estrutura de pastas
echo "📂 Verificando estrutura app/core/..."
if [ -d "app/core" ]; then
    echo "✅ Pasta app/core/ existe"
    ls -la app/core/*.php | head -5
else
    echo "❌ Pasta app/core/ NÃO existe!"
fi
echo ""

# 3. Verificar composer.json
echo "📄 Verificando composer.json autoload..."
cat composer.json | grep -A 10 '"autoload"'
echo ""

# 4. Limpar cache do Composer
echo "🧹 Limpando cache do Composer..."
composer clear-cache
echo ""

# 5. Remover autoload antigo
echo "🗑️ Removendo arquivos de autoload antigos..."
rm -f vendor/composer/autoload_*.php
echo "✅ Arquivos removidos"
echo ""

# 6. Regenerar autoload
echo "🔄 Regenerando autoload do Composer..."
composer dump-autoload --optimize --no-cache

if [ $? -eq 0 ]; then
    echo "✅ Autoload regenerado com sucesso!"
else
    echo "❌ Erro ao regenerar autoload"
    exit 1
fi
echo ""

# 7. Verificar arquivo de autoload PSR-4
echo "🔍 Verificando mapeamento PSR-4..."
cat vendor/composer/autoload_psr4.php | grep "App"
echo ""

# 8. Testar carregamento de classe
echo "🧪 Testando carregamento de classe EloquentBootstrap..."
php -r "
require 'vendor/autoload.php';
if (class_exists('App\Core\EloquentBootstrap')) {
    echo '✅ Classe App\Core\EloquentBootstrap ENCONTRADA!\n';
    echo '✅ Namespace funcionando corretamente!\n';
} else {
    echo '❌ Classe App\Core\EloquentBootstrap NÃO ENCONTRADA!\n';
    echo '❌ Verificar estrutura de pastas e namespaces\n';
}
"
echo ""

# 9. Testar todas as classes principais
echo "🧪 Testando todas as classes principais..."
php -r "
require 'vendor/autoload.php';
\$classes = [
    'App\Core\EloquentBootstrap',
    'App\Core\DatabaseInitializer',
    'App\Core\App',
    'App\Core\Router',
    'App\Core\Database',
    'App\Core\View',
];
foreach (\$classes as \$class) {
    if (class_exists(\$class)) {
        echo '✅ ' . \$class . ' OK\n';
    } else {
        echo '❌ ' . \$class . ' NÃO ENCONTRADA\n';
    }
}
"
echo ""

# 10. Resumo
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "✅ Fix Concluído!"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "📋 Próximos Passos:"
echo "1. Acesse: https://devbox.paulowh.com/teste.php"
echo "2. Verifique se todos os testes passaram"
echo "3. Se ainda houver erro, me envie a saída completa deste script"
echo ""
