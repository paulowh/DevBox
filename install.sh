#!/bin/bash
# ================================================
# Script de Instalação - DevBox (Hostinger)
# ================================================
# Execute via SSH: bash install.sh
# ================================================

echo "🚀 Iniciando instalação do DevBox..."

# 1. Verificar se estamos no diretório correto
if [ ! -f "composer.json" ]; then
    echo "❌ Erro: composer.json não encontrado!"
    echo "Execute este script na raiz do projeto (public_html/devbox/)"
    exit 1
fi

echo "✅ Diretório correto detectado"

# 2. Instalar/Atualizar dependências do Composer
echo ""
echo "📦 Instalando dependências do Composer..."
composer install --no-dev --optimize-autoloader

if [ $? -eq 0 ]; then
    echo "✅ Composer instalado com sucesso!"
else
    echo "❌ Erro ao instalar Composer"
    exit 1
fi

# 3. Regenerar autoload (IMPORTANTE!)
echo ""
echo "🔄 Regenerando autoload do Composer..."
composer dump-autoload --optimize

if [ $? -eq 0 ]; then
    echo "✅ Autoload regenerado com sucesso!"
else
    echo "❌ Erro ao regenerar autoload"
    exit 1
fi

# 4. Verificar arquivo .env
echo ""
echo "🔍 Verificando arquivo .env..."
if [ -f ".env" ]; then
    echo "✅ Arquivo .env existe"
else
    echo "⚠️ Arquivo .env não encontrado!"
    if [ -f ".env.hostinger" ]; then
        echo "📋 Copiando .env.hostinger para .env..."
        cp .env.hostinger .env
        echo "⚠️ EDITE o arquivo .env com suas credenciais do banco!"
    else
        echo "❌ .env.hostinger também não encontrado!"
    fi
fi

# 5. Configurar permissões
echo ""
echo "🔐 Configurando permissões das pastas..."
chmod -R 755 .
chmod -R 775 app/storage/cache/
chmod -R 775 app/storage/logs/
chmod -R 775 public/uploads/

echo "✅ Permissões configuradas!"

# 6. Resumo
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "✅ Instalação Concluída!"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "📋 Próximos Passos:"
echo "1. Edite o arquivo .env com suas credenciais"
echo "2. Acesse: https://devbox.paulowh.com/teste.php"
echo "3. Verifique se todos os testes passaram"
echo "4. Acesse: https://devbox.paulowh.com/"
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
