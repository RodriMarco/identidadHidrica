# 📘 GUÍA DE INSTALACIÓN - Identidad Hídrica

## 🎯 Resumen

Este es un sitio web completo con panel de administración que NO requiere base de datos.
Funciona con PHP y archivos JSON, ideal para hostings económicos.

---

## 📋 REQUISITOS

- Hosting con PHP 7.4 o superior (tu hosting Ampm lo tiene)
- 2GB de espacio es más que suficiente
- Acceso FTP o panel de archivos (cPanel/Plesk)

---

## 🚀 INSTALACIÓN PASO A PASO

### Paso 1: Acceder al Panel de Hosting

1. Entrá a tu panel de hosting (generalmente en tudominio.com/cpanel o el panel que te haya dado Ampm)
2. Buscá el **Administrador de Archivos** o **File Manager**
3. Navegá hasta la carpeta `public_html` (es donde va el sitio web)

### Paso 2: Hacer Backup del Sitio Actual

**¡IMPORTANTE!** Antes de borrar nada:
1. Descargá una copia de la carpeta `public_html` a tu computadora
2. Guardala por si necesitás volver atrás

### Paso 3: Limpiar la Carpeta

1. Seleccioná todos los archivos EXCEPTO:
   - `.htaccess` (si hay uno)
   - Cualquier carpeta de emails
2. Eliminá los archivos de WordPress

### Paso 4: Subir los Archivos Nuevos

**Opción A - Subir ZIP (Más rápido):**
1. Comprimí toda la carpeta `identidad-hidrica` en un archivo ZIP
2. Subí el ZIP a `public_html`
3. Hacé clic derecho → "Extraer" o "Extract"
4. Mové todos los archivos extraídos a la raíz de `public_html`
5. Borrá el ZIP y la carpeta vacía

**Opción B - Subir por FTP:**
1. Usá FileZilla u otro cliente FTP
2. Conectate con tus datos de FTP
3. Subí todo el contenido de la carpeta `identidad-hidrica` a `public_html`

### Paso 5: Configurar Permisos

En el Administrador de Archivos:
1. Carpeta `data` → Permisos 755
2. Carpeta `uploads` → Permisos 755
3. Archivos `.json` dentro de `data` → Permisos 644

### Paso 6: Cambiar la Contraseña del Admin

**¡MUY IMPORTANTE!**
1. Abrí el archivo `config.php`
2. Buscá la línea: `define('ADMIN_PASS', 'IH2026agua!');`
3. Cambiá `IH2026agua!` por tu contraseña segura
4. Guardá el archivo

---

## ✅ VERIFICAR QUE FUNCIONA

1. Abrí `https://identidadhidrica.com.ar` → Deberías ver el sitio
2. Abrí `https://identidadhidrica.com.ar/admin/` → Deberías ver el login
3. Ingresá con:
   - Usuario: `admin`
   - Contraseña: la que pusiste en config.php

---

## 📝 CÓMO AGREGAR UNA NOTICIA

1. Entrá al panel: `https://identidadhidrica.com.ar/admin/`
2. Hacé clic en **"📝 Artículos"**
3. Hacé clic en **"➕ Nuevo Artículo"**
4. Completá:
   - **Título**: El titular de la noticia
   - **Extracto**: Resumen corto (aparece en las cards)
   - **Contenido**: El texto completo de la nota
   - **Categoría**: Elegí una (Agro, Geopolítica, Columnas, etc.)
   - **Autor**: Nombre del periodista
   - **Imagen**: Subí una foto (JPG, PNG, máx 5MB)
5. Marcá **"Publicado"** para que aparezca en el sitio
6. Marcá **"⭐ Destacado"** para que aparezca en el hero de la home
7. Hacé clic en **"📤 Publicar"**

### Formato del Contenido

Podés usar HTML básico para dar formato:

```html
<p>Este es un párrafo normal.</p>

<h2>Este es un subtítulo</h2>

<p>Texto con <strong>negrita</strong> y <em>cursiva</em>.</p>

<blockquote>Esta es una cita destacada.</blockquote>
```

---

## 🎥 CÓMO AGREGAR UN VIDEO

1. Subí tu video a YouTube
2. En el panel, andá a **"🎥 Videos"**
3. Pegá el título y la URL de YouTube
4. Hacé clic en **"Agregar"**

---

## 📢 CÓMO AGREGAR PUBLICIDAD

1. En el panel, andá a **"📢 Publicidad"**
2. Completá:
   - **Título**: Nombre del anunciante
   - **Posición**: Dónde aparecerá (header, sidebar, footer)
   - **URL**: Link al sitio del anunciante
   - **Imagen**: Banner publicitario
3. Hacé clic en **"Agregar Banner"**

---

## 📁 ESTRUCTURA DE CARPETAS

```
public_html/
├── admin/           → Panel de administración
│   ├── login.php
│   ├── index.php
│   ├── articulos.php
│   ├── videos.php
│   └── publicidad.php
├── assets/
│   └── css/
│       └── style.css
├── data/            → Datos del sitio (JSON)
│   ├── articulos.json
│   ├── videos.json
│   └── publicidad.json
├── uploads/         → Imágenes subidas
├── config.php       → Configuración
├── functions.php    → Funciones del sistema
├── index.php        → Página principal
├── articulo.php     → Página de artículo
├── categoria.php    → Página de categoría
└── .htaccess        → Configuración del servidor
```

---

## ⚠️ SOLUCIÓN DE PROBLEMAS

### "No se pueden subir imágenes"
- Verificá que la carpeta `uploads` tenga permisos 755
- Verificá que el archivo sea JPG, PNG, GIF o WEBP
- Verificá que sea menor a 5MB

### "No se guardan los artículos"
- Verificá que la carpeta `data` tenga permisos 755
- Verificá que los archivos `.json` tengan permisos 644

### "Error 500"
- Revisá el archivo `config.php`, puede haber un error de sintaxis
- Contactá a tu hosting para ver el log de errores

### "No aparece el sitio"
- Verificá que el archivo `index.php` esté en la raíz de `public_html`
- Verificá que no haya un `index.html` que tenga prioridad

---

## 🔒 SEGURIDAD

1. **Cambiá la contraseña** del admin inmediatamente
2. El archivo `.htaccess` ya protege la carpeta `data`
3. No compartas las credenciales del admin
4. Hacé backups periódicos de la carpeta `data`

---

## 📞 SOPORTE

Si tenés problemas, contactame y te ayudo.

¡Éxitos con Identidad Hídrica! 💧
