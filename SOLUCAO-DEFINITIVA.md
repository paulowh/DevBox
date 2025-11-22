# 🔧 SOLUÇÃO DEFINITIVA - Class Not Found

## ❌ Problema
```
Fatal error: Class "App\Core\EloquentBootstrap" not found
```

## 🎯 Causa Raiz

O problema é **case-sensitivity** no Linux:
- **Namespace:** `App\Core` (maiúsculas)
- **Pasta:** `app/core` (minúsculas)
- **Linux diferencia** maiúsculas de minúsculas!

## ✅ SOLUÇÃO - Execute via SSH

### **Opção 1: Regenerar Autoload Forçadamente**

```bash
cd ~/domains/paulowh.com/public_html/devbox

# Limpar cache
composer clear-cache

# Remover autoload antigo
rm -rf vendor/composer/autoload_*

# Regenerar
composer dump-autoload --optimize --no-cache

# Testar
php -r "require 'vendor/autoload.php'; var_dump(class_exists('App\Core\EloquentBootstrap'));"
```

**Deve retornar:** `bool(true)`

---

### **Opção 2: Script Automático**

```bash
cd ~/domains/paulowh.com/public_html/devbox
bash fix-autoload.sh
```

Este script faz:
- ✅ Limpa cache do Composer
- ✅ Remove arquivos de autoload antigos
- ✅ Regenera autoload otimizado
- ✅ Testa todas as classes

---

### **Opção 3: Reinstalar Tudo**

Se as opções acima não funcionarem:

```bash
cd ~/domains/paulowh.com/public_html/devbox

# Backup do .env
cp .env .env.backup

# Remover vendor completamente
rm -rf vendor/

# Reinstalar
composer install --no-dev --optimize-autoloader

# Restaurar .env
cp .env.backup .env

# Testar
php -r "require 'vendor/autoload.php'; echo class_exists('App\Core\App') ? 'OK' : 'ERRO';"
```

---

## 🧪 TESTAR APÓS FIX

```bash
# Via SSH - teste direto
cd ~/domains/paulowh.com/public_html/devbox
php -r "
require 'vendor/autoload.php';
\$classes = ['App\Core\EloquentBootstrap', 'App\Core\App', 'App\Core\Router'];
foreach (\$classes as \$c) {
    echo (class_exists(\$c) ? '✅' : '❌') . ' ' . \$c . PHP_EOL;
}
"
```

**Via Navegador:**
- https://devbox.paulowh.com/teste.php
- https://devbox.paulowh.com/fix.php

---

## 📋 CHECKLIST DE DIAGNÓSTICO

Execute estes comandos para diagnosticar:

```bash
cd ~/domains/paulowh.com/public_html/devbox

# 1. Verificar se arquivos existem
ls -la app/core/*.php

# 2. Verificar composer.json
cat composer.json | grep -A 5 "autoload"

# 3. Verificar autoload_psr4.php
cat vendor/composer/autoload_psr4.php | grep "App"

# 4. Testar classe
php -r "require 'vendor/autoload.php'; var_dump(class_exists('App\Core\EloquentBootstrap'));"
```

**Envie o resultado se ainda houver erro!**

---

## 💡 COMANDOS RESUMIDOS

### Solução Rápida (1 comando):
```bash
cd ~/domains/paulowh.com/public_html/devbox && composer dump-autoload --optimize --no-cache
```

### Solução Completa (script):
```bash
cd ~/domains/paulowh.com/public_html/devbox && bash fix-autoload.sh
```

### Reinstalar (se nada funcionar):
```bash
cd ~/domains/paulowh.com/public_html/devbox && rm -rf vendor/ && composer install --no-dev --optimize-autoloader
```

---

## ⚠️ IMPORTANTE

Depois de executar qualquer solução:

1. **Aguarde 10 segundos** (cache do OPcache)
2. **Limpe cache do navegador** (Ctrl+Shift+R)
3. **Teste**: https://devbox.paulowh.com/teste.php

---

## 📊 RESULTADO ESPERADO

Após o fix, `teste.php` deve mostrar:

```
✅ 1. Testando Composer Autoload
✅ 2. Testando Carregamento do .env
✅ 3. Testando Eloquent Bootstrap
✅ 4. Testando Database Initializer
✅ 5. Testando App Core
```

Se **todos passarem**, acesse: https://devbox.paulowh.com/
