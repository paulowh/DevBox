# ⚙️ CONFIGURAÇÃO DO .ENV - HOSTINGER

## 📝 Configuração Correta para Produção

Edite o arquivo `.env` no Hostinger com estas configurações:

```env
# Application
APP_NAME="DevBox"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://devbox.paulowh.com

# Database
DB_CONNECTION=mysql
DB_HOST=193.203.175.83
DB_PORT=3306
DB_DATABASE=u687609827_dev
DB_USERNAME=u687609827_dev
DB_PASSWORD=sua_senha_real_aqui

# Paths (IMPORTANTE: deixe vazio!)
BASE_PATH=
```

## ⚠️ ATENÇÃO - Configurações Importantes:

### 1. **APP_ENV** e **APP_DEBUG**

```env
# ❌ ERRADO para PRODUÇÃO:
APP_ENV=development
APP_DEBUG=true

# ✅ CORRETO para PRODUÇÃO:
APP_ENV=production
APP_DEBUG=false
```

**Por quê?**
- `APP_DEBUG=true` mostra erros detalhados (inseguro em produção)
- `APP_ENV=development` pode ativar recursos de debug que causam erros

### 2. **BASE_PATH**

```env
# ❌ PODE CAUSAR PROBLEMAS:
BASE_PATH=/

# ✅ CORRETO (vazio):
BASE_PATH=
```

**Por quê?**
- O Document Root já aponta para `/public`
- Adicionar `BASE_PATH=/` pode causar redirecionamentos incorretos

### 3. **APP_URL**

```env
# ✅ CORRETO (com HTTPS e sem barra no final):
APP_URL=https://devbox.paulowh.com

# ❌ ERRADO:
APP_URL=https://devbox.paulowh.com/  (com barra)
APP_URL=http://devbox.paulowh.com    (sem HTTPS)
```

### 4. **DB_HOST**

Você já está usando o IP correto:
```env
DB_HOST=193.203.175.83  ✅
```

Alguns hostings usam `localhost`, mas o IP é mais confiável.

---

## 🔧 Como Aplicar as Mudanças

### Via File Manager (Hostinger):

1. Acesse: **File Manager**
2. Navegue para: `domains/paulowh.com/public_html/devbox/`
3. Clique com direito em `.env` → **Edit**
4. Altere estas linhas:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   BASE_PATH=
   ```
5. **Salve** (Ctrl+S)

### Via SSH:

```bash
cd ~/domains/paulowh.com/public_html/devbox
nano .env

# Altere as linhas necessárias
# Ctrl+X para sair, Y para salvar
```

---

## 🧪 Testar Após as Mudanças

1. **Salve o .env** com as configurações corretas
2. **Aguarde 1-2 minutos**
3. **Teste**:
   - https://devbox.paulowh.com/ (página principal)
   - https://devbox.paulowh.com/teste.php (diagnóstico)

---

## 🐛 Se Ainda Houver Erro 500

### Ative DEBUG temporariamente para ver o erro:

```env
APP_DEBUG=true
APP_ENV=development
```

1. Salve o `.env`
2. Acesse: https://devbox.paulowh.com/
3. **Copie a mensagem de erro completa**
4. **IMPORTANTE**: Volte para produção depois:
   ```env
   APP_DEBUG=false
   APP_ENV=production
   ```

---

## 📋 Checklist Final

- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_URL=https://devbox.paulowh.com` (sem barra no final)
- [ ] `BASE_PATH=` (vazio)
- [ ] `DB_HOST=193.203.175.83` ✅
- [ ] `DB_DATABASE=u687609827_dev` ✅
- [ ] `DB_USERNAME=u687609827_dev` ✅
- [ ] `DB_PASSWORD=***` (configurado)

---

## ✅ .env Completo e Correto

Copie e cole este template (substituindo a senha):

```env
# Application
APP_NAME="DevBox"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://devbox.paulowh.com

# Database
DB_CONNECTION=mysql
DB_HOST=193.203.175.83
DB_PORT=3306
DB_DATABASE=u687609827_dev
DB_USERNAME=u687609827_dev
DB_PASSWORD=SUA_SENHA_AQUI

# Paths
BASE_PATH=
```

**Pronto!** Depois de salvar, teste o site! 🚀
