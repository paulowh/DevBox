# 🚀 SOLUÇÃO FINAL - Estrutura de Pastas

## ✅ Problema Identificado

As pastas `app/Storage/cache` e `app/Storage/logs` não existem no servidor!

## 📋 EXECUTE VIA SSH:

### **Opção 1 - Script Automático (Recomendado):**

```bash
cd ~/domains/paulowh.com/public_html/devbox
git pull
bash create-folders.sh
```

Este script vai:
- ✅ Criar `app/Storage/cache/`
- ✅ Criar `app/Storage/logs/`
- ✅ Criar arquivos `.gitkeep`
- ✅ Criar arquivos `.htaccess` de proteção
- ✅ Ajustar permissões (775)

---

### **Opção 2 - Comandos Manuais:**

```bash
cd ~/domains/paulowh.com/public_html/devbox

# Criar pastas
mkdir -p app/Storage/cache
mkdir -p app/Storage/logs

# Ajustar permissões
chmod -R 775 app/Storage/

# Criar proteção .htaccess
echo "Order Deny,Allow" > app/Storage/cache/.htaccess
echo "Deny from all" >> app/Storage/cache/.htaccess

echo "Order Deny,Allow" > app/Storage/logs/.htaccess
echo "Deny from all" >> app/Storage/logs/.htaccess

# Verificar
ls -la app/Storage/
```

---

## 🧪 TESTAR

Após executar, acesse:

1. **Diagnóstico:** https://devbox.paulowh.com/diagnostico.php
   - Item 5 deve mostrar ✅ para `app/Storage/cache` e `app/Storage/logs`

2. **Teste Completo:** https://devbox.paulowh.com/teste.php
   - Todos os 5 passos devem passar com ✅

3. **Site:** https://devbox.paulowh.com/

---

## 📊 Estrutura Final Esperada

```
app/
├── Config/
├── Controllers/
├── Core/
├── Database/
├── Models/
├── Resources/
├── Routes/
├── Services/
└── Storage/           ← Com maiúscula!
    ├── cache/
    │   ├── .gitkeep
    │   └── .htaccess
    └── logs/
        ├── .gitkeep
        └── .htaccess
```

---

## ✅ Checklist Final

Execute este comando para verificar tudo:

```bash
cd ~/domains/paulowh.com/public_html/devbox

echo "Verificando estrutura..."
ls -la app/ | grep "^d"
echo ""
echo "Verificando Storage..."
ls -la app/Storage/
echo ""
echo "Verificando permissões..."
stat -c "%a %n" app/Storage/cache
stat -c "%a %n" app/Storage/logs
```

**Permissões corretas:**
- `775 app/Storage/cache`
- `775 app/Storage/logs`

---

## 🎯 Comando Único (Tudo de Uma Vez)

```bash
cd ~/domains/paulowh.com/public_html/devbox && git pull && bash create-folders.sh && composer dump-autoload --optimize
```

Este comando:
1. Puxa atualizações do Git
2. Cria estrutura de pastas
3. Regenera autoload do Composer

**Pronto!** Depois disso o site deve funcionar! 🚀
