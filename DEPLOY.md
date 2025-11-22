# 📦 Deploy no Hostinger

## 📁 Estrutura no Servidor

```
public_html/
    devbox/                     ← Raiz do projeto (clone do GitHub)
        app/
        public/                 ← SUBDOMÍNIO/DOMÍNIO DEVE APONTAR AQUI!
        vendor/
        composer.json
        .env                    ← Configure este arquivo!
        .htaccess
```

## ✅ Configurações Importantes

### 1. Document Root (PRINCIPAL - RESOLVE ERRO 403!)

No painel do Hostinger, configure o **Document Root** do seu domínio/subdomínio:

**OPÇÃO A - Se estiver usando SUBDOMÍNIO (ex: app.seudominio.com):**
```
public_html/devbox/public
```

**OPÇÃO B - Se estiver usando DOMÍNIO PRINCIPAL:**
```
domains/seudominio.com/public_html/devbox/public
```

**PASSO A PASSO:**

1. Acesse o painel do Hostinger
2. Vá em **Websites** > Clique no seu domínio/subdomínio
3. Vá em **Configurações avançadas** ou **Domínios** > **Gerenciar**
4. Procure por **Document Root**, **Diretório Raiz** ou **Web Root**
5. Altere para: `public_html/devbox/public`
6. Clique em **Salvar** e aguarde alguns minutos

⚠️ **IMPORTANTE**: O site DEVE apontar para `public_html/devbox/public`, não para `public_html/devbox`! Isso resolve o erro 403.

### 2. Arquivo .env

**RENOMEIE** o arquivo `.env.hostinger` para `.env` e configure com seus dados:

```env
APP_NAME="DevBox"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seudominio.com.br

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u123456789_devbox
DB_USERNAME=u123456789_user
DB_PASSWORD=sua_senha_aqui
```

**Onde encontrar os dados do banco:**

1. Painel Hostinger > **Databases** > **MySQL Databases**
2. Copie: nome do banco, usuário e senha
3. Cole no arquivo `.env`

### 3. Permissões de Pastas (IMPORTANTE!)

Via SSH ou File Manager do Hostinger, configure as permissões:

```bash
cd public_html/devbox
chmod -R 755 .
chmod -R 775 app/storage/
chmod -R 775 public/uploads/
```

**Pelo File Manager:**

1. Navegue até `public_html/devbox/`
2. Clique com botão direito nas pastas abaixo > **Permissions** (ou **Change Permissions**)
   - `app/storage/cache/` → **775** (rwxrwxr-x)
   - `app/storage/logs/` → **775** (rwxrwxr-x)
   - `public/uploads/` → **775** (rwxrwxr-x)
3. Marque a opção **"Apply to subdirectories"** se disponível

### 4. PHP Version

Versão mínima requerida: **PHP 8.0+**

**Configure:**

1. Painel Hostinger > **Configurações Avançadas** > **PHP Configuration**
2. Selecione: **PHP 8.0** ou superior
3. Salve

### 5. Composer (OBRIGATÓRIO!)

Execute via SSH:

```bash
cd public_html/devbox
composer install --no-dev --optimize-autoloader
```

**Se não tiver acesso SSH:**

1. O Hostinger pode ter uma opção no painel para executar comandos
2. Ou faça upload manual da pasta `vendor/` (não recomendado - muito pesado)
3. Verifique se a pasta `vendor/` existe e tem conteúdo dentro de `public_html/devbox/`

### 6. Migrations (Primeira vez)

O sistema roda automaticamente as migrations na primeira execução.

Para rodar manualmente via SSH:

```bash
php migrate_ordem.php
```

## 🔧 Troubleshooting

### ❌ Erro 403 Forbidden

**CAUSAS COMUNS:**

1. **Document Root incorreto** → Configure para `/public`
2. **Permissões erradas** → Execute `chmod -R 755 public_html/`
3. **Arquivo .htaccess** → Verifique se existe em `/public/.htaccess`
4. **mod_rewrite desabilitado** → Entre em contato com suporte Hostinger

**SOLUÇÃO PASSO A PASSO:**

```bash
# Via SSH
cd public_html/devbox
chmod -R 755 .
chmod -R 775 app/storage/
chmod -R 775 public/uploads/
ls -la public/.htaccess  # Deve existir!
ls -la .htaccess         # Também deve existir na raiz!
```

**Verifique o Document Root:**
- Deve estar configurado para: `public_html/devbox/public`
- NÃO pode ser: `public_html/devbox` (sem o /public)

### ❌ Site não carrega (404)

- Verifique se o Document Root está em `/public`
- Verifique se o arquivo `.htaccess` existe na raiz e em `/public`
- Teste acessar: `seudominio.com/index.php` diretamente

### ❌ Assets CSS/JS não carregam

- Os assets já estão compilados em `/public/assets`
- Não precisa rodar `npm install` ou `npm run build` no servidor
- Verifique permissões: `chmod -R 755 public/assets/`

### ❌ Erro de Database

**Sintomas:** "Connection refused" ou "Access denied"

**SOLUÇÃO:**

1. Crie o banco de dados no painel do Hostinger
2. Verifique as credenciais no arquivo `.env`
3. Teste conexão com phpMyAdmin
4. Se usar `DB_HOST=localhost` não funcionar, tente o IP do servidor

### ❌ Erro 500 Internal Server Error

**Verifique:**

```bash
# Permissões das pastas de storage
chmod -R 775 app/storage/cache/
chmod -R 775 app/storage/logs/

# Ver logs de erro
tail -f app/storage/logs/*.log
```

**Habilite debug temporariamente:**
No `.env`, altere:

```env
APP_DEBUG=true
```

_(Não esqueça de voltar para `false` depois!)_

## 📝 Checklist de Deploy

- [ ] Projeto clonado do GitHub em `public_html/devbox/`
- [ ] Document Root configurado para `public_html/devbox/public`
- [ ] PHP 8.0+ configurado
- [ ] Arquivo `.env` criado em `public_html/devbox/.env` e configurado
- [ ] Banco de dados criado no Hostinger
- [ ] Credenciais do banco configuradas no `.env`
- [ ] Permissões: `755` geral, `775` em `app/storage/*` e `public/uploads/`
- [ ] Composer executado: `vendor/` existe em `public_html/devbox/vendor/`
- [ ] Assets compilados presentes em `public_html/devbox/public/assets/`
- [ ] `.htaccess` existe em `public_html/devbox/.htaccess`
- [ ] `.htaccess` existe em `public_html/devbox/public/.htaccess`
- [ ] Site acessível sem erro 403 ou 500

## 🎯 Resumo Rápido

**No Painel do Hostinger:**
1. Configure Document Root: `public_html/devbox/public`
2. Configure PHP 8.0+
3. Crie o banco de dados MySQL

**Via SSH ou File Manager:**
```bash
cd public_html/devbox
cp .env.hostinger .env              # Copiar template
nano .env                            # Editar com dados do banco
composer install --no-dev            # Instalar dependências
chmod -R 775 app/storage/            # Permissões storage
chmod -R 775 public/uploads/         # Permissões uploads
```

**Teste:**
- Acesse: `https://seudominio.com`
- Se ver erro 403: Document Root está errado
- Se ver erro 500: Verifique permissões e `.env`
- Se der erro de DB: Verifique credenciais no `.env`

## 🚀 Estrutura Completa no Hostinger

```
public_html/
    devbox/                             ← Clone do GitHub aqui
        ├── .env                        ← CRIE/CONFIGURE este arquivo!
        ├── .htaccess                   ← Redireciona para /public (caso acesse devbox/)
        ├── .env.hostinger              ← Template de exemplo
        ├── composer.json
        ├── composer.lock
        ├── package.json
        ├── app/
        │   ├── config/
        │   ├── controllers/
        │   ├── models/
        │   ├── storage/
        │   │   ├── cache/              ← Permissão 775
        │   │   └── logs/               ← Permissão 775
        │   └── ...
        ├── vendor/                     ← Gerado pelo composer install
        │   └── autoload.php
        └── public/                     ← DOCUMENT ROOT DEVE APONTAR AQUI!
            ├── index.php               ← Entry point da aplicação
            ├── .htaccess               ← Rotas e rewrites
            ├── assets/                 ← CSS/JS compilados (versionado no Git)
            │   ├── css/
            │   ├── js/
            │   └── .vite/
            └── uploads/                ← Permissão 775 (uploads de usuários)
```

## 📞 Suporte

Se após seguir todos os passos ainda tiver problemas:

1. Ative `APP_DEBUG=true` temporariamente
2. Verifique os logs em `app/storage/logs/`
3. Entre em contato com suporte do Hostinger para verificar:
   - mod_rewrite habilitado
   - Permissões do servidor
   - Versão do PHP
