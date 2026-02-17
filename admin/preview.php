<?php
/**
 * IDENTIDAD HÍDRICA - Vista Previa de Artículo
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

// Obtener datos del formulario
$articulo = [
    'titulo' => $_POST['titulo'] ?? 'Sin título',
    'extracto' => $_POST['extracto'] ?? '',
    'contenido' => $_POST['contenido'] ?? '',
    'categoria' => $_POST['categoria'] ?? 'mundo',
    'autor' => $_POST['autor'] ?? 'Redacción',
    'imagen' => $_POST['imagen'] ?? '',
    'fecha' => date('Y-m-d H:i:s')
];

$cat = getCategoria($articulo['categoria']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista Previa: <?= htmlspecialchars($articulo['titulo']) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body { background: #f5f8fa; }
        .preview-banner {
            background: #ff9800;
            color: white;
            padding: 15px;
            text-align: center;
            font-weight: 600;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .preview-banner .close-btn {
            float: right;
            background: white;
            color: #ff9800;
            border: none;
            padding: 5px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
        }
        .preview-banner .close-btn:hover {
            background: #f0f0f0;
        }
    </style>
</head>
<body>
    <div class="preview-banner">
        <button class="close-btn" onclick="window.close()">Cerrar Vista Previa</button>
        📄 VISTA PREVIA - Los cambios no están guardados
    </div>

    <div class="top-bar">
        <div class="container top-bar-content">
            <span>Vista Previa</span>
        </div>
    </div>

    <header class="header">
        <div class="header-main container">
            <a href="#" class="logo">
                <div class="logo-icon">💧</div>
                <div>
                    <div class="logo-text">IDENTIDAD HÍDRICA</div>
                    <div class="logo-tagline"><?= SITE_SLOGAN ?></div>
                </div>
            </a>
        </div>
        <nav class="nav">
            <div class="container">
                <ul class="nav-list">
                    <li><a href="#">Portada</a></li>
                    <li><a href="#">Mundo</a></li>
                    <li><a href="#">Sustentabilidad</a></li>
                    <li><a href="#">Lifestyle</a></li>
                    <li><a href="#">Agro</a></li>
                    <li><a href="#">Columnas</a></li>
                    <li><a href="#">Podcast</a></li>
                    <li><a href="#">Nosotros</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <article class="article-page">
            <div class="container">
                <header class="article-header">
                    <span class="tag" style="background:<?= $cat['color'] ?>">
                        <?= $cat['nombre'] ?>
                    </span>
                    <h1><?= htmlspecialchars($articulo['titulo']) ?></h1>
                    <?php if ($articulo['extracto']): ?>
                    <p style="font-size: 1.2rem; color: #666; margin: 20px 0;"><?= htmlspecialchars($articulo['extracto']) ?></p>
                    <?php endif; ?>
                    <p class="meta">Por <strong><?= htmlspecialchars($articulo['autor']) ?></strong> • <?= formatearFecha($articulo['fecha']) ?></p>
                </header>

                <?php if ($articulo['imagen']): ?>
                <div class="article-image">
                    <img src="../<?= $articulo['imagen'] ?>" alt="">
                </div>
                <?php endif; ?>

                <div class="article-content">
                    <?= $articulo['contenido'] ?>
                </div>

                <div style="margin-top: 40px; padding: 20px; background: #fff3cd; border-radius: 10px; border: 2px dashed #ff9800;">
                    <p style="margin: 0; color: #856404; font-weight: 600;">
                        ⚠️ Esta es una vista previa. El artículo no ha sido publicado aún.
                    </p>
                </div>
            </div>
        </article>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-bottom">
                <span>Vista Previa - © <?= date('Y') ?> <?= SITE_NAME ?></span>
            </div>
        </div>
    </footer>
</body>
</html>
