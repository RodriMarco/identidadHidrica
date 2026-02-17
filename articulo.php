<?php
/**
 * IDENTIDAD HÍDRICA - Página de Artículo
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

$slug = $_GET['s'] ?? '';
$articulo = getArticulo($slug);

if (!$articulo) {
    header('Location: index.php');
    exit;
}

$cat = getCategoria($articulo['categoria']);
$relacionados = array_filter(getArticulos(4, $articulo['categoria']), fn($a) => $a['slug'] !== $slug);
$relacionados = array_slice($relacionados, 0, 3);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($articulo['titulo']) ?> | <?= SITE_NAME ?></title>
    <meta name="description" content="<?= htmlspecialchars($articulo['extracto']) ?>">
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
                    <li><a href="nosotros.php">Nosotros</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <article class="article-page">
            <div class="container">
                <header class="article-header">
                    <a href="categoria.php?c=<?= $articulo['categoria'] ?>" class="tag" style="background:<?= $cat['color'] ?>;text-decoration:none;">
                        <?= $cat['nombre'] ?>
                    </a>
                    <h1><?= htmlspecialchars($articulo['titulo']) ?></h1>
                    <p class="meta">Por <strong><?= htmlspecialchars($articulo['autor']) ?></strong> • <?= formatearFecha($articulo['fecha']) ?></p>
                </header>

                <?php if ($articulo['imagen']): ?>
                <div class="article-image">
                    <img src="<?= $articulo['imagen'] ?>" alt="">
                </div>
                <?php endif; ?>

                <div class="article-content">
                    <?= $articulo['contenido'] ?>
                </div>
            </div>
        </article>

        <?php if (!empty($relacionados)): ?>
        <section class="section" style="background:#f5f8fa;">
            <div class="container">
                <div class="section-header">
                    <h2>Artículos Relacionados</h2>
                </div>
                <div class="news-grid" style="grid-template-columns:repeat(3,1fr);">
                    <?php foreach ($relacionados as $art): $artCat = getCategoria($art['categoria']); ?>
                    <article class="news-card" onclick="location.href='articulo.php?s=<?= $art['slug'] ?>'">
                        <div class="news-card-image">
                            <?php if ($art['imagen']): ?>
                                <img src="<?= $art['imagen'] ?>" alt="">
                            <?php else: ?>
                                <div style="background:linear-gradient(135deg,<?= $artCat['color'] ?>,#0a1628);width:100%;height:100%;"></div>
                            <?php endif; ?>
                            <span class="tag" style="background:<?= $artCat['color'] ?>"><?= $artCat['nombre'] ?></span>
                        </div>
                        <div class="news-card-content">
                            <h3><?= htmlspecialchars($art['titulo']) ?></h3>
                            <span class="meta"><?= tiempoRelativo($art['fecha']) ?></span>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>
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
