<?php
/**
 * IDENTIDAD HÍDRICA - Crear Datos de Ejemplo
 * 
 * Ejecutá este archivo UNA SOLA VEZ para cargar contenido de prueba.
 * URL: http://localhost/identidad-hidrica/crear-datos-ejemplo.php
 * 
 * ¡BORRALO DESPUÉS DE USARLO!
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

// Mostrar en navegador
header('Content-Type: text/html; charset=utf-8');
echo "<html><head><title>Crear datos de ejemplo</title>";
echo "<style>body{font-family:Arial,sans-serif;max-width:800px;margin:50px auto;padding:20px;background:#f5f5f5;}";
echo ".ok{color:green;}.box{background:white;padding:20px;border-radius:10px;margin:20px 0;}</style></head><body>";
echo "<h1>🌊 Identidad Hídrica - Cargando datos de ejemplo</h1>";

// Verificar que las carpetas existen (config.php ya las crea)
echo "<div class='box'>";
echo "<h3>📁 Verificando carpetas...</h3>";
if (is_dir(DATA_PATH)) {
    echo "<span class='ok'>✅ Carpeta /data/ OK</span><br>";
} else {
    mkdir(DATA_PATH, 0755, true);
    echo "<span class='ok'>✅ Carpeta /data/ creada</span><br>";
}

// Cargar artículos de ejemplo
echo "</div><div class='box'>";
echo "<h3>📝 Creando artículos de ejemplo...</h3>";

$articulos = [
    [
        'titulo' => 'China lidera inversión global en desalinización del agua',
        'contenido' => '<p>En un movimiento estratégico que redefine el mapa geopolítico del agua, China ha anunciado una inversión sin precedentes de 50 mil millones de dólares en plantas desalinizadoras a lo largo de su costa.</p><p>Esta iniciativa, que se desarrollará durante los próximos diez años, posicionará al gigante asiático como líder mundial en tecnología de desalinización.</p><h2>Implicaciones para América Latina</h2><p>Expertos del sector señalan que esta movida podría tener repercusiones significativas para países latinoamericanos con extensas costas.</p><blockquote>"El agua será el petróleo del siglo XXI, y quien domine su producción dominará la economía global."</blockquote><p>Argentina, con su extensa costa atlántica, podría beneficiarse de acuerdos de transferencia tecnológica.</p>',
        'extracto' => 'El gigante asiático invertirá 50 mil millones de dólares en plantas desalinizadoras.',
        'categoria' => 'geopolitica',
        'autor' => 'Redacción',
        'imagen' => '',
        'publicado' => true,
        'destacado' => true,
        'fecha' => date('Y-m-d H:i:s', strtotime('-1 day'))
    ],
    [
        'titulo' => 'Nueva tecnología permite riego con 70% menos agua',
        'contenido' => '<p>Investigadores argentinos del INTA desarrollaron un sistema de riego inteligente que reduce el consumo de agua en un 70% manteniendo los mismos niveles de productividad.</p><p>El sistema utiliza sensores de humedad y algoritmos de inteligencia artificial para determinar el momento óptimo de riego.</p><h2>Resultados en campo</h2><p>Las pruebas realizadas en cultivos de soja en Córdoba mostraron resultados prometedores.</p>',
        'extracto' => 'Investigadores del INTA desarrollaron un sistema de riego inteligente.',
        'categoria' => 'agro',
        'autor' => 'Redacción',
        'imagen' => '',
        'publicado' => true,
        'destacado' => true,
        'fecha' => date('Y-m-d H:i:s', strtotime('-2 days'))
    ],
    [
        'titulo' => 'Las aguas premium argentinas conquistan Europa',
        'contenido' => '<p>Las aguas minerales argentinas de alta gama están ganando terreno en restaurantes de lujo europeos.</p><p>Marcas nacionales han logrado posicionarse en cartas de sommeliers de agua en París, Londres y Madrid.</p><h2>Un nuevo nicho</h2><p>El sommelier de agua es una profesión emergente que está transformando la industria.</p>',
        'extracto' => 'Marcas argentinas de agua premium llegan a restaurantes de lujo europeos.',
        'categoria' => 'gourmet',
        'autor' => 'Redacción',
        'imagen' => '',
        'publicado' => true,
        'destacado' => true,
        'fecha' => date('Y-m-d H:i:s', strtotime('-3 days'))
    ],
    [
        'titulo' => 'El futuro del agua es ahora',
        'contenido' => '<p>Vivimos tiempos de transformación. El agua, ese recurso que durante siglos dimos por sentado, se ha convertido en el centro de debates geopolíticos e innovaciones tecnológicas.</p><p>Desde mi perspectiva, estamos ante una oportunidad única para repensar cómo gestionamos este recurso vital.</p><p>La pregunta no es si cambiaremos, sino cuán rápido lo haremos.</p>',
        'extracto' => 'Una reflexión sobre los desafíos y oportunidades en la gestión del agua.',
        'categoria' => 'columnas',
        'autor' => 'Dr. Roberto Fernández',
        'imagen' => '',
        'publicado' => true,
        'destacado' => false,
        'fecha' => date('Y-m-d H:i:s', strtotime('-4 days'))
    ],
    [
        'titulo' => 'Inauguran planta de tratamiento en Mendoza',
        'contenido' => '<p>La provincia de Mendoza inauguró una moderna planta de tratamiento que permitirá reutilizar el 80% del agua residual para riego agrícola.</p><p>La inversión representa un hito en la gestión sustentable del recurso hídrico en la región cuyana.</p>',
        'extracto' => 'Nueva planta permitirá reutilizar el 80% del agua residual.',
        'categoria' => 'sustentabilidad',
        'autor' => 'Redacción',
        'imagen' => '',
        'publicado' => true,
        'destacado' => false,
        'fecha' => date('Y-m-d H:i:s', strtotime('-5 days'))
    ],
    [
        'titulo' => 'Fuentes de agua: tendencia en diseño de interiores',
        'contenido' => '<p>Las fuentes de agua interiores están viviendo un renacimiento en el diseño contemporáneo.</p><p>Arquitectos las incorporan como elementos que aportan tranquilidad y humidifican el ambiente.</p>',
        'extracto' => 'Las fuentes interiores vuelven como tendencia en arquitectura.',
        'categoria' => 'decoracion',
        'autor' => 'Redacción',
        'imagen' => '',
        'publicado' => true,
        'destacado' => false,
        'fecha' => date('Y-m-d H:i:s', strtotime('-6 days'))
    ],
    [
        'titulo' => 'Argentina apuesta por la desalinización solar',
        'contenido' => '<p>Un proyecto piloto en la Patagonia combina energía solar con tecnología de desalinización para proveer agua potable a comunidades rurales aisladas.</p><p>La iniciativa podría replicarse en toda la costa argentina.</p>',
        'extracto' => 'Proyecto piloto en Patagonia combina solar y desalinización.',
        'categoria' => 'tecnologia',
        'autor' => 'Redacción',
        'imagen' => '',
        'publicado' => true,
        'destacado' => false,
        'fecha' => date('Y-m-d H:i:s', strtotime('-7 days'))
    ]
];

foreach ($articulos as $art) {
    $slug = guardarArticulo($art);
    echo "<span class='ok'>✅</span> {$art['titulo']}<br>";
}

// Cargar videos de ejemplo
echo "</div><div class='box'>";
echo "<h3>🎥 Creando videos de ejemplo...</h3>";

$videos = [
    [
        'titulo' => 'Documental: Crisis del agua en el mundo',
        'url' => 'https://www.youtube.com/watch?v=C65iqOSCZOY',
        'duracion' => '15:00',
        'fecha' => date('Y-m-d')
    ],
    [
        'titulo' => 'Tecnología de riego inteligente - Demo',
        'url' => 'https://www.youtube.com/watch?v=1PNX6M_dVsk',
        'duracion' => '8:30',
        'fecha' => date('Y-m-d')
    ],
    [
        'titulo' => 'El ciclo del agua explicado',
        'url' => 'https://www.youtube.com/watch?v=al-do-HGuIk',
        'duracion' => '5:00',
        'fecha' => date('Y-m-d')
    ]
];

guardarVideos($videos);
echo "<span class='ok'>✅</span> 3 videos cargados<br>";

// Resumen
echo "</div><div class='box' style='background:#e8f5e9;'>";
echo "<h3>🎉 ¡Listo!</h3>";
echo "<p>Se crearon <strong>" . count($articulos) . " artículos</strong> y <strong>" . count($videos) . " videos</strong> de ejemplo.</p>";
echo "<p><a href='index.php' style='color:#2d9cdb;'>→ Ver el sitio</a></p>";
echo "<p><a href='admin/' style='color:#2d9cdb;'>→ Ir al panel de administración</a></p>";
echo "</div>";

echo "<p style='color:#c00;font-weight:bold;'>⚠️ IMPORTANTE: Borrá este archivo (crear-datos-ejemplo.php) por seguridad.</p>";
echo "</body></html>";
