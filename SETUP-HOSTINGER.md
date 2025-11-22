# 🚨 GUIA RÁPIDO - HOSTINGER

## ⚠️ ERRO 403? SIGA ESTES PASSOS:

### 1️⃣ DOCUMENT ROOT (MAIS IMPORTANTE!)

No painel do Hostinger:

- **Websites** > Seu domínio > **Configurações Avançadas** (ou Domínios)
- Procure: **Document Root** / **Diretório Raiz** / **Web Root**
- Altere para: `public_html/devbox/public`
- **NÃO** use apenas `public_html/devbox` ❌

```
ERRADO:  public_html/devbox          ❌
CORRETO: public_html/devbox/public   ✅
```

### 2️⃣ CRIAR ARQUIVO .ENV

Via File Manager:

1. Entre em `public_html/devbox/`
2. Copie o arquivo `.env.hostinger` e renomeie para `.env`
3. Edite o `.env` e coloque seus dados do banco:

```env
DB_DATABASE=u123456789_devbox
DB_USERNAME=u123456789_user
DB_PASSWORD=sua_senha
```

### 3️⃣ PERMISSÕES

Via File Manager, dê permissão **775** para:

- `public_html/devbox/app/storage/cache/`
- `public_html/devbox/app/storage/logs/`
- `public_html/devbox/public/uploads/`

Como fazer:

- Botão direito na pasta > **Permissions** ou **Change Permissions**
- Digite: `775` ou marque: `rwxrwxr-x`

### 4️⃣ COMPOSER (Se tiver SSH)

```bash
cd public_html/devbox
composer install --no-dev --optimize-autoloader
```

### 5️⃣ PHP VERSION

Configure para **PHP 8.0** ou superior:

- Painel > **PHP Configuration** > Selecione PHP 8.0+

---

## ✅ CHECKLIST VISUAL

```
□ Document Root = public_html/devbox/public
□ Arquivo .env criado e configurado
□ Banco de dados criado no painel
□ Permissões 775 nas pastas storage e uploads
□ PHP 8.0+ configurado
□ Composer executado (pasta vendor/ existe)
```

## 🎯 TESTE FINAL

Acesse seu domínio:

- ✅ Site carrega = SUCESSO!
- ❌ Erro 403 = Document Root errado (volte ao passo 1)
- ❌ Erro 500 = Permissões ou .env (passos 2 e 3)
- ❌ Erro DB = Configuração .env (passo 2)

---

**Dúvidas?** Veja o arquivo `DEPLOY.md` completo
