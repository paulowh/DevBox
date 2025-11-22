# 🔧 SOLUÇÃO DO ERRO "Class not found"

## ❌ Erro Encontrado:
```
Fatal error: Class "App\Core\EloquentBootstrap" not found
```

## ✅ Causa:
O **autoload do Composer** não foi regenerado após o deploy. As classes do projeto não estão sendo carregadas.

## 🚀 SOLUÇÃO RÁPIDA (SSH)

### Via SSH (Recomendado):

```bash
# 1. Acesse via SSH e navegue até o projeto
cd ~/domains/paulowh.com/public_html/devbox

# 2. Regenere o autoload do Composer
composer dump-autoload --optimize

# 3. Verifique
ls -la vendor/composer/autoload_psr4.php

# 4. Teste
php -r "require 'vendor/autoload.php'; echo class_exists('App\Core\EloquentBootstrap') ? 'OK' : 'ERRO';"
```

**Se funcionar, você verá: "OK"**

---

## 🌐 SOLUÇÃO VIA NAVEGADOR

Se não tiver acesso SSH, acesse:

**https://devbox.paulowh.com/fix.php**

Este script vai:
1. Regenerar o autoload automaticamente
2. Verificar se as classes estão carregando
3. Mostrar o status de cada classe

---

## 📋 SOLUÇÃO COMPLETA (Script Automático)

### Via SSH - Execute o script de instalação:

```bash
cd ~/domains/paulowh.com/public_html/devbox
bash install.sh
```

Este script vai:
- ✅ Instalar/atualizar dependências do Composer
- ✅ Regenerar autoload otimizado
- ✅ Configurar permissões corretas
- ✅ Verificar arquivo .env

---

## 🔍 VERIFICAR SE RESOLVEU

Após executar a solução, teste:

1. **Teste Completo:**
   https://devbox.paulowh.com/teste.php

2. **Site Principal:**
   https://devbox.paulowh.com/

---

## 🐛 Se Ainda Não Funcionar

### Verifique o arquivo `vendor/composer/autoload_psr4.php`:

```bash
cat vendor/composer/autoload_psr4.php | grep "App"
```

Deve mostrar:
```php
'App\\' => array($baseDir . '/app'),
```

### Se não mostrar, reinstale o Composer:

```bash
cd ~/domains/paulowh.com/public_html/devbox
rm -rf vendor/
composer install --no-dev --optimize-autoloader
```

---

## 📊 CHECKLIST

Execute cada comando em ordem:

```bash
# 1. Ir para o diretório do projeto
cd ~/domains/paulowh.com/public_html/devbox

# 2. Verificar composer.json
cat composer.json | grep -A 5 "autoload"

# 3. Limpar e reinstalar (se necessário)
rm -rf vendor/composer/
composer dump-autoload --optimize

# 4. Verificar se funcionou
php -r "require 'vendor/autoload.php'; var_dump(class_exists('App\Core\App'));"
```

Deve retornar: `bool(true)`

---

## 🎯 RESUMO - 3 MANEIRAS DE RESOLVER:

### Opção 1 - SSH (Mais Rápido):
```bash
cd ~/domains/paulowh.com/public_html/devbox
composer dump-autoload --optimize
```

### Opção 2 - Navegador:
Acesse: https://devbox.paulowh.com/fix.php

### Opção 3 - Script Completo:
```bash
cd ~/domains/paulowh.com/public_html/devbox
bash install.sh
```

---

**Depois de executar, teste em:**
https://devbox.paulowh.com/teste.php

Se todos os passos passarem ✅, acesse:
https://devbox.paulowh.com/
