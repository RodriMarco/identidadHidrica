# 🌊 IDENTIDAD HÍDRICA - Guía de Instalación

## Requisitos del Hosting
- PHP 7.4 o superior (tu hosting de AMPM seguro lo tiene)
- 2GB de espacio es suficiente ✅
- No necesita MySQL

---

## 📦 PASO 1: Preparar los Archivos

1. Descomprimí el archivo `identidad-hidrica.zip`
2. Vas a ver esta estructura:

```
identidad-hidrica/
├── index.php          (página principal)
├── articulo.php       (página de artículo)
├── categoria.php      (página de categoría)
├── config.php         (configuración)
├── functions.php      (funciones)
├── .htaccess          (seguridad)
├── crear-datos-ejemplo.php
├── admin/             (panel de administración)
│   ├── index.php
│   ├── login.php
│   ├── articulos.php
│   ├── videos.php
│   ├── publicidad.php
│   └── logout.php
├── assets/
│   └── css/
│       └── style.css
├── content/           (acá se guardan los datos)
│   ├── articulos/
│   ├── videos/
│   └── publicidad/
└── uploads/           (imágenes subidas)
```

---

## 🔧 PASO 2: Configurar

Abrí el archivo `config.php` y modificá:

```php
// Cambiá la URL por tu dominio
define('SITE_URL', 'https://identidadhidrica.com.ar');

// ¡IMPORTANTE! Cambiá la contraseña del admin
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', 'tu_contraseña_segura');  // CAMBIÁ ESTO
```

---

## 📤 PASO 3: Subir al Hosting

### Opción A: Usando cPanel File Manager (más fácil)

1. Entrá a tu cPanel de AMPM
2. Abrí "Administrador de archivos" (File Manager)
3. Navegá a la carpeta `public_html`
4. **Borrá o renombrá** la instalación actual de WordPress (hacé backup antes)
5. Subí todos los archivos del ZIP a `public_html`

### Opción B: Usando FTP

1. Conectate con FileZilla u otro cliente FTP
2. Datos de conexión (los tenés en tu panel de AMPM):
   - Host: tu-dominio.com o la IP
   - Usuario: tu usuario FTP
   - Contraseña: tu contraseña FTP
   - Puerto: 21
3. Subí todo a la carpeta `public_html`

---

## ✅ PASO 4: Verificar Permisos

Asegurate que estas carpetas tengan permisos de escritura (755 o 777):

- `/content/`
- `/content/articulos/`
- `/content/videos/`
- `/content/publicidad/`
- `/uploads/`

En cPanel: Click derecho → Permisos → 755

---

## 🎯 PASO 5: Crear Datos de Ejemplo (Opcional)

Para que el sitio no aparezca vacío:

1. Entrá a: `https://identidadhidrica.com.ar/crear-datos-ejemplo.php`
2. Se van a crear artículos y videos de prueba
3. **Después borrá este archivo** por seguridad

---

## 🔐 PASO 6: Acceder al Panel Admin

1. Andá a: `https://identidadhidrica.com.ar/admin/`
2. Ingresá con:
   - Usuario: `admin`
   - Contraseña: la que pusiste en config.php

---

## 📝 Cómo Agregar Contenido

### Crear un Artículo:
1. En el admin, click en "📝 Artículos"
2. Click en "➕ Nuevo Artículo"
3. Completá:
   - **Título**: El título de la nota
   - **Extracto**: Resumen corto (aparece en las cards)
   - **Contenido**: El texto completo
   - **Categoría**: Geopolítica, Agro, Columnas, etc.
   - **Imagen**: Subí una imagen destacada
4. Marcá "Destacado" si querés que aparezca grande en la home
5. Click en "Publicar"

### Agregar un Video:
1. Subí tu video a YouTube
2. En el admin → Videos
3. Pegá la URL de YouTube
4. Listo! El sistema extrae la miniatura automáticamente

### Gestionar Publicidad:
1. Admin → Publicidad
2. Subí el banner del anunciante
3. Elegí la posición (header, sidebar, etc.)
4. Activá/pausá según necesites

---

## 🎨 Personalización Rápida

### Cambiar colores:
Editá `assets/css/style.css`, buscá las variables al principio:

```css
:root {
    --color-deep-ocean: #0a1628;    /* Azul oscuro */
    --color-aqua: #2d9cdb;          /* Celeste principal */
    --color-agro-green: #2d6a4f;    /* Verde agro */
    --color-gold: #c9a962;          /* Dorado columnas */
}
```

### Cambiar logo:
Por ahora es texto. Si querés poner una imagen:
1. Subí tu logo a `/assets/img/logo.png`
2. Editá el header en `index.php`

---

## ⚠️ Solución de Problemas

### "Error 500" al entrar
- Verificá que PHP esté habilitado en tu hosting
- Revisá que los archivos se subieron completos

### "No se pueden guardar artículos"
- Dale permisos 755 a la carpeta `/content/`

### "No aparecen las imágenes"
- Dale permisos 755 a `/uploads/`
- Verificá que el dominio en config.php sea correcto

### "Página en blanco"
- Habilitá errores PHP temporalmente agregando esto al inicio de index.php:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

---

## 🆘 ¿Necesitás Ayuda?

Si tenés problemas con la instalación, los errores más comunes son:
1. Permisos de carpetas
2. URL mal configurada en config.php
3. Archivos subidos incompletos

Verificá estos tres puntos primero.

---

## 📋 Checklist Final

- [ ] Archivos subidos a public_html
- [ ] config.php editado con tu URL y contraseña
- [ ] Permisos 755 en /content y /uploads
- [ ] Probé entrar al admin
- [ ] Creé mi primer artículo
- [ ] Borré crear-datos-ejemplo.php

---

¡Listo! Tu portal de noticias está funcionando. 🎉
