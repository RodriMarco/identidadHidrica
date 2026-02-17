<?php
/**
 * IDENTIDAD HÍDRICA - Página de Categoría
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

$categoriaSlug = $_GET['c'] ?? '';
$cat = getCategoria($categoriaSlug);
$articulos = getArticulos(null, $categoriaSlug);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $cat['nombre'] ?> | <?= SITE_NAME ?></title>
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
                    <li><a href="categoria.php?c=mundo" <?= $categoriaSlug === 'mundo' ? 'class="active"' : '' ?>>Mundo</a></li>
                    <li class="dropdown">
                        <a href="categoria.php?c=sustentabilidad" <?= $categoriaSlug === 'sustentabilidad' ? 'class="active"' : '' ?>>Sustentabilidad ▾</a>
                        <div class="dropdown-menu">
                            <a href="categoria.php?c=gestion-agua">Gestión del Agua</a>
                            <a href="categoria.php?c=cambio-climatico">Cambio Climático</a>
                            <a href="categoria.php?c=conservacion">Conservación</a>
                            <a href="categoria.php?c=economia-circular">Economía Circular</a>
                        </div>
                    </li>
                    <li class="dropdown">
                        <a href="categoria.php?c=lifestyle" <?= $categoriaSlug === 'lifestyle' ? 'class="active"' : '' ?>>Lifestyle</a>
                        <div class="dropdown-menu">
                            <a href="categoria.php?c=gourmet">Agua Gourmet</a>
                            <a href="categoria.php?c=recreacion">Recreación</a>
                            <a href="categoria.php?c=hidratacion">Hidratación</a>
                            <a href="categoria.php?c=tecnologia">Tecnología</a>
                        </div>
                    </li>
                    <li><a href="categoria.php?c=agro" class="nav-agro <?= $categoriaSlug === 'agro' ? 'active' : '' ?>">Agro</a></li>
                    <li><a href="categoria.php?c=columnas" class="nav-columnas <?= $categoriaSlug === 'columnas' ? 'active' : '' ?>">Columnas</a></li>
                    <li><a href="videos.php">Podcast</a></li>
                    <li><a href="nosotros.php">Nosotros</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <div class="category-header" style="background:linear-gradient(135deg,<?= $cat['color'] ?>,#0a1628);">
            <div class="container">
                <h1><?= $cat['nombre'] ?></h1>
                <p>Todas las noticias de <?= $cat['nombre'] ?></p>
            </div>
        </div>

        <section class="section">
            <div class="container">
                <?php if (empty($articulos)): ?>
                <div style="text-align:center;padding:60px 20px;">
                    <h2 style="color:#999;margin-bottom:10px;">No hay artículos todavía</h2>
                    <p style="color:#999;margin-bottom:20px;">Pronto publicaremos contenido en esta sección.</p>
                    <a href="index.php" class="btn">Volver al inicio</a>
                </div>
                <?php else: ?>
                <div class="news-grid-3">
                    <?php foreach ($articulos as $art): $artCat = getCategoria($art['categoria']); ?>
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
                            <p><?= htmlspecialchars($art['extracto']) ?></p>
                            <span class="meta"><?= tiempoRelativo($art['fecha']) ?> • Por <?= htmlspecialchars($art['autor']) ?></span>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="logo-text">IDENTIDAD HÍDRICA</div>
                    <p><?= SITE_SLOGAN ?></p>
                    <div style="margin-top: 15px;">
                        <a href="mailto:redaccion@identidadhidrica.com.ar" style="display: block; color: #fff; text-decoration: none; margin-bottom: 8px;">redaccion@identidadhidrica.com.ar</a>
                        <a href="mailto:publicidad@identidadhidrica.com.ar" style="display: block; color: #fff; text-decoration: none;">publicidad@identidadhidrica.com.ar</a>
                    </div>
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

    <script>
    // Fade-in en scroll para news cards
    document.addEventListener('DOMContentLoaded', function() {
        const newsCards = document.querySelectorAll('.news-card');
        newsCards.forEach(card => {
            card.classList.add('fade-in-scroll');
        });

        const observerOptions = {
            root: null,
            rootMargin: '0px 0px -50px 0px',
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        newsCards.forEach(card => {
            observer.observe(card);
        });
    });
    </script>
</body>
</html>
