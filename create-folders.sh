#!/bin/bash
# ================================================
# Criar Estrutura de Pastas - DevBox
# Execute via SSH: bash create-folders.sh
# ================================================

echo "📁 Criando estrutura de pastas do DevBox..."

cd ~/domains/paulowh.com/public_html/devbox

# Criar pastas de Storage se não existirem
echo ""
echo "📂 Verificando/Criando pastas Storage..."

if [ ! -d "app/Storage" ]; then
    echo "⚠️  app/Storage/ não existe, criando..."
    mkdir -p app/Storage
fi

if [ ! -d "app/Storage/cache" ]; then
    echo "➕ Criando app/Storage/cache/"
    mkdir -p app/Storage/cache
    echo "✅ app/Storage/cache/ criada"
fi

if [ ! -d "app/Storage/logs" ]; then
    echo "➕ Criando app/Storage/logs/"
    mkdir -p app/Storage/logs
    echo "✅ app/Storage/logs/ criada"
fi

# Criar .gitkeep para manter as pastas no Git
echo ""
echo "📌 Criando arquivos .gitkeep..."
touch app/Storage/cache/.gitkeep
touch app/Storage/logs/.gitkeep
touch public/uploads/.gitkeep

# Ajustar permissões
echo ""
echo "🔐 Ajustando permissões..."
chmod -R 775 app/Storage/
chmod -R 775 public/uploads/

echo "✅ Permissões ajustadas!"

# Criar arquivos .htaccess de proteção
echo ""
echo "🔒 Criando arquivos .htaccess de proteção..."

# Storage/cache
cat > app/Storage/cache/.htaccess << 'EOF'
# Negar acesso a esta pasta
Order Deny,Allow
Deny from all
EOF

# Storage/logs
cat > app/Storage/logs/.htaccess << 'EOF'
# Negar acesso a esta pasta
Order Deny,Allow
Deny from all
EOF

echo "✅ Arquivos .htaccess criados!"

# Verificar estrutura
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "📊 Estrutura Criada:"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
ls -la app/Storage/
echo ""
ls -la app/Storage/cache/
echo ""
ls -la app/Storage/logs/
echo ""

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "✅ Estrutura de Pastas Criada com Sucesso!"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "📋 Próximos Passos:"
echo "1. Acesse: https://devbox.paulowh.com/diagnostico.php"
echo "2. Verifique se todos os itens estão ✅"
echo "3. Acesse: https://devbox.paulowh.com/"
echo ""
