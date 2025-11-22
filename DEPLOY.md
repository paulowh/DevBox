# 📦 Deploy no Hostinger

## ✅ Configurações Importantes

### 1. Document Root (PRINCIPAL - RESOLVE ERRO 403!)
Configure o **Document Root** no painel do Hostinger para:
```
public_html/public
```
ou apenas
```
/public
```

**PASSO A PASSO:**
1. Acesse o painel do Hostinger
2. Vá em **Websites** > Seu site > **Configurações avançadas**
3. Procure por **Document Root** ou **Diretório Raiz**
4. Altere para: `public_html/public` ou `/public`
5. Clique em **Salvar**

⚠️ **IMPORTANTE**: O site DEVE apontar para a pasta `public`, não para a raiz! Isso resolve o erro 403.

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
chmod -R 755 public_html/
chmod -R 775 public_html/app/storage/
chmod -R 775 public_html/public/uploads/
```

**Pelo File Manager:**
- Clique com botão direito na pasta > **Permissions**
- `app/storage/cache/` → 775
- `app/storage/logs/` → 775
- `public/uploads/` → 775

### 4. PHP Version
Versão mínima requerida: **PHP 8.0+**

**Configure:**
1. Painel Hostinger > **Configurações Avançadas** > **PHP Configuration**
2. Selecione: **PHP 8.0** ou superior
3. Salve

### 5. Composer (OBRIGATÓRIO!)
Execute via SSH:
```bash
cd public_html
composer install --no-dev --optimize-autoloader
```

**Se não tiver acesso SSH:**
- O Hostinger geralmente roda o composer automaticamente
- Verifique se a pasta `vendor/` existe e tem conteúdo

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
cd public_html
chmod -R 755 .
chmod -R 775 app/storage/
chmod -R 775 public/uploads/
ls -la public/.htaccess  # Deve existir!
```

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
*(Não esqueça de voltar para `false` depois!)*

## 📝 Checklist de Deploy

- [ ] Document Root configurado para `/public`
- [ ] PHP 8.0+ configurado
- [ ] Arquivo `.env` criado e configurado
- [ ] Banco de dados criado no Hostinger
- [ ] Permissões configuradas (755 geral, 775 storage)
- [ ] Composer instalado (`vendor/` existe)
- [ ] Assets compilados presentes em `/public/assets/`
- [ ] `.htaccess` existe em raiz e em `/public/`
- [ ] Site acessível sem erro 403

## 🚀 Estrutura Esperada no Hostinger

```
public_html/                    ← Raiz do seu repositório
├── .env                        ← Configure este arquivo!
├── .htaccess                   ← Redireciona para /public
├── composer.json
├── app/
├── vendor/                     ← Gerado pelo composer
└── public/                     ← DOCUMENT ROOT DEVE APONTAR AQUI!
    ├── index.php               ← Entry point
    ├── .htaccess               ← Configuração de rotas
    ├── assets/                 ← CSS/JS compilados
    └── uploads/                ← Permissão 775
```

## 📞 Suporte

Se após seguir todos os passos ainda tiver problemas:
1. Ative `APP_DEBUG=true` temporariamente
2. Verifique os logs em `app/storage/logs/`
3. Entre em contato com suporte do Hostinger para verificar:
   - mod_rewrite habilitado
   - Permissões do servidor
   - Versão do PHP
