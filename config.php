<?php
/**
 * IDENTIDAD HÍDRICA - Configuración del Sitio
 * 
 * ⚠️ IMPORTANTE: Cambiá la contraseña antes de subir al servidor
 */

// Información del sitio
define('SITE_NAME', 'Identidad Hídrica');
define('SITE_SLOGAN', 'Único medio especializado en los usos del agua');
define('SITE_URL', 'http://localhost/identidad-hidrica');

// ⚠️ CREDENCIALES DEL ADMIN - ¡CAMBIAR ANTES DE SUBIR!
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', 'IH2026agua!'); // Cambiá esta contraseña

// Rutas del sistema (no modificar)
define('ROOT_PATH', __DIR__);
define('DATA_PATH', ROOT_PATH . '/data');
define('UPLOADS_PATH', ROOT_PATH . '/uploads');
define('UPLOADS_URL', 'uploads');

// Configuración
define('ARTICLES_PER_PAGE', 12);

// Categorías del sitio
$CATEGORIAS = [
    'mundo' => ['nombre' => 'Mundo', 'color' => '#2d9cdb', 'icono' => ''],
    'sustentabilidad' => ['nombre' => 'Sustentabilidad', 'color' => '#1f6f8b', 'icono' => ''],
    'gestion-agua' => ['nombre' => 'Gestión del Agua', 'color' => '#1a5f7a', 'icono' => ''],
    'agro' => ['nombre' => 'Agro', 'color' => '#2d6a4f', 'icono' => ''],
    'columnas' => ['nombre' => 'Columnas', 'color' => '#c9a962', 'icono' => ''],
    'lifestyle' => ['nombre' => 'Lifestyle', 'color' => '#56d5e8', 'icono' => ''],
    'gourmet' => ['nombre' => 'Agua Gourmet', 'color' => '#9b59b6', 'icono' => ''],
    'recreacion' => ['nombre' => 'Recreación', 'color' => '#3498db', 'icono' => ''],
    'hidratacion' => ['nombre' => 'Hidratación', 'color' => '#00bcd4', 'icono' => ''],
    'tecnologia' => ['nombre' => 'Tecnología', 'color' => '#34495e', 'icono' => ''],
    'en-accion' => ['nombre' => 'En Acción', 'color' => '#f39c12', 'icono' => ''],
    'nosotros' => ['nombre' => 'Nosotros', 'color' => '#2d9cdb', 'icono' => '']
];

// Configuración del Newsletter
define('NEWSLETTER_EMAIL', ''); // Email donde llegarán las suscripciones

// Tamaños de banners publicitarios disponibles
$BANNER_SIZES = [
    'leaderboard' => ['width' => 970, 'height' => 90, 'nombre' => 'Leaderboard (970x90)'],
    'medium-rectangle' => ['width' => 300, 'height' => 250, 'nombre' => 'Medium Rectangle (300x250)'],
    'large-rectangle' => ['width' => 336, 'height' => 280, 'nombre' => 'Large Rectangle (336x280)'],
    'half-page' => ['width' => 300, 'height' => 600, 'nombre' => 'Half Page (300x600)'],
    'billboard' => ['width' => 970, 'height' => 250, 'nombre' => 'Billboard (970x250)'],
    'custom' => ['width' => 600, 'height' => 250, 'nombre' => 'Custom (600x250)']
];

// Zona horaria Argentina
date_default_timezone_set('America/Argentina/Buenos_Aires');

// ============================================
// INICIALIZACIÓN AUTOMÁTICA
// Crea las carpetas necesarias si no existen
// ============================================
function inicializarSistema() {
    $carpetas = [DATA_PATH, UPLOADS_PATH];
    foreach ($carpetas as $carpeta) {
        if (!is_dir($carpeta)) {
            @mkdir($carpeta, 0755, true);
        }
    }
    
    // Crear archivos JSON vacíos si no existen
    $archivos = [
        DATA_PATH . '/articulos.json' => '[]',
        DATA_PATH . '/videos.json' => '[]',
        DATA_PATH . '/publicidad.json' => '[]',
        DATA_PATH . '/config.json' => '{"newsletter_email":"","nosotros_contenido":""}',
        DATA_PATH . '/suscriptores.json' => '[]'
    ];
    
    foreach ($archivos as $archivo => $contenido) {
        if (!file_exists($archivo)) {
            @file_put_contents($archivo, $contenido);
        }
    }
}

// Ejecutar inicialización
inicializarSistema();

// Función helper para categorías
function getCategoria($slug) {
    global $CATEGORIAS;
    return $CATEGORIAS[$slug] ?? ['nombre' => ucfirst($slug), 'color' => '#2d9cdb', 'icono' => '📰'];
}
