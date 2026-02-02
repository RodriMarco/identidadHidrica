<?php
require_once __DIR__ . '/../functions.php';
verificarAdmin();

$mensaje = '';
$videos = getVideos();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'add' && !empty($_POST['titulo']) && !empty($_POST['url'])) {
        array_unshift($videos, [
            'titulo' => $_POST['titulo'],
            'url' => $_POST['url'],
            'duracion' => $_POST['duracion'] ?? '',
            'fecha' => date('Y-m-d')
        ]);
        guardarVideos($videos);
        $mensaje = 'Podcast agregado';
    } elseif ($_POST['action'] === 'delete') {
        array_splice($videos, (int)$_POST['index'], 1);
        guardarVideos($videos);
        $mensaje = 'Podcast eliminado';
    }
    $videos = getVideos();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Podcast | Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body{background:#f5f8fa;}
        .video-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-top:20px;}
        .video-card{background:white;border-radius:12px;overflow:hidden;box-shadow:0 4px 15px rgba(0,0,0,0.05);}
        .video-thumb{aspect-ratio:16/9;background:#000;}
        .video-thumb img{width:100%;height:100%;object-fit:cover;}
        .video-card-body{padding:15px;}
        .video-card-body h4{font-size:1rem;margin-bottom:5px;}
        .video-card-body small{color:#999;}
        .video-actions{padding:0 15px 15px;display:flex;gap:10px;}
        .video-actions a,.video-actions button{padding:6px 12px;border-radius:6px;font-size:0.85rem;text-decoration:none;border:none;cursor:pointer;}
        .btn-view{background:#e8f5e9;color:#388e3c;}
        .btn-delete{background:#ffebee;color:#c62828;}
        @media(max-width:768px){.video-grid{grid-template-columns:1fr;}}
    </style>
</head>
<body>
    <header class="admin-header">
        <div style="display:flex;align-items:center;gap:15px;"><div><h1 style="font-size:1.2rem;margin:0;">Panel de Administración</h1></div></div>
        <nav class="admin-nav">
            <a href="index.php">Dashboard</a>
            <a href="articulos.php">Artículos</a>
            <a href="videos.php" class="active">Podcast</a>
            <a href="publicidad.php">Publicidad</a>
            <a href="configuracion.php">Configuración</a>
            <a href="../index.php" target="_blank">Ver sitio</a>
            <a href="logout.php">Salir</a>
        </nav>
    </header>

    <div class="admin-content">
        <?php if ($mensaje): ?><div class="message success"><?= $mensaje ?></div><?php endif; ?>

        <h2 style="font-family:var(--font-display);font-size:2rem;margin-bottom:30px;">Podcast</h2>

        <div class="admin-card" style="margin-bottom:30px;">
            <h2>Agregar Podcast de YouTube</h2>
            <form method="POST" style="display:grid;grid-template-columns:2fr 3fr 1fr auto;gap:15px;align-items:end;">
                <input type="hidden" name="action" value="add">
                <div class="form-group" style="margin:0;"><label>Título</label><input type="text" name="titulo" placeholder="Título del video" required></div>
                <div class="form-group" style="margin:0;"><label>URL de YouTube</label><input type="url" name="url" placeholder="https://www.youtube.com/watch?v=..." required></div>
                <div class="form-group" style="margin:0;"><label>Duración</label><input type="text" name="duracion" placeholder="15:30"></div>
                <button type="submit" class="btn-primary" style="height:46px;">Agregar</button>
            </form>
        </div>

        <div class="admin-card">
            <h2>Podcasts Cargados (<?= count($videos) ?>)</h2>
            <?php if (empty($videos)): ?>
            <div style="text-align:center;padding:60px 20px;"><h3 style="color:#999;">No hay podcasts</h3><p style="color:#999;">Agregá tu primer podcast de YouTube.</p></div>
            <?php else: ?>
            <div class="video-grid">
                <?php foreach ($videos as $i => $vid): $vidId = getYoutubeId($vid['url']); ?>
                <div class="video-card">
                    <div class="video-thumb"><?php if ($vidId): ?><img src="https://img.youtube.com/vi/<?= $vidId ?>/mqdefault.jpg" alt=""><?php endif; ?></div>
                    <div class="video-card-body"><h4><?= htmlspecialchars($vid['titulo']) ?></h4><small><?= $vid['duracion'] ?? '' ?></small></div>
                    <div class="video-actions">
                        <a href="<?= $vid['url'] ?>" target="_blank" class="btn-view">Ver</a>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar?')">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="index" value="<?= $i ?>">
                            <button type="submit" class="btn-delete">Eliminar</button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
