<?php
require_once __DIR__ . '/../functions.php';
verificarAdmin();
$articulos = getTodosArticulos();
$videos = getVideos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin | <?= SITE_NAME ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>body{background:#f5f8fa;}</style>
</head>
<body>
    <header class="admin-header">
        <div style="display:flex;align-items:center;gap:15px;">
            <div><h1 style="font-size:1.2rem;margin:0;">Panel de Administración</h1><p style="font-size:0.8rem;opacity:0.7;margin:0;"><?= SITE_NAME ?></p></div>
        </div>
        <nav class="admin-nav">
            <a href="index.php" class="active">Dashboard</a>
            <a href="articulos.php">Artículos</a>
            <a href="videos.php">Videos</a>
            <a href="publicidad.php">Publicidad</a>
            <a href="configuracion.php">Configuración</a>
            <a href="../index.php" target="_blank">Ver sitio</a>
            <a href="logout.php">Salir</a>
        </nav>
    </header>

    <div class="admin-content">
        <h2 style="font-family:var(--font-display);font-size:2rem;margin-bottom:30px;">Bienvenido</h2>

        <div class="stats-grid">
            <div class="stat-card"><h3><?= count($articulos) ?></h3><p>Artículos</p></div>
            <div class="stat-card"><h3><?= count($videos) ?></h3><p>Podcast</p></div>
            <div class="stat-card"><h3><?= count(array_filter($articulos, fn($a) => $a['categoria'] === 'agro')) ?></h3><p>Notas Agro</p></div>
            <div class="stat-card"><h3><?= count(array_filter($articulos, fn($a) => $a['categoria'] === 'columnas')) ?></h3><p>Columnas</p></div>
        </div>

        <div style="display:flex;gap:15px;margin-bottom:30px;">
            <a href="articulos.php?action=new" class="btn-primary" style="display:inline-flex;align-items:center;gap:8px;padding:14px 24px;border-radius:10px;text-decoration:none;">Nuevo Artículo</a>
            <a href="videos.php" class="btn-primary" style="display:inline-flex;align-items:center;gap:8px;padding:14px 24px;border-radius:10px;text-decoration:none;background:#2d6a4f;">Agregar Podcast</a>
        </div>

        <div class="admin-card">
            <h2>Últimos artículos</h2>
            <?php if (empty($articulos)): ?>
            <p style="color:#999;padding:20px 0;">No hay artículos. <a href="articulos.php?action=new">Crear el primero →</a></p>
            <?php else: ?>
            <table class="admin-table">
                <thead><tr><th>Título</th><th>Categoría</th><th>Fecha</th><th>Estado</th><th></th></tr></thead>
                <tbody>
                <?php foreach (array_slice($articulos, 0, 5) as $art): $cat = getCategoria($art['categoria']); ?>
                <tr>
                    <td><strong><?= htmlspecialchars($art['titulo']) ?></strong> <?= !empty($art['destacado']) ? '[Destacado]' : '' ?></td>
                    <td><span style="background:<?= $cat['color'] ?>;color:white;padding:3px 10px;border-radius:15px;font-size:0.75rem;"><?= $cat['nombre'] ?></span></td>
                    <td><?= formatearFecha($art['fecha']) ?></td>
                    <td><span class="status-badge <?= $art['publicado'] ? 'status-published' : 'status-draft' ?>"><?= $art['publicado'] ? 'Publicado' : 'Borrador' ?></span></td>
                    <td><a href="articulos.php?action=edit&slug=<?= $art['slug'] ?>" style="color:#2d9cdb;">Editar</a></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <p style="margin-top:15px;"><a href="articulos.php" style="color:#2d9cdb;">Ver todos →</a></p>
            <?php endif; ?>
        </div>

        <div class="admin-card" style="background:linear-gradient(135deg,#e8f4f8,#fff);">
            <h2>¿Cómo usar el panel?</h2>
            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:20px;margin-top:20px;">
                <div><h4 style="color:#2d9cdb;margin-bottom:10px;">Crear artículos</h4><p style="font-size:0.9rem;color:#666;">Click en "Nuevo Artículo", completá título, contenido, categoría e imagen. Marcá "Destacado" para que aparezca en el hero.</p></div>
                <div><h4 style="color:#2d9cdb;margin-bottom:10px;">Agregar podcast</h4><p style="font-size:0.9rem;color:#666;">Subí tus videos a YouTube y pegá la URL acá. El sistema extrae la miniatura automáticamente.</p></div>
                <div><h4 style="color:#2d6a4f;margin-bottom:10px;">Sección Agro</h4><p style="font-size:0.9rem;color:#666;">Elegí categoría "Agro" para que las notas aparezcan en la sección verde destacada.</p></div>
                <div><h4 style="color:#c9a962;margin-bottom:10px;">Columnas</h4><p style="font-size:0.9rem;color:#666;">Seleccioná "Columnas" como categoría y completá el nombre del autor.</p></div>
            </div>
        </div>
    </div>
</body>
</html>
