<?php
/**
 * IDENTIDAD HÍDRICA - Funciones del Sistema
 * 
 * Este archivo maneja todas las operaciones de lectura/escritura
 * usando archivos JSON (no necesita base de datos)
 */

require_once __DIR__ . '/config.php';

// ============================================
// FUNCIONES DE ARTÍCULOS
// ============================================

function getArticulos($limite = null, $categoria = null, $destacados = false) {
    $archivo = DATA_PATH . '/articulos.json';

    if (!file_exists($archivo)) {
        return [];
    }

    $articulos = json_decode(file_get_contents($archivo), true) ?: [];

    // Filtrar por categoría
    if ($categoria) {
        $articulos = array_filter($articulos, fn($a) => $a['categoria'] === $categoria);
    }

    // Filtrar solo publicados
    $articulos = array_filter($articulos, fn($a) => !empty($a['publicado']));

    // Filtrar destacados
    if ($destacados) {
        $articulos = array_filter($articulos, fn($a) => !empty($a['destacado']));
    }

    // Ordenar por fecha (más recientes primero)
    usort($articulos, fn($a, $b) => strtotime($b['fecha']) - strtotime($a['fecha']));

    // Limitar resultados
    if ($limite) {
        $articulos = array_slice($articulos, 0, $limite);
    }

    return array_values($articulos);
}

function getArticulosPortada($limite = 3) {
    $archivo = DATA_PATH . '/articulos.json';

    if (!file_exists($archivo)) {
        return [];
    }

    $articulos = json_decode(file_get_contents($archivo), true) ?: [];

    // Filtrar solo publicados y marcados para portada
    $articulos = array_filter($articulos, fn($a) => !empty($a['publicado']) && !empty($a['portada']));

    // Ordenar por fecha (más recientes primero)
    usort($articulos, fn($a, $b) => strtotime($b['fecha']) - strtotime($a['fecha']));

    // Limitar resultados
    return array_slice(array_values($articulos), 0, $limite);
}

function getArticulosAleatorios($cantidad = 3, $categoria = null) {
    $archivo = DATA_PATH . '/articulos.json';

    if (!file_exists($archivo)) {
        return [];
    }

    $articulos = json_decode(file_get_contents($archivo), true) ?: [];

    // Filtrar por categoría
    if ($categoria) {
        $articulos = array_filter($articulos, fn($a) => $a['categoria'] === $categoria);
    }

    // Filtrar solo publicados
    $articulos = array_filter($articulos, fn($a) => !empty($a['publicado']));

    $articulos = array_values($articulos);

    // Mezclar aleatoriamente
    shuffle($articulos);

    // Limitar resultados
    return array_slice($articulos, 0, $cantidad);
}

function getArticulo($slug) {
    $articulos = json_decode(file_get_contents(DATA_PATH . '/articulos.json'), true) ?: [];
    
    foreach ($articulos as $art) {
        if ($art['slug'] === $slug) {
            return $art;
        }
    }
    return null;
}

function guardarArticulo($datos, $slugExistente = null) {
    $archivo = DATA_PATH . '/articulos.json';
    $articulos = file_exists($archivo) ? json_decode(file_get_contents($archivo), true) : [];
    $articulos = $articulos ?: [];
    
    $slug = $slugExistente ?: crearSlug($datos['titulo']);
    
    // Asegurar slug único
    if (!$slugExistente) {
        $slugBase = $slug;
        $contador = 1;
        while (array_filter($articulos, fn($a) => $a['slug'] === $slug)) {
            $slug = $slugBase . '-' . $contador++;
        }
    }
    
    $articulo = [
        'slug' => $slug,
        'titulo' => $datos['titulo'],
        'contenido' => $datos['contenido'],
        'extracto' => $datos['extracto'] ?: substr(strip_tags($datos['contenido']), 0, 150) . '...',
        'categoria' => $datos['categoria'],
        'imagen' => $datos['imagen'] ?? '',
        'autor' => $datos['autor'] ?: 'Redacción',
        'fecha' => $datos['fecha'] ?? date('Y-m-d H:i:s'),
        'publicado' => !empty($datos['publicado']),
        'destacado' => !empty($datos['destacado']),
        'portada' => !empty($datos['portada'])
    ];
    
    // Actualizar o agregar
    $encontrado = false;
    foreach ($articulos as $i => $art) {
        if ($art['slug'] === $slug) {
            $articulos[$i] = $articulo;
            $encontrado = true;
            break;
        }
    }
    
    if (!$encontrado) {
        array_unshift($articulos, $articulo);
    }
    
    file_put_contents($archivo, json_encode($articulos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    return $slug;
}

function eliminarArticulo($slug) {
    $archivo = DATA_PATH . '/articulos.json';
    $articulos = json_decode(file_get_contents($archivo), true) ?: [];
    $articulos = array_filter($articulos, fn($a) => $a['slug'] !== $slug);
    file_put_contents($archivo, json_encode(array_values($articulos), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function getTodosArticulos() {
    $archivo = DATA_PATH . '/articulos.json';
    if (!file_exists($archivo)) return [];
    $articulos = json_decode(file_get_contents($archivo), true) ?: [];
    usort($articulos, fn($a, $b) => strtotime($b['fecha']) - strtotime($a['fecha']));
    return $articulos;
}

// ============================================
// FUNCIONES DE VIDEOS
// ============================================

function getVideos($limite = null) {
    $archivo = DATA_PATH . '/videos.json';
    if (!file_exists($archivo)) return [];
    $videos = json_decode(file_get_contents($archivo), true) ?: [];
    return $limite ? array_slice($videos, 0, $limite) : $videos;
}

function guardarVideos($videos) {
    file_put_contents(DATA_PATH . '/videos.json', json_encode($videos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// ============================================
// FUNCIONES DE PUBLICIDAD
// ============================================

function getPublicidad($posicion = null) {
    $archivo = DATA_PATH . '/publicidad.json';
    if (!file_exists($archivo)) return [];
    $pubs = json_decode(file_get_contents($archivo), true) ?: [];
    
    if ($posicion) {
        $pubs = array_filter($pubs, fn($p) => $p['posicion'] === $posicion && !empty($p['activo']));
    }
    return array_values($pubs);
}

function guardarPublicidad($pubs) {
    file_put_contents(DATA_PATH . '/publicidad.json', json_encode($pubs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function getPublicidadConfig() {
    $archivo = DATA_PATH . '/publicidad_config.json';
    if (!file_exists($archivo)) return [];
    return json_decode(file_get_contents($archivo), true) ?: [];
}

function guardarPublicidadConfig($config) {
    file_put_contents(DATA_PATH . '/publicidad_config.json', json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// ============================================
// FUNCIONES AUXILIARES
// ============================================

function crearSlug($texto) {
    $texto = mb_strtolower($texto, 'UTF-8');
    $texto = preg_replace('/[áàäâã]/u', 'a', $texto);
    $texto = preg_replace('/[éèëê]/u', 'e', $texto);
    $texto = preg_replace('/[íìïî]/u', 'i', $texto);
    $texto = preg_replace('/[óòöôõ]/u', 'o', $texto);
    $texto = preg_replace('/[úùüû]/u', 'u', $texto);
    $texto = preg_replace('/[ñ]/u', 'n', $texto);
    $texto = preg_replace('/[^a-z0-9]+/', '-', $texto);
    return trim($texto, '-');
}

function formatearFecha($fecha) {
    $meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
    $t = strtotime($fecha);
    return date('j', $t) . ' de ' . $meses[date('n', $t) - 1] . ' de ' . date('Y', $t);
}

function tiempoRelativo($fecha) {
    $diff = time() - strtotime($fecha);
    if ($diff < 60) return 'Hace un momento';
    if ($diff < 3600) return 'Hace ' . floor($diff/60) . ' min';
    if ($diff < 86400) return 'Hace ' . floor($diff/3600) . ' hs';
    if ($diff < 172800) return 'Ayer';
    return formatearFecha($fecha);
}

function getYoutubeId($url) {
    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $m);
    return $m[1] ?? null;
}

function subirImagen($archivo) {
    $permitidos = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $ext = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
    
    if (!in_array($ext, $permitidos)) return ['error' => 'Formato no permitido. Usá JPG, PNG, GIF o WEBP'];
    if ($archivo['size'] > 5 * 1024 * 1024) return ['error' => 'Archivo muy grande (máx 5MB)'];
    
    $nombre = time() . '_' . crearSlug(pathinfo($archivo['name'], PATHINFO_FILENAME)) . '.' . $ext;
    $destino = UPLOADS_PATH . '/' . $nombre;
    
    if (move_uploaded_file($archivo['tmp_name'], $destino)) {
        return ['url' => 'uploads/' . $nombre];
    }
    return ['error' => 'Error al subir archivo'];
}

function verificarAdmin() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['admin_logged'])) {
        header('Location: login.php');
        exit;
    }
}

// ============================================
// FUNCIONES DE CONFIGURACIÓN DEL SITIO
// ============================================

function getSiteConfig() {
    $archivo = DATA_PATH . '/config.json';
    if (!file_exists($archivo)) {
        return ['newsletter_email' => '', 'nosotros_contenido' => ''];
    }
    return json_decode(file_get_contents($archivo), true) ?: ['newsletter_email' => '', 'nosotros_contenido' => ''];
}

function guardarSiteConfig($config) {
    file_put_contents(DATA_PATH . '/config.json', json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// ============================================
// FUNCIONES DE SUSCRIPTORES NEWSLETTER
// ============================================

function getSuscriptores() {
    $archivo = DATA_PATH . '/suscriptores.json';
    if (!file_exists($archivo)) return [];
    return json_decode(file_get_contents($archivo), true) ?: [];
}

function agregarSuscriptor($email) {
    $suscriptores = getSuscriptores();
    // Verificar si ya existe
    foreach ($suscriptores as $s) {
        if ($s['email'] === $email) return false;
    }
    $suscriptores[] = [
        'email' => $email,
        'fecha' => date('Y-m-d H:i:s')
    ];
    file_put_contents(DATA_PATH . '/suscriptores.json', json_encode($suscriptores, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    // Enviar notificación si hay email configurado
    $config = getSiteConfig();
    if (!empty($config['newsletter_email'])) {
        @mail($config['newsletter_email'], 'Nueva suscripción al Newsletter',
            "Nuevo suscriptor: $email\nFecha: " . date('d/m/Y H:i'),
            "From: noreply@identidadhidrica.com.ar");
    }
    return true;
}

// ============================================
// FUNCIONES DE ARTÍCULOS PROGRAMADOS
// ============================================

function getArticulosPublicados($limite = null, $categoria = null, $destacados = false) {
    $archivo = DATA_PATH . '/articulos.json';

    if (!file_exists($archivo)) {
        return [];
    }

    $articulos = json_decode(file_get_contents($archivo), true) ?: [];
    $ahora = time();

    // Filtrar por categoría
    if ($categoria) {
        $articulos = array_filter($articulos, fn($a) => $a['categoria'] === $categoria);
    }

    // Filtrar solo publicados Y con fecha <= ahora (para programados)
    $articulos = array_filter($articulos, function($a) use ($ahora) {
        if (empty($a['publicado'])) return false;
        $fechaPublicacion = isset($a['fecha_publicacion']) ? strtotime($a['fecha_publicacion']) : strtotime($a['fecha']);
        return $fechaPublicacion <= $ahora;
    });

    // Filtrar destacados
    if ($destacados) {
        $articulos = array_filter($articulos, fn($a) => !empty($a['destacado']));
    }

    // Ordenar por fecha (más recientes primero)
    usort($articulos, fn($a, $b) => strtotime($b['fecha']) - strtotime($a['fecha']));

    // Limitar resultados
    if ($limite) {
        $articulos = array_slice($articulos, 0, $limite);
    }

    return array_values($articulos);
}
