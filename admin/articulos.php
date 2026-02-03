<?php
require_once __DIR__ . '/../functions.php';
verificarAdmin();

$action = $_GET['action'] ?? 'list';
$slug = $_GET['slug'] ?? '';
$mensaje = $error = '';

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar fecha programada (no puede ser en el pasado)
    $fechaPublicacion = null;
    if (!empty($_POST['fecha_publicacion'])) {
        $fechaProg = strtotime($_POST['fecha_publicacion']);
        $ahora = time();
        if ($fechaProg < $ahora - 60) { // 60 segundos de margen
            $error = 'La fecha de publicación no puede ser en el pasado';
        } else {
            $fechaPublicacion = $_POST['fecha_publicacion'];
        }
    }

    $datos = [
        'titulo' => $_POST['titulo'] ?? '',
        'contenido' => $_POST['contenido'] ?? '',
        'extracto' => $_POST['extracto'] ?? '',
        'categoria' => $_POST['categoria'] ?? 'mundo',
        'autor' => $_POST['autor'] ?? 'Redacción',
        'publicado' => isset($_POST['publicado']),
        'destacado' => isset($_POST['destacado']),
        'portada' => isset($_POST['portada']),
        'fecha' => $_POST['fecha'] ?? date('Y-m-d H:i:s'),
        'fecha_publicacion' => $fechaPublicacion,
        'imagen' => $_POST['imagen_actual'] ?? ''
    ];
    
    if (!empty($_FILES['imagen']['name'])) {
        $result = subirImagen($_FILES['imagen']);
        if (isset($result['url'])) $datos['imagen'] = $result['url'];
        else $error = $result['error'];
    }
    
    if (!$error) {
        $slugEdit = $_POST['slug_edit'] ?? null;
        $nuevoSlug = guardarArticulo($datos, $slugEdit);
        // Si es nuevo artículo, redirigir a formulario vacío con mensaje de éxito
        // Si es edición, mantener en la página de edición
        if ($slugEdit) {
            header('Location: articulos.php?action=edit&slug=' . $nuevoSlug . '&msg=ok');
        } else {
            header('Location: articulos.php?action=new&msg=ok');
        }
        exit;
    }
}

// Eliminar
if ($action === 'delete' && $slug) {
    eliminarArticulo($slug);
    header('Location: articulos.php?msg=deleted');
    exit;
}

// Obtener artículo para editar
$articulo = ($action === 'edit' && $slug) ? getArticulo($slug) : null;
if ($action === 'edit' && !$articulo) { header('Location: articulos.php'); exit; }

if (isset($_GET['msg'])) {
    $mensaje = $_GET['msg'] === 'ok' ? 'Artículo guardado correctamente' : 'Artículo eliminado';
}

$articulos = getTodosArticulos();
global $CATEGORIAS;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artículos | Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>body{background:#f5f8fa;} .img-preview{max-width:200px;border-radius:8px;margin-top:10px;}
    /* Estilos para Quill */
    .ql-editor { min-height: 400px; font-family: 'Poppins', sans-serif; font-size: 16px; line-height: 1.7; }
    .ql-container { font-size: 16px; }
    .ql-toolbar { background: #f8f9fa; border-radius: 8px 8px 0 0; }
    .ql-container { border-radius: 0 0 8px 8px; background: white; }
    #editor-container { margin-bottom: 15px; }
    </style>
    <!-- Quill Editor (100% gratuito) -->
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
</head>
<body>
    <header class="admin-header">
        <div style="display:flex;align-items:center;gap:15px;">
            <div><h1 style="font-size:1.2rem;margin:0;">Panel de Administración</h1></div>
        </div>
        <nav class="admin-nav">
            <a href="index.php">Dashboard</a>
            <a href="articulos.php" class="active">Artículos</a>
            <a href="videos.php">Podcast</a>
            <a href="publicidad.php">Publicidad</a>
            <a href="configuracion.php">Configuración</a>
            <a href="../index.php" target="_blank">Ver sitio</a>
            <a href="logout.php">Salir</a>
        </nav>
    </header>

    <div class="admin-content">
        <?php if ($mensaje): ?><div class="message success"><?= $mensaje ?></div><?php endif; ?>
        <?php if ($error): ?><div class="message error"><?= $error ?></div><?php endif; ?>

        <?php if ($action === 'new' || $action === 'edit'): ?>
        <div class="admin-card">
            <h2><?= $action === 'new' ? 'Nuevo Artículo' : 'Editar Artículo' ?></h2>
            <form method="POST" enctype="multipart/form-data">
                <?php if ($articulo): ?>
                <input type="hidden" name="slug_edit" value="<?= $slug ?>">
                <input type="hidden" name="imagen_actual" value="<?= $articulo['imagen'] ?? '' ?>">
                <input type="hidden" name="fecha" value="<?= $articulo['fecha'] ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label>Título *</label>
                    <input type="text" name="titulo" required value="<?= htmlspecialchars($articulo['titulo'] ?? '') ?>" placeholder="Ej: China invierte en plantas desalinizadoras">
                </div>
                
                <div class="form-group">
                    <label>Extracto / Bajada</label>
                    <input type="text" name="extracto" value="<?= htmlspecialchars($articulo['extracto'] ?? '') ?>" placeholder="Breve descripción (opcional)">
                </div>
                
                <div class="form-group">
                    <label>Contenido *</label>
                    <div id="editor-container"></div>
                    <textarea name="contenido" id="contenido-hidden" style="display:none;"><?= htmlspecialchars($articulo['contenido'] ?? '') ?></textarea>
                    <small style="color:#666;">Usa la barra de herramientas para dar formato: negrita, cursiva, subtitulos, listas, imagenes, etc.</small>
                </div>
                
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                    <div class="form-group">
                        <label>Categoría *</label>
                        <select name="categoria" required>
                            <?php foreach ($CATEGORIAS as $key => $cat): ?>
                            <option value="<?= $key ?>" <?= (($articulo['categoria'] ?? '') === $key) ? 'selected' : '' ?>><?= $cat['icono'] ?> <?= $cat['nombre'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Autor</label>
                        <input type="text" name="autor" value="<?= htmlspecialchars($articulo['autor'] ?? 'Redacción') ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Imagen destacada</label>
                    <input type="file" name="imagen" accept="image/*">
                    <?php if (!empty($articulo['imagen'])): ?>
                    <img src="<?= $articulo['imagen'] ?>" class="img-preview" alt="">
                    <p style="font-size:0.85rem;color:#666;margin-top:5px;">Imagen actual. Subí una nueva para reemplazarla.</p>
                    <?php endif; ?>
                </div>
                
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                    <div class="form-group">
                        <label>Opciones</label>
                        <div class="checkbox-group">
                            <label><input type="checkbox" name="publicado" <?= (!isset($articulo) || !empty($articulo['publicado'])) ? 'checked' : '' ?>> Publicado</label>
                            <label><input type="checkbox" name="portada" <?= !empty($articulo['portada']) ? 'checked' : '' ?>> Mostrar en Portada</label>
                            <label><input type="checkbox" name="destacado" <?= !empty($articulo['destacado']) ? 'checked' : '' ?>> Destacado</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Programar publicación (opcional)</label>
                        <input type="datetime-local" name="fecha_publicacion" value="<?= !empty($articulo['fecha_publicacion']) ? date('Y-m-d\TH:i', strtotime($articulo['fecha_publicacion'])) : '' ?>" min="<?= date('Y-m-d\TH:i') ?>">
                        <small style="color:#666;display:block;margin-top:5px;">Si no se especifica, se publica inmediatamente. No se permiten fechas pasadas.</small>
                    </div>
                </div>
                
                <div style="display:flex;gap:15px;margin-top:30px;">
                    <button type="submit" class="btn-primary"><?= $action === 'new' ? 'Publicar' : 'Guardar' ?></button>
                    <a href="articulos.php" class="btn-primary btn-secondary">Cancelar</a>
                    <?php if ($articulo): ?>
                    <a href="../articulo.php?s=<?= $slug ?>" target="_blank" class="btn-primary" style="background:#d4edda;color:#155724;">Ver</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
        
        <?php else: ?>
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:30px;">
            <h2 style="font-family:var(--font-display);font-size:2rem;margin:0;">Artículos</h2>
            <a href="articulos.php?action=new" class="btn-primary">Nuevo Artículo</a>
        </div>

        <div class="admin-card">
            <?php if (empty($articulos)): ?>
            <div style="text-align:center;padding:60px 20px;">
                <h3 style="color:#999;">No hay artículos</h3>
                <p style="color:#999;margin-bottom:20px;">Creá tu primer artículo</p>
                <a href="articulos.php?action=new" class="btn-primary">Crear Artículo</a>
            </div>
            <?php else: ?>
            <table class="admin-table">
                <thead><tr><th>Título</th><th>Categoría</th><th>Autor</th><th>Fecha</th><th>Estado</th><th>Acciones</th></tr></thead>
                <tbody>
                <?php foreach ($articulos as $art): $cat = getCategoria($art['categoria']); ?>
                <tr>
                    <td><strong><?= htmlspecialchars($art['titulo']) ?></strong> <?= !empty($art['portada']) ? '<span style="color:#2d9cdb;">[Portada]</span>' : '' ?> <?= !empty($art['destacado']) ? '<span style="color:#c9a962;">[Destacado]</span>' : '' ?></td>
                    <td><span style="background:<?= $cat['color'] ?>;color:white;padding:3px 10px;border-radius:15px;font-size:0.75rem;"><?= $cat['nombre'] ?></span></td>
                    <td><?= htmlspecialchars($art['autor']) ?></td>
                    <td><?= formatearFecha($art['fecha']) ?></td>
                    <td><span class="status-badge <?= $art['publicado'] ? 'status-published' : 'status-draft' ?>"><?= $art['publicado'] ? 'Publicado' : 'Borrador' ?></span></td>
                    <td>
                        <a href="articulos.php?action=edit&slug=<?= $art['slug'] ?>" style="color:#2d9cdb;margin-right:10px;">Editar</a>
                        <a href="../articulo.php?s=<?= $art['slug'] ?>" target="_blank" style="color:#28a745;margin-right:10px;">Ver</a>
                        <a href="articulos.php?action=delete&slug=<?= $art['slug'] ?>" style="color:#dc3545;" onclick="return confirm('¿Eliminar?')">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>

    <?php if ($action === 'new' || $action === 'edit'): ?>
    <script>
    // Inicializar Quill Editor
    var quill = new Quill('#editor-container', {
        theme: 'snow',
        placeholder: 'Escribi el contenido del articulo...',
        modules: {
            toolbar: [
                [{ 'header': [2, 3, 4, false] }],
                [{ 'font': [] }],
                [{ 'size': ['small', false, 'large', 'huge'] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'align': [] }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }],
                ['blockquote'],
                ['link', 'image', 'video'],
                ['clean']
            ]
        }
    });

    // Cargar contenido existente
    var contenidoInicial = document.getElementById('contenido-hidden').value;
    if (contenidoInicial) {
        quill.root.innerHTML = contenidoInicial;
    }

    // Sincronizar con el textarea oculto antes de enviar
    document.querySelector('form').addEventListener('submit', function(e) {
        var contenido = quill.root.innerHTML;
        // Verificar que no esté vacío (solo tags vacíos)
        var textoPlano = quill.getText().trim();
        if (!textoPlano || textoPlano.length === 0) {
            e.preventDefault();
            alert('Por favor, ingresa el contenido del artículo.');
            return false;
        }
        document.getElementById('contenido-hidden').value = contenido;
    });
    </script>
    <?php endif; ?>
</body>
</html>
