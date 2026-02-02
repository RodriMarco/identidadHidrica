<?php
/**
 * IDENTIDAD HÍDRICA - Página de Podcast
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

$videos = getVideos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Podcast | <?= SITE_NAME ?></title>
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
                        <a href="categoria.php?c=sustentabilidad">Sustentabilidad</a>
                        <div class="dropdown-menu">
                            <a href="categoria.php?c=gestion-agua">Gestión del Agua</a>
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
                    <li><a href="videos.php" class="active">Podcast</a></li>
                    <li><a href="nosotros.php">Nosotros</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <div class="category-header" style="background:linear-gradient(135deg,#c62828,#0a1628);">
            <div class="container">
                <h1>Podcast</h1>
                <p>Documentales, entrevistas y contenido audiovisual</p>
            </div>
        </div>

        <section class="section">
            <div class="container">
                <?php if (empty($videos)): ?>
                <div style="text-align:center;padding:60px 20px;">
                    <h2 style="color:#999;margin-bottom:10px;">No hay contenido todavía</h2>
                    <p style="color:#999;margin-bottom:20px;">Pronto subiremos contenido audiovisual.</p>
                    <a href="index.php" class="btn">Volver al inicio</a>
                </div>
                <?php else: ?>

                <!-- Video Principal -->
                <?php $principal = $videos[0]; $videoId = getYoutubeId($principal['url']); ?>
                <div style="margin-bottom:50px;">
                    <div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;border-radius:16px;box-shadow:0 10px 40px rgba(0,0,0,0.2);">
                        <iframe 
                            src="https://www.youtube.com/embed/<?= $videoId ?>" 
                            title="<?= htmlspecialchars($principal['titulo']) ?>"
                            style="position:absolute;top:0;left:0;width:100%;height:100%;border:none;"
                            allowfullscreen>
                        </iframe>
                    </div>
                    <h2 style="font-family:'Cormorant Garamond',serif;font-size:1.8rem;margin-top:20px;color:#0a1628;">
                        <?= htmlspecialchars($principal['titulo']) ?>
                    </h2>
                    <?php if (!empty($principal['duracion'])): ?>
                        <p style="color:#666;">Duración: <?= $principal['duracion'] ?></p>
                    <?php endif; ?>
                </div>

                <!-- Más Videos -->
                <?php if (count($videos) > 1): ?>
                <h3 style="font-family:'Cormorant Garamond',serif;font-size:1.5rem;margin-bottom:25px;color:#0a1628;border-bottom:2px solid #0a1628;padding-bottom:10px;">
                    Más Videos
                </h3>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:25px;">
                    <?php for ($i = 1; $i < count($videos); $i++): $vid = $videos[$i]; $vidId = getYoutubeId($vid['url']); ?>
                    <div class="news-card" style="cursor:pointer;" onclick="window.open('<?= $vid['url'] ?>', '_blank')">
                        <div class="news-card-image" style="position:relative;">
                            <img src="https://img.youtube.com/vi/<?= $vidId ?>/mqdefault.jpg" alt="" style="width:100%;height:180px;object-fit:cover;">
                            <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);background:rgba(0,0,0,0.7);color:white;width:50px;height:50px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:18px;">▶</div>
                            <?php if (!empty($vid['duracion'])): ?>
                                <span style="position:absolute;bottom:10px;right:10px;background:rgba(0,0,0,0.8);color:white;padding:3px 8px;border-radius:4px;font-size:0.8rem;"><?= $vid['duracion'] ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="news-card-content">
                            <h3 class="news-card-title"><?= htmlspecialchars($vid['titulo']) ?></h3>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>
                <?php endif; ?>

                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-bottom">
                <span>© <?= date('Y') ?> <?= SITE_NAME ?>. Todos los derechos reservados.</span>
            </div>
        </div>
    </footer>
</body>
</html>
