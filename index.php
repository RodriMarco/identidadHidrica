<?php
/**
 * IDENTIDAD HÍDRICA - Página Principal
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

// Obtener artículos para portada (marcados con 'portada') ordenados por fecha
$portada = getArticulosPortada(3);
// Si no hay suficientes marcados para portada, completar con los más recientes
if (count($portada) < 3) {
    $recientes = getArticulos(3 - count($portada));
    $slugsPortada = array_column($portada, 'slug');
    foreach ($recientes as $art) {
        if (!in_array($art['slug'], $slugsPortada)) {
            $portada[] = $art;
        }
        if (count($portada) >= 3) break;
    }
}

// Obtener artículos por categoría ordenados por fecha (más recientes primero)
$mundo = getArticulos(3, 'mundo');
$sustentabilidad = getArticulos(3, 'sustentabilidad');
$agro = getArticulos(3, 'agro');
$columnas = getArticulos(3, 'columnas');

// Para lifestyle, obtener de subcategorías ordenados por fecha
$lifestyleGourmet = getArticulos(1, 'gourmet');
$lifestyleRecreacion = getArticulos(1, 'recreacion');
$lifestyleHidratacion = getArticulos(1, 'hidratacion');
$lifestyleTecnologia = getArticulos(1, 'tecnologia');
$lifestyle = array_merge($lifestyleGourmet, $lifestyleRecreacion, $lifestyleHidratacion);
if (count($lifestyle) < 3 && !empty($lifestyleTecnologia)) {
    $lifestyle = array_merge($lifestyle, $lifestyleTecnologia);
}
$lifestyle = array_slice($lifestyle, 0, 3);
$videos = getVideos(5);
$siteConfig = getSiteConfig();
$pubConfig = getPublicidadConfig();

// Procesar suscripción newsletter
$newsletterMsg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        if (agregarSuscriptor($email)) {
            $newsletterMsg = 'success';
        } else {
            $newsletterMsg = 'exists';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITE_NAME ?> | <?= SITE_SLOGAN ?></title>
    <meta name="description" content="<?= SITE_SLOGAN ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container top-bar-content">
            <span id="top-bar-datetime">Cargando...</span>
            <span id="top-bar-weather">Buenos Aires, Argentina</span>
        </div>
    </div>
    <script>
    // Actualizar fecha y hora
    function actualizarFechaHora() {
        const ahora = new Date();
        const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const fecha = ahora.toLocaleDateString('es-AR', opciones);
        const hora = ahora.toLocaleTimeString('es-AR', { hour: '2-digit', minute: '2-digit' });
        document.getElementById('top-bar-datetime').textContent = fecha.charAt(0).toUpperCase() + fecha.slice(1) + ' | ' + hora;
    }
    actualizarFechaHora();
    setInterval(actualizarFechaHora, 60000);

    // Obtener temperatura (usando API gratuita)
    async function obtenerClima() {
        try {
            // Primero obtener ubicacion aproximada por IP
            const geoRes = await fetch('https://ipapi.co/json/');
            const geoData = await geoRes.json();
            const ciudad = geoData.city || 'Buenos Aires';
            const pais = geoData.country_name || 'Argentina';
            const lat = geoData.latitude || -34.6037;
            const lon = geoData.longitude || -58.3816;

            // Obtener clima de Open-Meteo (gratis, sin API key)
            const climaRes = await fetch(`https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current_weather=true`);
            const climaData = await climaRes.json();
            const temp = Math.round(climaData.current_weather.temperature);

            document.getElementById('top-bar-weather').textContent = `${ciudad}, ${pais} | ${temp}°C`;
        } catch (e) {
            document.getElementById('top-bar-weather').textContent = 'Buenos Aires, Argentina';
        }
    }
    obtenerClima();
    </script>

    <!-- Header -->
    <header class="header">
        <div class="header-main container">
            <a href="index.php" class="logo">
                <div class="logo-icon"></div>
                <div>
                    <div class="logo-text">IDENTIDAD HÍDRICA</div>
                    <div class="logo-tagline"><?= SITE_SLOGAN ?></div>
                </div>
            </a>
            <button class="mobile-menu-btn" onclick="document.getElementById('navList').classList.toggle('active')">☰</button>
        </div>
        <nav class="nav">
            <div class="container">
                <ul class="nav-list" id="navList">
                    <li><a href="index.php" class="active">Portada</a></li>
                    <li><a href="categoria.php?c=mundo">Mundo</a></li>
                    <li class="dropdown">
                        <a href="categoria.php?c=sustentabilidad">Sustentabilidad ▾</a>
                        <div class="dropdown-menu">
                            <a href="categoria.php?c=gestion-agua">Gestión del Agua</a>
                        </div>
                    </li>
                    <li class="dropdown">
                        <a href="categoria.php?c=lifestyle">Lifestyle ▾</a>
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

    <!-- Contenido Principal -->
    <main class="main-content">
        <!-- Hero Section -->
        <?php if (!empty($portada)): ?>
        <section class="hero">
            <div class="container">
                <div class="hero-grid">
                    <?php $d = $portada[0]; $cat = getCategoria($d['categoria']); ?>
                    <article class="hero-main" onclick="location.href='articulo.php?s=<?= $d['slug'] ?>'">
                        <?php if ($d['imagen']): ?>
                            <img src="<?= $d['imagen'] ?>" alt="">
                        <?php else: ?>
                            <div style="background:linear-gradient(135deg,#162447,#0a1628);width:100%;height:100%;"></div>
                        <?php endif; ?>
                        <div class="hero-overlay">
                            <span class="tag" style="background:<?= $cat['color'] ?>"><?= $cat['nombre'] ?></span>
                            <h1><?= htmlspecialchars($d['titulo']) ?></h1>
                            <p><?= htmlspecialchars($d['extracto']) ?></p>
                        </div>
                    </article>
                    <div class="hero-sidebar">
                        <?php for ($i = 1; $i <= 2; $i++): if (isset($portada[$i])): $d = $portada[$i]; $cat = getCategoria($d['categoria']); ?>
                        <article class="hero-card" onclick="location.href='articulo.php?s=<?= $d['slug'] ?>'">
                            <?php if ($d['imagen']): ?>
                                <img src="<?= $d['imagen'] ?>" alt="">
                            <?php else: ?>
                                <div style="background:linear-gradient(135deg,#1f6f8b,#0a1628);width:100%;height:100%;position:absolute;top:0;left:0;"></div>
                            <?php endif; ?>
                            <div class="hero-overlay">
                                <span class="tag" style="background:<?= $cat['color'] ?>"><?= $cat['nombre'] ?></span>
                                <h2><?= htmlspecialchars($d['titulo']) ?></h2>
                            </div>
                        </article>
                        <?php endif; endfor; ?>
                    </div>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- Mundo -->
        <?php if (!empty($mundo)): ?>
        <section class="section">
            <div class="container">
                <div class="section-header">
                    <h2>Mundo</h2>
                    <a href="categoria.php?c=mundo">Ver todas</a>
                </div>
                <div class="news-grid news-grid-3">
                    <?php foreach ($mundo as $art): $cat = getCategoria($art['categoria']); ?>
                    <article class="news-card" onclick="location.href='articulo.php?s=<?= $art['slug'] ?>'">
                        <div class="news-card-image">
                            <?php if ($art['imagen']): ?>
                                <img src="<?= $art['imagen'] ?>" alt="">
                            <?php else: ?>
                                <div style="background:linear-gradient(135deg,<?= $cat['color'] ?>,#0a1628);width:100%;height:100%;"></div>
                            <?php endif; ?>
                            <span class="tag" style="background:<?= $cat['color'] ?>"><?= $cat['nombre'] ?></span>
                        </div>
                        <div class="news-card-content">
                            <h3><?= htmlspecialchars($art['titulo']) ?></h3>
                            <p><?= htmlspecialchars($art['extracto']) ?></p>
                            <span class="meta"><?= tiempoRelativo($art['fecha']) ?></span>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- Publicidad entre Mundo y Agro -->
        <?php
        $zonaMundoAgro = $pubConfig['mundo-agro'] ?? null;
        if ($zonaMundoAgro && !empty($zonaMundoAgro['activo']) && $zonaMundoAgro['cantidad'] > 0):
            $cantidad = (int)$zonaMundoAgro['cantidad'];
            $banners = $zonaMundoAgro['banners'] ?? [];
        ?>
        <section class="ad-zone-between-sections">
            <div class="container">
                <div class="ad-zone-banners ad-count-<?= $cantidad ?>">
                    <?php
                    // Determinar qué posiciones mostrar según la cantidad
                    if ($cantidad == 1) {
                        $posiciones = ['centro'];
                    } elseif ($cantidad == 2) {
                        $posiciones = ['izquierda', 'derecha'];
                    } else {
                        $posiciones = ['izquierda', 'centro', 'derecha'];
                    }

                    foreach ($posiciones as $pos):
                        $banner = $banners[$pos] ?? null;
                        if ($banner && !empty($banner['imagen'])):
                    ?>
                    <a href="<?= htmlspecialchars($banner['url'] ?: '#') ?>" class="ad-zone-banner" target="_blank" rel="noopener" title="<?= htmlspecialchars($banner['titulo'] ?? '') ?>">
                        <img src="<?= htmlspecialchars($banner['imagen']) ?>" alt="<?= htmlspecialchars($banner['titulo'] ?? 'Publicidad') ?>">
                    </a>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- AGRO Section -->
        <?php if (!empty($agro)): ?>
        <section class="agro-section">
            <div class="container">
                <div class="section-header" style="border-color:rgba(255,255,255,0.2);">
                    <h2 style="color:white;">Agro</h2>
                    <a href="categoria.php?c=agro" style="color:#90ee90;">Ver todas</a>
                </div>
                <div class="agro-grid">
                    <?php foreach ($agro as $art): ?>
                    <article class="agro-card" onclick="location.href='articulo.php?s=<?= $art['slug'] ?>'">
                        <span class="tag" style="background:#40916c;">Agro</span>
                        <h3><?= htmlspecialchars($art['titulo']) ?></h3>
                        <p><?= htmlspecialchars($art['extracto']) ?></p>
                        <span class="meta">Por <?= htmlspecialchars($art['autor']) ?></span>
                    </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- Publicidad entre Agro y Sustentabilidad -->
        <?php
        $zonaAgroSustentabilidad = $pubConfig['agro-sustentabilidad'] ?? null;
        if ($zonaAgroSustentabilidad && !empty($zonaAgroSustentabilidad['activo']) && $zonaAgroSustentabilidad['cantidad'] > 0):
            $cantidad = (int)$zonaAgroSustentabilidad['cantidad'];
            $banners = $zonaAgroSustentabilidad['banners'] ?? [];
        ?>
        <section class="ad-zone-between-sections">
            <div class="container">
                <div class="ad-zone-banners ad-count-<?= $cantidad ?>">
                    <?php
                    if ($cantidad == 1) {
                        $posiciones = ['centro'];
                    } elseif ($cantidad == 2) {
                        $posiciones = ['izquierda', 'derecha'];
                    } else {
                        $posiciones = ['izquierda', 'centro', 'derecha'];
                    }

                    foreach ($posiciones as $pos):
                        $banner = $banners[$pos] ?? null;
                        if ($banner && !empty($banner['imagen'])):
                    ?>
                    <a href="<?= htmlspecialchars($banner['url'] ?: '#') ?>" class="ad-zone-banner" target="_blank" rel="noopener" title="<?= htmlspecialchars($banner['titulo'] ?? '') ?>">
                        <img src="<?= htmlspecialchars($banner['imagen']) ?>" alt="<?= htmlspecialchars($banner['titulo'] ?? 'Publicidad') ?>">
                    </a>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- Sustentabilidad -->
        <?php if (!empty($sustentabilidad)): ?>
        <section class="section">
            <div class="container">
                <div class="section-header">
                    <h2>Sustentabilidad</h2>
                    <a href="categoria.php?c=sustentabilidad">Ver todas</a>
                </div>
                <div class="news-grid news-grid-3">
                    <?php foreach ($sustentabilidad as $art): $cat = getCategoria($art['categoria']); ?>
                    <article class="news-card" onclick="location.href='articulo.php?s=<?= $art['slug'] ?>'">
                        <div class="news-card-image">
                            <?php if ($art['imagen']): ?>
                                <img src="<?= $art['imagen'] ?>" alt="">
                            <?php else: ?>
                                <div style="background:linear-gradient(135deg,<?= $cat['color'] ?>,#0a1628);width:100%;height:100%;"></div>
                            <?php endif; ?>
                            <span class="tag" style="background:<?= $cat['color'] ?>"><?= $cat['nombre'] ?></span>
                        </div>
                        <div class="news-card-content">
                            <h3><?= htmlspecialchars($art['titulo']) ?></h3>
                            <p><?= htmlspecialchars($art['extracto']) ?></p>
                            <span class="meta"><?= tiempoRelativo($art['fecha']) ?></span>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- Publicidad entre Sustentabilidad y Columnas -->
        <?php
        $zonaSustentabilidadColumnas = $pubConfig['sustentabilidad-columnas'] ?? null;
        if ($zonaSustentabilidadColumnas && !empty($zonaSustentabilidadColumnas['activo']) && $zonaSustentabilidadColumnas['cantidad'] > 0):
            $cantidad = (int)$zonaSustentabilidadColumnas['cantidad'];
            $banners = $zonaSustentabilidadColumnas['banners'] ?? [];
        ?>
        <section class="ad-zone-between-sections">
            <div class="container">
                <div class="ad-zone-banners ad-count-<?= $cantidad ?>">
                    <?php
                    if ($cantidad == 1) {
                        $posiciones = ['centro'];
                    } elseif ($cantidad == 2) {
                        $posiciones = ['izquierda', 'derecha'];
                    } else {
                        $posiciones = ['izquierda', 'centro', 'derecha'];
                    }

                    foreach ($posiciones as $pos):
                        $banner = $banners[$pos] ?? null;
                        if ($banner && !empty($banner['imagen'])):
                    ?>
                    <a href="<?= htmlspecialchars($banner['url'] ?: '#') ?>" class="ad-zone-banner" target="_blank" rel="noopener" title="<?= htmlspecialchars($banner['titulo'] ?? '') ?>">
                        <img src="<?= htmlspecialchars($banner['imagen']) ?>" alt="<?= htmlspecialchars($banner['titulo'] ?? 'Publicidad') ?>">
                    </a>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- COLUMNAS Section -->
        <?php if (!empty($columnas)): ?>
        <section class="columnas-section">
            <div class="container">
                <div class="section-header" style="border-color:rgba(201,169,98,0.3);">
                    <h2 style="color:#c9a962;">Columnas de Opinión</h2>
                    <a href="categoria.php?c=columnas" style="color:#c9a962;">Ver todas</a>
                </div>
                <div class="columnas-grid">
                    <?php foreach ($columnas as $art): ?>
                    <article class="columna-card" onclick="location.href='articulo.php?s=<?= $art['slug'] ?>'">
                        <div class="columna-avatar"><?= strtoupper(substr($art['autor'], 0, 1)) ?></div>
                        <h4><?= htmlspecialchars($art['autor']) ?></h4>
                        <span>Columnista</span>
                        <h3>"<?= htmlspecialchars($art['titulo']) ?>"</h3>
                    </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- Lifestyle -->
        <?php if (!empty($lifestyle)): ?>
        <section class="section">
            <div class="container">
                <div class="section-header">
                    <h2>Lifestyle</h2>
                    <a href="categoria.php?c=lifestyle">Ver todas</a>
                </div>
                <div class="lifestyle-tabs">
                    <a href="categoria.php?c=gourmet" class="tab">Agua Gourmet</a>
                    <a href="categoria.php?c=recreacion" class="tab">Recreación</a>
                    <a href="categoria.php?c=hidratacion" class="tab">Hidratación</a>
                    <a href="categoria.php?c=tecnologia" class="tab">Tecnología</a>
                </div>
                <div class="news-grid news-grid-3">
                    <?php foreach ($lifestyle as $art): $cat = getCategoria($art['categoria']); ?>
                    <article class="news-card" onclick="location.href='articulo.php?s=<?= $art['slug'] ?>'">
                        <div class="news-card-image">
                            <?php if ($art['imagen']): ?>
                                <img src="<?= $art['imagen'] ?>" alt="">
                            <?php else: ?>
                                <div style="background:linear-gradient(135deg,<?= $cat['color'] ?>,#0a1628);width:100%;height:100%;"></div>
                            <?php endif; ?>
                            <span class="tag" style="background:<?= $cat['color'] ?>"><?= $cat['nombre'] ?></span>
                        </div>
                        <div class="news-card-content">
                            <h3><?= htmlspecialchars($art['titulo']) ?></h3>
                            <p><?= htmlspecialchars($art['extracto']) ?></p>
                            <span class="meta"><?= tiempoRelativo($art['fecha']) ?></span>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- Podcast -->
        <?php if (!empty($videos)): ?>
        <section class="section videos-section">
            <div class="container">
                <div class="section-header">
                    <h2>Podcast</h2>
                    <a href="videos.php">Ver todos</a>
                </div>
                <div class="videos-grid">
                    <div class="video-featured">
                        <?php $vid = $videos[0]; $vidId = getYoutubeId($vid['url']); ?>
                        <iframe src="https://www.youtube.com/embed/<?= $vidId ?>" title="<?= htmlspecialchars($vid['titulo']) ?>" frameborder="0" allowfullscreen></iframe>
                    </div>
                    <div class="video-sidebar">
                        <?php for ($i = 1; $i < min(4, count($videos)); $i++): $vid = $videos[$i]; $vidId = getYoutubeId($vid['url']); ?>
                        <div class="video-item" onclick="window.open('<?= $vid['url'] ?>', '_blank')">
                            <div class="video-thumb">
                                <img src="https://img.youtube.com/vi/<?= $vidId ?>/mqdefault.jpg" alt="">
                                <span>▶</span>
                            </div>
                            <div class="video-info">
                                <h5><?= htmlspecialchars($vid['titulo']) ?></h5>
                                <small><?= $vid['duracion'] ?? '' ?></small>
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- Newsletter -->
        <div class="container">
            <div class="newsletter">
                <div class="newsletter-text">
                    <h3>Suscribite al Newsletter</h3>
                    <p>Las noticias más importantes del agua, directo a tu casilla</p>
                    <?php if ($newsletterMsg === 'success'): ?>
                        <p style="color:#90ee90;margin-top:10px;">¡Gracias por suscribirte!</p>
                    <?php elseif ($newsletterMsg === 'exists'): ?>
                        <p style="color:#ffd700;margin-top:10px;">Ya estás suscripto con este email.</p>
                    <?php endif; ?>
                </div>
                <form class="newsletter-form" method="POST">
                    <input type="email" name="email" placeholder="Tu email" required>
                    <button type="submit">Suscribirme</button>
                </form>
            </div>
        </div>
        </main>

    <!-- Footer -->
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
                    <h4>Multimedia</h4>
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
        // Agregar clase fade-in-scroll a todas las news-card
        const newsCards = document.querySelectorAll('.news-card');
        newsCards.forEach(card => {
            card.classList.add('fade-in-scroll');
        });

        // Intersection Observer para detectar cuando entran en viewport
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
