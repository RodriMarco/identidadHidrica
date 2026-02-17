<?php
/**
 * IDENTIDAD HÍDRICA - Página Nosotros
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

$siteConfig = getSiteConfig();
$contenido = $siteConfig['nosotros_contenido'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nosotros | <?= SITE_NAME ?></title>
    <meta name="description" content="Conocé más sobre <?= SITE_NAME ?> - <?= SITE_SLOGAN ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="top-bar">
        <div class="container top-bar-content">
            <span>Argentina</span>
        </div>
    </div>

    <header class="header">
        <div class="header-main container">
            <a href="index.php" class="logo">
                <div class="logo-icon"></div>
                <div>
                    <div class="logo-text">IDENTIDAD HÍDRICA</div>
                    <div class="logo-tagline"><?= SITE_SLOGAN ?></div>
                </div>
            </a>
        </div>
        <nav class="nav">
            <div class="container">
                <ul class="nav-list">
                    <li><a href="index.php">Portada</a></li>
                    <li><a href="categoria.php?c=mundo">Mundo</a></li>
                    <li class="dropdown">
                        <a href="categoria.php?c=sustentabilidad">Sustentabilidad ▾</a>
                        <div class="dropdown-menu">
                            <a href="categoria.php?c=gestion-agua">Gestión del Agua</a>
                            <a href="categoria.php?c=cambio-climatico">Cambio Climático</a>
                            <a href="categoria.php?c=conservacion">Conservación</a>
                            <a href="categoria.php?c=economia-circular">Economía Circular</a>
                        </div>
                    </li>
                    <li class="dropdown">
                        <a href="categoria.php?c=lifestyle">Lifestyle</a>
                        <div class="dropdown-menu">
                            <a href="categoria.php?c=gourmet">Agua Gourmet</a>
                            <a href="categoria.php?c=recreacion">Recreación</a>
                            <a href="categoria.php?c=hidratacion">Hidratación</a>
                            <a href="categoria.php?c=tecnologia">Tecnología</a>
                        </div>
                    </li>
                    <li><a href="categoria.php?c=agro" class="nav-agro">Agro</a></li>
                    <li><a href="categoria.php?c=columnas" class="nav-columnas">Columnas</a></li>
                    <li><a href="videos.php">Podcast</a></li>
                    <li><a href="nosotros.php" class="active">Nosotros</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <div class="category-header" style="background:linear-gradient(135deg,#2d9cdb,#0a1628);">
            <div class="container">
                <h1>Nosotros</h1>
                <p>Conocé más sobre Identidad Hídrica</p>
            </div>
        </div>

        <section class="section">
            <div class="container">
                <div class="article-content" style="max-width:900px;margin:0 auto;">
                    <?php if (!empty($contenido)): ?>
                        <?= $contenido ?>
                    <?php else: ?>
                        <div style="text-align:center;padding:60px 20px;">
                            <h2 style="color:#999;margin-bottom:20px;">Contenido en construcción</h2>
                            <p style="color:#666;">Esta sección será completada próximamente desde el panel de administración.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="logo-text">IDENTIDAD HÍDRICA</div>
                    <p><?= SITE_SLOGAN ?></p>
                </div>
                <div class="footer-column">
                    <h4>Secciones</h4>
                    <ul>
                        <li><a href="categoria.php?c=mundo">Mundo</a></li>
                        <li><a href="categoria.php?c=sustentabilidad">Sustentabilidad</a></li>
                        <li><a href="categoria.php?c=agro">Agro</a></li>
                        <li><a href="categoria.php?c=columnas">Columnas</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4>Lifestyle</h4>
                    <ul>
                        <li><a href="categoria.php?c=gourmet">Agua Gourmet</a></li>
                        <li><a href="categoria.php?c=recreacion">Recreación</a></li>
                        <li><a href="categoria.php?c=hidratacion">Hidratación</a></li>
                        <li><a href="categoria.php?c=tecnologia">Tecnología</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4>Contacto</h4>
                    <ul>
                        <li><a href="mailto:redaccion@identidadhidrica.com.ar">redaccion@identidadhidrica.com.ar</a></li>
                        <li><a href="mailto:publicidad@identidadhidrica.com.ar">publicidad@identidadhidrica.com.ar</a></li>
                        <li><a href="videos.php">Podcast</a></li>
                        <li><a href="nosotros.php">Nosotros</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <span>© <?= date('Y') ?> <?= SITE_NAME ?>. Todos los derechos reservados.</span>
            </div>
        </div>
    </footer>
</body>
</html>
