# 📦 Deploy no Hostinger

## ✅ Configurações Importantes

### 1. Document Root
Configure o **Document Root** no painel do Hostinger para:
```
/public
```
⚠️ **IMPORTANTE**: O site deve apontar para a pasta `public`, não para a raiz do projeto!

### 2. Arquivo .env
Crie um arquivo `.env` na raiz do projeto com as seguintes variáveis:

```env
APP_NAME="DevBox"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seudominio.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=nome_do_banco
DB_USERNAME=usuario_do_banco
DB_PASSWORD=senha_do_banco
```

### 3. Permissões de Pastas
Certifique-se que as seguintes pastas têm permissão de escrita (775 ou 777):
```
app/storage/cache/
app/storage/logs/
public/uploads/
```

### 4. PHP Version
Versão mínima requerida: **PHP 8.0**

Configure no painel do Hostinger: **Gerenciar > Configurações Avançadas > Versão do PHP**

### 5. Composer
Execute via SSH ou pelo painel do Hostinger:
```bash
composer install --no-dev --optimize-autoloader
```

### 6. Migrations (Primeira vez)
O sistema roda automaticamente as migrations na primeira execução.
Se precisar rodar manualmente:
```bash
php migrate_ordem.php
```

## 🔧 Troubleshooting

### Site não carrega (404)
- Verifique se o Document Root está em `/public`
- Verifique se o arquivo `.htaccess` existe na raiz e em `/public`

### Erros de permissão
```bash
chmod -R 775 app/storage/
chmod -R 775 public/uploads/
```

### Assets CSS/JS não carregam
- Os assets já estão compilados em `/public/assets`
- Não precisa rodar `npm install` ou `npm run build` no servidor

### Erro de Database
- Verifique as credenciais no arquivo `.env`
- Certifique-se que o banco de dados foi criado no painel do Hostinger

## 📝 Notas

- Os arquivos compilados (`/public/assets/*`) estão versionados no Git
- Sempre rode `npm run build` localmente antes de fazer push
- O Hostinger puxa automaticamente do GitHub quando você faz push
