<?php
/**
 * IDENTIDAD HÍDRICA - Diagnóstico y Reparación
 * 
 * Subí este archivo a tu carpeta y ejecutalo en el navegador
 * URL: http://localhost/identidad-hidrica/diagnostico.php
 * 
 * ¡BORRALO DESPUÉS DE USAR!
 */

echo "<html><head><title>Diagnóstico - Identidad Hídrica</title>";
echo "<style>
    body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
    h1 { color: #0a1628; }
    .ok { color: green; font-weight: bold; }
    .error { color: red; font-weight: bold; }
    .warning { color: orange; font-weight: bold; }
    .box { background: #f5f5f5; padding: 15px; margin: 10px 0; border-radius: 8px; }
    .fix { background: #e8f5e9; padding: 15px; margin: 20px 0; border-radius: 8px; border-left: 4px solid green; }
    code { background: #eee; padding: 2px 6px; border-radius: 4px; }
</style></head><body>";

echo "<h1>🔧 Diagnóstico de Identidad Hídrica</h1>";

$errores = 0;
$reparados = 0;

// 1. Verificar PHP
echo "<div class='box'>";
echo "<h3>1. Versión de PHP</h3>";
$phpVersion = phpversion();
if (version_compare($phpVersion, '7.4', '>=')) {
    echo "<span class='ok'>✅ PHP $phpVersion - OK</span>";
} else {
    echo "<span class='error'>❌ PHP $phpVersion - Se requiere 7.4 o superior</span>";
    $errores++;
}
echo "</div>";

// 2. Verificar archivos principales
echo "<div class='box'>";
echo "<h3>2. Archivos principales</h3>";
$archivos = ['config.php', 'functions.php', 'index.php', 'admin/index.php', 'admin/articulos.php'];
foreach ($archivos as $archivo) {
    if (file_exists(__DIR__ . '/' . $archivo)) {
        echo "<span class='ok'>✅</span> $archivo<br>";
    } else {
        echo "<span class='error'>❌</span> $archivo - <strong>FALTA</strong><br>";
        $errores++;
    }
}
echo "</div>";

// 3. Verificar y crear carpetas
echo "<div class='box'>";
echo "<h3>3. Carpetas de datos</h3>";

$carpetas = [
    'data',
    'uploads'
];

foreach ($carpetas as $carpeta) {
    $ruta = __DIR__ . '/' . $carpeta;
    
    if (!is_dir($ruta)) {
        // Intentar crear
        if (@mkdir($ruta, 0755, true)) {
            echo "<span class='ok'>✅</span> /$carpeta - <strong>CREADA</strong><br>";
            $reparados++;
        } else {
            echo "<span class='error'>❌</span> /$carpeta - No existe y no se pudo crear<br>";
            $errores++;
        }
    } else {
        // Verificar permisos de escritura
        if (is_writable($ruta)) {
            echo "<span class='ok'>✅</span> /$carpeta - OK (escribible)<br>";
        } else {
            // Intentar arreglar permisos
            if (@chmod($ruta, 0755)) {
                echo "<span class='ok'>✅</span> /$carpeta - <strong>PERMISOS REPARADOS</strong><br>";
                $reparados++;
            } else {
                echo "<span class='error'>❌</span> /$carpeta - Existe pero NO es escribible<br>";
                $errores++;
            }
        }
    }
}
echo "</div>";

// 4. Probar escritura real
echo "<div class='box'>";
echo "<h3>4. Test de escritura</h3>";

$testFile = __DIR__ . '/data/test_write.json';
$testData = json_encode(['test' => true, 'fecha' => date('Y-m-d H:i:s')]);

if (@file_put_contents($testFile, $testData)) {
    echo "<span class='ok'>✅ Escritura en /data/ - OK</span><br>";
    @unlink($testFile); // Borrar archivo de prueba
} else {
    echo "<span class='error'>❌ No se puede escribir en /data/</span><br>";
    $errores++;
}

$testUpload = __DIR__ . '/uploads/test_write.txt';
if (@file_put_contents($testUpload, 'test')) {
    echo "<span class='ok'>✅ Escritura en uploads/ - OK</span><br>";
    @unlink($testUpload);
} else {
    echo "<span class='error'>❌ No se puede escribir en uploads/</span><br>";
    $errores++;
}
echo "</div>";

// 5. Verificar config.php
echo "<div class='box'>";
echo "<h3>5. Configuración</h3>";
if (file_exists(__DIR__ . '/config.php')) {
    require_once __DIR__ . '/config.php';
    echo "SITE_URL: <code>" . SITE_URL . "</code><br>";
    echo "CONTENT_PATH: <code>" . CONTENT_PATH . "</code><br>";
    
    if (strpos(SITE_URL, 'localhost') !== false || strpos(SITE_URL, '127.0.0.1') !== false) {
        echo "<span class='ok'>✅ URL configurada para local</span>";
    } else {
        echo "<span class='warning'>⚠️ URL configurada para producción - Cambiala a localhost para pruebas</span>";
    }
}
echo "</div>";

// Resumen
echo "<div class='box' style='background: " . ($errores == 0 ? '#e8f5e9' : '#ffebee') . "'>";
echo "<h3>📊 Resumen</h3>";
if ($errores == 0) {
    echo "<span class='ok' style='font-size: 1.2em'>✅ Todo está funcionando correctamente!</span><br><br>";
    echo "Podés ir al <a href='admin/'>Panel de Administración</a> y empezar a cargar contenido.";
} else {
    echo "<span class='error'>❌ Se encontraron $errores errores</span><br>";
    if ($reparados > 0) {
        echo "<span class='ok'>✅ Se repararon $reparados problemas automáticamente</span><br>";
        echo "<br><strong>Recargá esta página para verificar si se solucionaron todos los problemas.</strong>";
    }
}
echo "</div>";

// Instrucciones manuales si hay errores
if ($errores > 0) {
    echo "<div class='fix'>";
    echo "<h3>🔨 Solución manual (Windows + XAMPP)</h3>";
    echo "<ol>";
    echo "<li>Abrí el Explorador de Windows</li>";
    echo "<li>Andá a <code>C:\\xampp\\htdocs\\identidad-hidrica\\</code></li>";
    echo "<li>Click derecho en la carpeta <code>data</code> → Propiedades</li>";
    echo "<li>Destildá <strong>'Solo lectura'</strong> si está marcado</li>";
    echo "<li>Click en 'Aplicar a subcarpetas'</li>";
    echo "<li>Hacé lo mismo con la carpeta <code>uploads</code></li>";
    echo "<li>Recargá esta página</li>";
    echo "</ol>";
    echo "</div>";
}

echo "<hr><p style='color:#999; font-size: 0.9em'>⚠️ <strong>IMPORTANTE:</strong> Borrá este archivo (diagnostico.php) después de usarlo.</p>";
echo "</body></html>";
