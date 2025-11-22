# 🚨 ERRO 500 - SOLUÇÃO URGENTE

## ⚡ AÇÃO IMEDIATA

### PASSO 1: Executar Diagnóstico

Acesse: **https://devbox.paulowh.com/diagnostico.php**

Este arquivo vai mostrar exatamente qual é o problema!

---

## 🔧 CAUSAS MAIS COMUNS DO ERRO 500

### 1. ❌ Arquivo `.env` não existe

**SOLUÇÃO:**

```bash
# Via SSH ou File Manager
cd public_html/devbox
cp .env.hostinger .env
nano .env  # Edite com dados do banco
```

**Pelo File Manager:**

1. Entre em `public_html/devbox/`
2. Clique com direito em `.env.hostinger` → **Copy**
3. Cole na mesma pasta
4. Renomeie a cópia para `.env`
5. Edite o `.env` e configure o banco de dados

---

### 2. ❌ Pasta `vendor/` não existe (Composer não foi executado)

**SOLUÇÃO:**

```bash
# Via SSH
cd public_html/devbox
composer install --no-dev --optimize-autoloader
```

**Se não tiver SSH:**

- Entre em contato com suporte Hostinger
- Ou suba a pasta `vendor/` via FTP (não recomendado - muito pesado)

---

### 3. ❌ Permissões erradas nas pastas

**SOLUÇÃO:**

```bash
# Via SSH
cd public_html/devbox
chmod -R 755 .
chmod -R 775 app/storage/cache/
chmod -R 775 app/storage/logs/
chmod -R 775 public/uploads/
```

**Pelo File Manager:**

1. Navegue até `public_html/devbox/app/storage/cache/`
2. Botão direito → **Change Permissions** ou **Permissions**
3. Digite `775` ou marque: `Read, Write, Execute` para Owner e Group
4. ✅ Marque: **Apply to subdirectories**
5. Repita para `app/storage/logs/` e `public/uploads/`

---

### 4. ❌ Erro no arquivo `.htaccess`

**SOLUÇÃO TEMPORÁRIA (teste):**

```bash
# Renomeie o .htaccess para desativar temporariamente
cd public_html/devbox/public
mv .htaccess .htaccess.backup
```

Teste: https://devbox.paulowh.com/index.php

Se funcionar, o problema está no `.htaccess`. Use o novo arquivo commitado no Git.

---

### 5. ❌ Versão do PHP incompatível

**SOLUÇÃO:**

1. Painel Hostinger → **PHP Configuration**
2. Selecione: **PHP 8.0**, **8.1** ou **8.2**
3. Salve e aguarde 1-2 minutos

---

### 6. ❌ Erro de sintaxe no código PHP

**SOLUÇÃO - Ativar Debug:**

Edite o arquivo `.env`:

```env
APP_DEBUG=true
APP_ENV=development
```

Recarregue a página - vai mostrar o erro específico.

**⚠️ IMPORTANTE:** Depois de resolver, volte para:

```env
APP_DEBUG=false
APP_ENV=production
```

---

## 📋 CHECKLIST DE VERIFICAÇÃO

Execute cada item e marque:

```bash
# 1. Arquivo .env existe?
ls -la /home/u123456789/domains/devbox.paulowh.com/public_html/devbox/.env

# 2. Vendor existe?
ls -la /home/u123456789/domains/devbox.paulowh.com/public_html/devbox/vendor/autoload.php

# 3. Permissões corretas?
ls -la /home/u123456789/domains/devbox.paulowh.com/public_html/devbox/app/storage/

# 4. PHP 8.0+?
php -v

# 5. Extensões necessárias?
php -m | grep -E 'pdo|mysql|mbstring'
```

---

## 🔍 VER LOGS DE ERRO

### Logs do PHP (Hostinger):

```bash
# Via SSH
tail -f ~/domains/devbox.paulowh.com/logs/error_log
# ou
tail -f ~/public_html/devbox/app/storage/logs/*.log
```

### Pelo File Manager:

1. Vá em `domains/devbox.paulowh.com/logs/`
2. Baixe o arquivo `error_log`
3. Abra e veja os erros mais recentes

---

## 🎯 COMANDO COMPLETO (RESOLVER TUDO DE UMA VEZ)

Se tiver acesso SSH, execute:

```bash
# Navegue até o projeto
cd ~/domains/devbox.paulowh.com/public_html/devbox

# Ou (dependendo da estrutura)
cd ~/public_html/devbox

# Crie o .env se não existir
if [ ! -f .env ]; then
    cp .env.hostinger .env
    echo "⚠️ EDITE O ARQUIVO .env COM SEUS DADOS DO BANCO!"
fi

# Instale dependências
composer install --no-dev --optimize-autoloader

# Ajuste permissões
chmod -R 755 .
chmod -R 775 app/storage/cache/
chmod -R 775 app/storage/logs/
chmod -R 775 public/uploads/

# Verifique
echo "✅ Verificando estrutura..."
ls -la .env
ls -la vendor/autoload.php
ls -la app/storage/cache/

echo "✅ Acesse: https://devbox.paulowh.com/diagnostico.php"
```

---

## 📞 ÚLTIMO RECURSO

Se NADA funcionar:

1. **Baixe os logs:**

   - `domains/devbox.paulowh.com/logs/error_log`
   - `public_html/devbox/app/storage/logs/`

2. **Tire screenshots do diagnóstico:**

   - https://devbox.paulowh.com/diagnostico.php

3. **Verifique Document Root:**

   - Painel → Domínios → devbox.paulowh.com
   - Document Root DEVE ser: `public_html/devbox/public`

4. **Contate suporte Hostinger** com os logs

---

## ✅ TESTE FINAL

Após executar as soluções:

1. Acesse: https://devbox.paulowh.com/diagnostico.php
2. Verifique se todos os itens estão ✅ (verde)
3. Acesse: https://devbox.paulowh.com/

**Deu certo?** 🎉
**Ainda com erro?** Veja os logs e o diagnóstico!
