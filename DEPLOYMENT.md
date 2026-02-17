# 🚀 Guía de Deployment - Identidad Hídrica

## ⚠️ IMPORTANTE: Cómo actualizar el sitio SIN perder datos

Tu sitio guarda todos los datos (artículos, publicidades, configuraciones) en archivos JSON dentro de la carpeta `/data/` y las imágenes en `/uploads/`. **Nunca sobrescribas estas carpetas en producción.**

---

## 📋 Archivos/Carpetas que NUNCA debes sobrescribir en producción:

```
❌ /data/                    # Contiene todos tus artículos, videos, publicidad
❌ /uploads/                 # Contiene todas las imágenes subidas
❌ config.php (opcional)     # Solo si cambiaste la contraseña del admin
```

---

## ✅ Archivos que SÍ debes actualizar al subir cambios:

```
✅ index.php
✅ articulo.php
✅ categoria.php
✅ videos.php
✅ nosotros.php
✅ functions.php
✅ /admin/*.php
✅ /assets/css/*.css
✅ /assets/js/*.js
✅ .htaccess
```

---

## 🔧 Procedimiento recomendado para actualizar:

### Opción 1: FTP/SFTP (más seguro)
1. Conectate por FTP a tu servidor
2. **Hacé un backup** de las carpetas `/data/` y `/uploads/` (descargarlas a tu PC)
3. Subí SOLO los archivos `.php`, `.css`, `.js` y `.htaccess` que cambiaron
4. **NO toques** las carpetas `/data/` y `/uploads/`

### Opción 2: Git en servidor (avanzado)
Si usás Git directamente en el servidor:
```bash
# En el servidor, dentro de la carpeta del sitio
git pull origin main

# Esto NO tocará /data/ ni /uploads/ porque están en .gitignore
```

---

## 📦 Contenido de las carpetas de datos:

### `/data/`
- `articulos.json` - Todos los artículos publicados
- `videos.json` - Videos del podcast
- `publicidad.json` - Configuración de publicidades
- `config.json` - Configuración del sitio (contenido de Nosotros, etc.)
- `suscriptores.json` - Lista de emails suscritos al newsletter

### `/uploads/`
- Todas las imágenes subidas desde el panel de administración
- Organizadas por tipo: artículos, publicidad, etc.

---

## 🆘 Si borraste algo por error:

1. Restaurá el backup de `/data/` y `/uploads/`
2. Si no tenés backup, los datos se perdieron (por eso siempre hacer backup antes)

---

## 💡 Recomendación: Backup automático

Configurá un backup automático semanal de:
- Carpeta `/data/`
- Carpeta `/uploads/`

Podés usar:
- cPanel Backup
- Script cron que copie a Dropbox/Google Drive
- Plugin de backup de tu hosting

---

## 🔐 Cambiar contraseña del admin

Si necesitás cambiar la contraseña:
1. Editá `config.php` línea 15
2. Cambiá `ADMIN_PASS` por tu nueva contraseña
3. Subí el archivo al servidor

**Contraseña actual:** `IH2026agua!`

---

## ✅ Checklist antes de cada actualización:

- [ ] Hice backup de `/data/` y `/uploads/`
- [ ] Probé los cambios en local primero
- [ ] Solo voy a subir archivos de código (no datos)
- [ ] Tengo acceso al panel de admin por si algo falla
