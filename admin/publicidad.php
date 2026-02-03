<?php
require_once __DIR__ . '/../functions.php';
verificarAdmin();

$mensaje = '';
$config = getPublicidadConfig();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'save_config') {
        // Guardar configuración de la zona
        $zona = $_POST['zona'];
        $cantidad = (int)$_POST['cantidad'];

        $config[$zona] = [
            'cantidad' => $cantidad,
            'activo' => isset($_POST['activo']),
            'banners' => []
        ];

        // Procesar cada banner según la cantidad seleccionada
        for ($i = 1; $i <= $cantidad; $i++) {
            $posLabel = $cantidad == 1 ? 'centro' : ($cantidad == 2 ? ($i == 1 ? 'izquierda' : 'derecha') : ($i == 1 ? 'izquierda' : ($i == 2 ? 'centro' : 'derecha')));

            $banner = [
                'titulo' => $_POST["titulo_$i"] ?? '',
                'url' => $_POST["url_$i"] ?? '#',
                'imagen' => $_POST["imagen_actual_$i"] ?? ''
            ];

            // Subir nueva imagen si se proporcionó
            if (!empty($_FILES["imagen_$i"]['name'])) {
                $result = subirImagen($_FILES["imagen_$i"]);
                if (isset($result['url'])) {
                    $banner['imagen'] = $result['url'];
                }
            }

            $config[$zona]['banners'][$posLabel] = $banner;
        }

        guardarPublicidadConfig($config);
        $mensaje = 'Configuración guardada correctamente';
        $config = getPublicidadConfig();
    }
}

// Configuración por defecto para las zonas publicitarias
$zonaMundoAgro = $config['mundo-agro'] ?? ['cantidad' => 0, 'activo' => false, 'banners' => []];
$zonaAgroSustentabilidad = $config['agro-sustentabilidad'] ?? ['cantidad' => 0, 'activo' => false, 'banners' => []];
$zonaSustentabilidadColumnas = $config['sustentabilidad-columnas'] ?? ['cantidad' => 0, 'activo' => false, 'banners' => []];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicidad | Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body{background:#f5f8fa;}
        .banner-config { display: none; padding: 20px; background: #f8f9fa; border-radius: 8px; margin-top: 15px; }
        .banner-config.active { display: block; }
        .banner-slot { background: white; padding: 20px; border-radius: 8px; margin-bottom: 15px; border: 1px solid #e0e0e0; }
        .banner-slot h4 { margin: 0 0 15px 0; color: #1976d2; font-family: var(--font-display); }
        .banner-preview { max-width: 200px; max-height: 100px; border-radius: 4px; margin-top: 10px; }
        .size-guide { display: flex; gap: 20px; margin-top: 15px; flex-wrap: wrap; }
        .size-item { text-align: center; padding: 15px; background: #fff; border-radius: 8px; border: 2px dashed #ddd; }
        .size-item.active { border-color: #1976d2; background: #e3f2fd; }
        .size-item strong { display: block; margin-top: 10px; }

        /* Acordeon */
        .accordion-card { margin-bottom: 15px; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .accordion-header { display: flex; justify-content: space-between; align-items: center; padding: 20px 25px; background: white; cursor: pointer; border: none; width: 100%; text-align: left; transition: background 0.2s; }
        .accordion-header:hover { background: #f8f9fa; }
        .accordion-header h3 { margin: 0; font-family: var(--font-display); font-size: 1.2rem; color: #0a1628; display: flex; align-items: center; gap: 10px; }
        .accordion-header .status-badge { font-size: 0.75rem; padding: 4px 12px; border-radius: 20px; font-weight: 500; }
        .accordion-header .status-badge.active { background: #d4edda; color: #155724; }
        .accordion-header .status-badge.inactive { background: #f8d7da; color: #721c24; }
        .accordion-arrow { font-size: 1.2rem; transition: transform 0.3s ease; color: #666; }
        .accordion-card.open .accordion-arrow { transform: rotate(180deg); }
        .accordion-content { display: none; padding: 25px; background: #fafbfc; border-top: 1px solid #eee; }
        .accordion-card.open .accordion-content { display: block; }

        /* Toggle Switch */
        .toggle-switch { position: relative; display: inline-block; cursor: pointer; }
        .toggle-switch input { opacity: 0; width: 0; height: 0; position: absolute; }
        .toggle-slider { position: relative; display: inline-block; width: 50px; height: 26px; background: #dc3545; border-radius: 26px; transition: 0.3s; }
        .toggle-slider:before { content: ""; position: absolute; width: 20px; height: 20px; left: 3px; top: 3px; background: white; border-radius: 50%; transition: 0.3s; box-shadow: 0 2px 4px rgba(0,0,0,0.2); }
        .toggle-switch input:checked + .toggle-slider { background: #28a745; }
        .toggle-switch input:checked + .toggle-slider:before { transform: translateX(24px); }
    </style>
</head>
<body>
    <header class="admin-header">
        <div style="display:flex;align-items:center;gap:15px;"><div><h1 style="font-size:1.2rem;margin:0;">Panel de Administracion</h1></div></div>
        <nav class="admin-nav">
            <a href="index.php">Dashboard</a>
            <a href="articulos.php">Articulos</a>
            <a href="videos.php">Podcast</a>
            <a href="publicidad.php" class="active">Publicidad</a>
            <a href="configuracion.php">Configuracion</a>
            <a href="../index.php" target="_blank">Ver sitio</a>
            <a href="logout.php">Salir</a>
        </nav>
    </header>

    <div class="admin-content">
        <?php if ($mensaje): ?><div class="message success"><?= $mensaje ?></div><?php endif; ?>

        <h2 style="font-family:var(--font-display);font-size:2rem;margin-bottom:30px;">Publicidad</h2>
        <p style="color:#666;margin-bottom:25px;">Configura los banners publicitarios que aparecen entre las secciones de la portada. Haz clic en cada seccion para expandir y configurar.</p>

        <!-- Zona: Entre Mundo y Agro -->
        <div class="accordion-card" id="accordion-mundo-agro">
            <div class="accordion-header" onclick="toggleAccordion('mundo-agro')">
                <h3>
                    Entre Secciones: Mundo - Agro
                    <span class="status-badge <?= !empty($zonaMundoAgro['activo']) ? 'active' : 'inactive' ?>"><?= !empty($zonaMundoAgro['activo']) ? 'Activo' : 'Inactivo' ?></span>
                </h3>
                <span class="accordion-arrow">▼</span>
            </div>
            <div class="accordion-content">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="save_config">
                    <input type="hidden" name="zona" value="mundo-agro">

                    <div style="display:flex;gap:30px;align-items:center;margin-bottom:25px;flex-wrap:wrap;">
                        <div class="form-group" style="margin:0;">
                            <label>Cantidad de banners</label>
                            <select name="cantidad" id="cantidad-mundo-agro" onchange="actualizarBanners('mundo-agro', this.value)" style="width:200px;">
                                <option value="0" <?= $zonaMundoAgro['cantidad'] == 0 ? 'selected' : '' ?>>Sin publicidad</option>
                                <option value="1" <?= $zonaMundoAgro['cantidad'] == 1 ? 'selected' : '' ?>>1 banner (970x250)</option>
                                <option value="2" <?= $zonaMundoAgro['cantidad'] == 2 ? 'selected' : '' ?>>2 banners (450x250 c/u)</option>
                                <option value="3" <?= $zonaMundoAgro['cantidad'] == 3 ? 'selected' : '' ?>>3 banners (350x250 c/u)</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin:0;">
                            <label style="margin-bottom:8px;display:block;">Mostrar en el sitio</label>
                            <label class="toggle-switch">
                                <input type="checkbox" name="activo" <?= !empty($zonaMundoAgro['activo']) ? 'checked' : '' ?>>
                                <span class="toggle-slider"></span>
                                                            </label>
                        </div>
                    </div>

                    <!-- Guía de tamaños -->
                    <div class="size-guide" id="size-guide-mundo-agro">
                    <div class="size-item" data-cant="1">
                        <div style="background:#ddd;width:194px;height:50px;margin:0 auto;border-radius:4px;"></div>
                        <strong>1 Banner</strong>
                        <small>970 x 250 px</small>
                    </div>
                    <div class="size-item" data-cant="2">
                        <div style="display:flex;gap:10px;justify-content:center;">
                            <div style="background:#ddd;width:90px;height:50px;border-radius:4px;"></div>
                            <div style="background:#ddd;width:90px;height:50px;border-radius:4px;"></div>
                        </div>
                        <strong>2 Banners</strong>
                        <small>450 x 250 px c/u</small>
                    </div>
                    <div class="size-item" data-cant="3">
                        <div style="display:flex;gap:5px;justify-content:center;">
                            <div style="background:#ddd;width:60px;height:45px;border-radius:4px;"></div>
                            <div style="background:#ddd;width:60px;height:45px;border-radius:4px;"></div>
                            <div style="background:#ddd;width:60px;height:45px;border-radius:4px;"></div>
                        </div>
                        <strong>3 Banners</strong>
                        <small>350 x 250 px c/u</small>
                    </div>
                </div>

                <!-- Configuración de banners -->
                <div id="banners-mundo-agro">
                    <!-- Banner 1 -->
                    <div class="banner-config <?= $zonaMundoAgro['cantidad'] >= 1 ? 'active' : '' ?>" id="banner-mundo-agro-1">
                        <div class="banner-slot">
                            <h4>Banner <?= $zonaMundoAgro['cantidad'] == 1 ? '(Centro - 970x250)' : ($zonaMundoAgro['cantidad'] == 2 ? 'Izquierda (450x250)' : 'Izquierda (350x250)') ?></h4>
                            <?php
                            $b1 = $zonaMundoAgro['banners']['izquierda'] ?? $zonaMundoAgro['banners']['centro'] ?? ['titulo' => '', 'url' => '', 'imagen' => ''];
                            ?>
                            <input type="hidden" name="imagen_actual_1" value="<?= htmlspecialchars($b1['imagen']) ?>">
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
                                <div class="form-group" style="margin:0;">
                                    <label>Titulo/Anunciante</label>
                                    <input type="text" name="titulo_1" value="<?= htmlspecialchars($b1['titulo']) ?>" placeholder="Nombre del anunciante">
                                </div>
                                <div class="form-group" style="margin:0;">
                                    <label>URL de destino</label>
                                    <input type="url" name="url_1" value="<?= htmlspecialchars($b1['url']) ?>" placeholder="https://...">
                                </div>
                            </div>
                            <div class="form-group" style="margin-top:15px;">
                                <label>Imagen (PNG con transparencia recomendado)</label>
                                <input type="file" name="imagen_1" accept="image/*">
                                <?php if (!empty($b1['imagen'])): ?>
                                <img src="../<?= $b1['imagen'] ?>" class="banner-preview" alt="Preview">
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Banner 2 -->
                    <div class="banner-config <?= $zonaMundoAgro['cantidad'] >= 2 ? 'active' : '' ?>" id="banner-mundo-agro-2">
                        <div class="banner-slot">
                            <h4>Banner <?= $zonaMundoAgro['cantidad'] == 2 ? 'Derecha (450x250)' : 'Centro (350x250)' ?></h4>
                            <?php
                            // Con 2 banners: izq y der. Con 3 banners: izq, centro, der
                            $b2 = ($zonaMundoAgro['cantidad'] == 3)
                                ? ($zonaMundoAgro['banners']['centro'] ?? ['titulo' => '', 'url' => '', 'imagen' => ''])
                                : ($zonaMundoAgro['banners']['derecha'] ?? ['titulo' => '', 'url' => '', 'imagen' => '']);
                            ?>
                            <input type="hidden" name="imagen_actual_2" value="<?= htmlspecialchars($b2['imagen']) ?>">
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
                                <div class="form-group" style="margin:0;">
                                    <label>Titulo/Anunciante</label>
                                    <input type="text" name="titulo_2" value="<?= htmlspecialchars($b2['titulo']) ?>" placeholder="Nombre del anunciante">
                                </div>
                                <div class="form-group" style="margin:0;">
                                    <label>URL de destino</label>
                                    <input type="url" name="url_2" value="<?= htmlspecialchars($b2['url']) ?>" placeholder="https://...">
                                </div>
                            </div>
                            <div class="form-group" style="margin-top:15px;">
                                <label>Imagen (PNG con transparencia recomendado)</label>
                                <input type="file" name="imagen_2" accept="image/*">
                                <?php if (!empty($b2['imagen'])): ?>
                                <img src="../<?= $b2['imagen'] ?>" class="banner-preview" alt="Preview">
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Banner 3 -->
                    <div class="banner-config <?= $zonaMundoAgro['cantidad'] >= 3 ? 'active' : '' ?>" id="banner-mundo-agro-3">
                        <div class="banner-slot">
                            <h4>Banner Derecha (350x250)</h4>
                            <?php
                            $b3 = $zonaMundoAgro['banners']['derecha'] ?? ['titulo' => '', 'url' => '', 'imagen' => ''];
                            ?>
                            <input type="hidden" name="imagen_actual_3" value="<?= htmlspecialchars($b3['imagen']) ?>">
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
                                <div class="form-group" style="margin:0;">
                                    <label>Titulo/Anunciante</label>
                                    <input type="text" name="titulo_3" value="<?= htmlspecialchars($b3['titulo']) ?>" placeholder="Nombre del anunciante">
                                </div>
                                <div class="form-group" style="margin:0;">
                                    <label>URL de destino</label>
                                    <input type="url" name="url_3" value="<?= htmlspecialchars($b3['url']) ?>" placeholder="https://...">
                                </div>
                            </div>
                            <div class="form-group" style="margin-top:15px;">
                                <label>Imagen (PNG con transparencia recomendado)</label>
                                <input type="file" name="imagen_3" accept="image/*">
                                <?php if (!empty($b3['imagen'])): ?>
                                <img src="../<?= $b3['imagen'] ?>" class="banner-preview" alt="Preview">
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                    <button type="submit" class="btn-primary" style="margin-top:20px;">Guardar Configuracion</button>
                </form>
            </div>
        </div>

        <!-- Zona: Entre Agro y Sustentabilidad -->
        <div class="accordion-card" id="accordion-agro-sustentabilidad">
            <div class="accordion-header" onclick="toggleAccordion('agro-sustentabilidad')">
                <h3>
                    Entre Secciones: Agro - Sustentabilidad
                    <span class="status-badge <?= !empty($zonaAgroSustentabilidad['activo']) ? 'active' : 'inactive' ?>"><?= !empty($zonaAgroSustentabilidad['activo']) ? 'Activo' : 'Inactivo' ?></span>
                </h3>
                <span class="accordion-arrow">▼</span>
            </div>
            <div class="accordion-content">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="save_config">
                    <input type="hidden" name="zona" value="agro-sustentabilidad">

                    <div style="display:flex;gap:30px;align-items:center;margin-bottom:25px;flex-wrap:wrap;">
                        <div class="form-group" style="margin:0;">
                            <label>Cantidad de banners</label>
                            <select name="cantidad" id="cantidad-agro-sustentabilidad" onchange="actualizarBanners('agro-sustentabilidad', this.value)" style="width:200px;">
                                <option value="0" <?= $zonaAgroSustentabilidad['cantidad'] == 0 ? 'selected' : '' ?>>Sin publicidad</option>
                                <option value="1" <?= $zonaAgroSustentabilidad['cantidad'] == 1 ? 'selected' : '' ?>>1 banner (970x250)</option>
                                <option value="2" <?= $zonaAgroSustentabilidad['cantidad'] == 2 ? 'selected' : '' ?>>2 banners (450x250 c/u)</option>
                                <option value="3" <?= $zonaAgroSustentabilidad['cantidad'] == 3 ? 'selected' : '' ?>>3 banners (350x250 c/u)</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin:0;">
                            <label style="margin-bottom:8px;display:block;">Mostrar en el sitio</label>
                            <label class="toggle-switch">
                                <input type="checkbox" name="activo" <?= !empty($zonaAgroSustentabilidad['activo']) ? 'checked' : '' ?>>
                                <span class="toggle-slider"></span>
                                                            </label>
                        </div>
                    </div>

                    <!-- Guía de tamaños -->
                    <div class="size-guide" id="size-guide-agro-sustentabilidad">
                    <div class="size-item" data-cant="1">
                        <div style="background:#ddd;width:194px;height:50px;margin:0 auto;border-radius:4px;"></div>
                        <strong>1 Banner</strong>
                        <small>970 x 250 px</small>
                    </div>
                    <div class="size-item" data-cant="2">
                        <div style="display:flex;gap:10px;justify-content:center;">
                            <div style="background:#ddd;width:90px;height:50px;border-radius:4px;"></div>
                            <div style="background:#ddd;width:90px;height:50px;border-radius:4px;"></div>
                        </div>
                        <strong>2 Banners</strong>
                        <small>450 x 250 px c/u</small>
                    </div>
                    <div class="size-item" data-cant="3">
                        <div style="display:flex;gap:5px;justify-content:center;">
                            <div style="background:#ddd;width:60px;height:45px;border-radius:4px;"></div>
                            <div style="background:#ddd;width:60px;height:45px;border-radius:4px;"></div>
                            <div style="background:#ddd;width:60px;height:45px;border-radius:4px;"></div>
                        </div>
                        <strong>3 Banners</strong>
                        <small>350 x 250 px c/u</small>
                    </div>
                </div>

                <!-- Configuración de banners -->
                <div id="banners-agro-sustentabilidad">
                    <!-- Banner 1 -->
                    <div class="banner-config <?= $zonaAgroSustentabilidad['cantidad'] >= 1 ? 'active' : '' ?>" id="banner-agro-sustentabilidad-1">
                        <div class="banner-slot">
                            <h4>Banner <?= $zonaAgroSustentabilidad['cantidad'] == 1 ? '(Centro - 970x250)' : ($zonaAgroSustentabilidad['cantidad'] == 2 ? 'Izquierda (450x250)' : 'Izquierda (350x250)') ?></h4>
                            <?php
                            $b1 = $zonaAgroSustentabilidad['banners']['izquierda'] ?? $zonaAgroSustentabilidad['banners']['centro'] ?? ['titulo' => '', 'url' => '', 'imagen' => ''];
                            ?>
                            <input type="hidden" name="imagen_actual_1" value="<?= htmlspecialchars($b1['imagen']) ?>">
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
                                <div class="form-group" style="margin:0;">
                                    <label>Titulo/Anunciante</label>
                                    <input type="text" name="titulo_1" value="<?= htmlspecialchars($b1['titulo']) ?>" placeholder="Nombre del anunciante">
                                </div>
                                <div class="form-group" style="margin:0;">
                                    <label>URL de destino</label>
                                    <input type="url" name="url_1" value="<?= htmlspecialchars($b1['url']) ?>" placeholder="https://...">
                                </div>
                            </div>
                            <div class="form-group" style="margin-top:15px;">
                                <label>Imagen (PNG con transparencia recomendado)</label>
                                <input type="file" name="imagen_1" accept="image/*">
                                <?php if (!empty($b1['imagen'])): ?>
                                <img src="../<?= $b1['imagen'] ?>" class="banner-preview" alt="Preview">
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Banner 2 -->
                    <div class="banner-config <?= $zonaAgroSustentabilidad['cantidad'] >= 2 ? 'active' : '' ?>" id="banner-agro-sustentabilidad-2">
                        <div class="banner-slot">
                            <h4>Banner <?= $zonaAgroSustentabilidad['cantidad'] == 2 ? 'Derecha (450x250)' : 'Centro (350x250)' ?></h4>
                            <?php
                            $b2 = ($zonaAgroSustentabilidad['cantidad'] == 3)
                                ? ($zonaAgroSustentabilidad['banners']['centro'] ?? ['titulo' => '', 'url' => '', 'imagen' => ''])
                                : ($zonaAgroSustentabilidad['banners']['derecha'] ?? ['titulo' => '', 'url' => '', 'imagen' => '']);
                            ?>
                            <input type="hidden" name="imagen_actual_2" value="<?= htmlspecialchars($b2['imagen']) ?>">
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
                                <div class="form-group" style="margin:0;">
                                    <label>Titulo/Anunciante</label>
                                    <input type="text" name="titulo_2" value="<?= htmlspecialchars($b2['titulo']) ?>" placeholder="Nombre del anunciante">
                                </div>
                                <div class="form-group" style="margin:0;">
                                    <label>URL de destino</label>
                                    <input type="url" name="url_2" value="<?= htmlspecialchars($b2['url']) ?>" placeholder="https://...">
                                </div>
                            </div>
                            <div class="form-group" style="margin-top:15px;">
                                <label>Imagen (PNG con transparencia recomendado)</label>
                                <input type="file" name="imagen_2" accept="image/*">
                                <?php if (!empty($b2['imagen'])): ?>
                                <img src="../<?= $b2['imagen'] ?>" class="banner-preview" alt="Preview">
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Banner 3 -->
                    <div class="banner-config <?= $zonaAgroSustentabilidad['cantidad'] >= 3 ? 'active' : '' ?>" id="banner-agro-sustentabilidad-3">
                        <div class="banner-slot">
                            <h4>Banner Derecha (350x250)</h4>
                            <?php
                            $b3 = $zonaAgroSustentabilidad['banners']['derecha'] ?? ['titulo' => '', 'url' => '', 'imagen' => ''];
                            ?>
                            <input type="hidden" name="imagen_actual_3" value="<?= htmlspecialchars($b3['imagen']) ?>">
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
                                <div class="form-group" style="margin:0;">
                                    <label>Titulo/Anunciante</label>
                                    <input type="text" name="titulo_3" value="<?= htmlspecialchars($b3['titulo']) ?>" placeholder="Nombre del anunciante">
                                </div>
                                <div class="form-group" style="margin:0;">
                                    <label>URL de destino</label>
                                    <input type="url" name="url_3" value="<?= htmlspecialchars($b3['url']) ?>" placeholder="https://...">
                                </div>
                            </div>
                            <div class="form-group" style="margin-top:15px;">
                                <label>Imagen (PNG con transparencia recomendado)</label>
                                <input type="file" name="imagen_3" accept="image/*">
                                <?php if (!empty($b3['imagen'])): ?>
                                <img src="../<?= $b3['imagen'] ?>" class="banner-preview" alt="Preview">
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                    <button type="submit" class="btn-primary" style="margin-top:20px;">Guardar Configuracion</button>
                </form>
            </div>
        </div>

        <!-- Zona: Entre Sustentabilidad y Columnas -->
        <div class="accordion-card" id="accordion-sustentabilidad-columnas">
            <div class="accordion-header" onclick="toggleAccordion('sustentabilidad-columnas')">
                <h3>
                    Entre Secciones: Sustentabilidad - Columnas de Opinion
                    <span class="status-badge <?= !empty($zonaSustentabilidadColumnas['activo']) ? 'active' : 'inactive' ?>"><?= !empty($zonaSustentabilidadColumnas['activo']) ? 'Activo' : 'Inactivo' ?></span>
                </h3>
                <span class="accordion-arrow">▼</span>
            </div>
            <div class="accordion-content">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="save_config">
                    <input type="hidden" name="zona" value="sustentabilidad-columnas">

                    <div style="display:flex;gap:30px;align-items:center;margin-bottom:25px;flex-wrap:wrap;">
                        <div class="form-group" style="margin:0;">
                            <label>Cantidad de banners</label>
                            <select name="cantidad" id="cantidad-sustentabilidad-columnas" onchange="actualizarBanners('sustentabilidad-columnas', this.value)" style="width:200px;">
                                <option value="0" <?= $zonaSustentabilidadColumnas['cantidad'] == 0 ? 'selected' : '' ?>>Sin publicidad</option>
                                <option value="1" <?= $zonaSustentabilidadColumnas['cantidad'] == 1 ? 'selected' : '' ?>>1 banner (970x250)</option>
                                <option value="2" <?= $zonaSustentabilidadColumnas['cantidad'] == 2 ? 'selected' : '' ?>>2 banners (450x250 c/u)</option>
                                <option value="3" <?= $zonaSustentabilidadColumnas['cantidad'] == 3 ? 'selected' : '' ?>>3 banners (350x250 c/u)</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin:0;">
                            <label style="margin-bottom:8px;display:block;">Mostrar en el sitio</label>
                            <label class="toggle-switch">
                                <input type="checkbox" name="activo" <?= !empty($zonaSustentabilidadColumnas['activo']) ? 'checked' : '' ?>>
                                <span class="toggle-slider"></span>
                                                            </label>
                        </div>
                    </div>

                    <!-- Guía de tamaños -->
                    <div class="size-guide" id="size-guide-sustentabilidad-columnas">
                    <div class="size-item" data-cant="1">
                        <div style="background:#ddd;width:194px;height:50px;margin:0 auto;border-radius:4px;"></div>
                        <strong>1 Banner</strong>
                        <small>970 x 250 px</small>
                    </div>
                    <div class="size-item" data-cant="2">
                        <div style="display:flex;gap:10px;justify-content:center;">
                            <div style="background:#ddd;width:90px;height:50px;border-radius:4px;"></div>
                            <div style="background:#ddd;width:90px;height:50px;border-radius:4px;"></div>
                        </div>
                        <strong>2 Banners</strong>
                        <small>450 x 250 px c/u</small>
                    </div>
                    <div class="size-item" data-cant="3">
                        <div style="display:flex;gap:5px;justify-content:center;">
                            <div style="background:#ddd;width:60px;height:45px;border-radius:4px;"></div>
                            <div style="background:#ddd;width:60px;height:45px;border-radius:4px;"></div>
                            <div style="background:#ddd;width:60px;height:45px;border-radius:4px;"></div>
                        </div>
                        <strong>3 Banners</strong>
                        <small>350 x 250 px c/u</small>
                    </div>
                </div>

                <!-- Configuración de banners -->
                <div id="banners-sustentabilidad-columnas">
                    <!-- Banner 1 -->
                    <div class="banner-config <?= $zonaSustentabilidadColumnas['cantidad'] >= 1 ? 'active' : '' ?>" id="banner-sustentabilidad-columnas-1">
                        <div class="banner-slot">
                            <h4>Banner <?= $zonaSustentabilidadColumnas['cantidad'] == 1 ? '(Centro - 970x250)' : ($zonaSustentabilidadColumnas['cantidad'] == 2 ? 'Izquierda (450x250)' : 'Izquierda (350x250)') ?></h4>
                            <?php
                            $b1 = $zonaSustentabilidadColumnas['banners']['izquierda'] ?? $zonaSustentabilidadColumnas['banners']['centro'] ?? ['titulo' => '', 'url' => '', 'imagen' => ''];
                            ?>
                            <input type="hidden" name="imagen_actual_1" value="<?= htmlspecialchars($b1['imagen']) ?>">
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
                                <div class="form-group" style="margin:0;">
                                    <label>Titulo/Anunciante</label>
                                    <input type="text" name="titulo_1" value="<?= htmlspecialchars($b1['titulo']) ?>" placeholder="Nombre del anunciante">
                                </div>
                                <div class="form-group" style="margin:0;">
                                    <label>URL de destino</label>
                                    <input type="url" name="url_1" value="<?= htmlspecialchars($b1['url']) ?>" placeholder="https://...">
                                </div>
                            </div>
                            <div class="form-group" style="margin-top:15px;">
                                <label>Imagen (PNG con transparencia recomendado)</label>
                                <input type="file" name="imagen_1" accept="image/*">
                                <?php if (!empty($b1['imagen'])): ?>
                                <img src="../<?= $b1['imagen'] ?>" class="banner-preview" alt="Preview">
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Banner 2 -->
                    <div class="banner-config <?= $zonaSustentabilidadColumnas['cantidad'] >= 2 ? 'active' : '' ?>" id="banner-sustentabilidad-columnas-2">
                        <div class="banner-slot">
                            <h4>Banner <?= $zonaSustentabilidadColumnas['cantidad'] == 2 ? 'Derecha (450x250)' : 'Centro (350x250)' ?></h4>
                            <?php
                            $b2 = ($zonaSustentabilidadColumnas['cantidad'] == 3)
                                ? ($zonaSustentabilidadColumnas['banners']['centro'] ?? ['titulo' => '', 'url' => '', 'imagen' => ''])
                                : ($zonaSustentabilidadColumnas['banners']['derecha'] ?? ['titulo' => '', 'url' => '', 'imagen' => '']);
                            ?>
                            <input type="hidden" name="imagen_actual_2" value="<?= htmlspecialchars($b2['imagen']) ?>">
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
                                <div class="form-group" style="margin:0;">
                                    <label>Titulo/Anunciante</label>
                                    <input type="text" name="titulo_2" value="<?= htmlspecialchars($b2['titulo']) ?>" placeholder="Nombre del anunciante">
                                </div>
                                <div class="form-group" style="margin:0;">
                                    <label>URL de destino</label>
                                    <input type="url" name="url_2" value="<?= htmlspecialchars($b2['url']) ?>" placeholder="https://...">
                                </div>
                            </div>
                            <div class="form-group" style="margin-top:15px;">
                                <label>Imagen (PNG con transparencia recomendado)</label>
                                <input type="file" name="imagen_2" accept="image/*">
                                <?php if (!empty($b2['imagen'])): ?>
                                <img src="../<?= $b2['imagen'] ?>" class="banner-preview" alt="Preview">
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Banner 3 -->
                    <div class="banner-config <?= $zonaSustentabilidadColumnas['cantidad'] >= 3 ? 'active' : '' ?>" id="banner-sustentabilidad-columnas-3">
                        <div class="banner-slot">
                            <h4>Banner Derecha (350x250)</h4>
                            <?php
                            $b3 = $zonaSustentabilidadColumnas['banners']['derecha'] ?? ['titulo' => '', 'url' => '', 'imagen' => ''];
                            ?>
                            <input type="hidden" name="imagen_actual_3" value="<?= htmlspecialchars($b3['imagen']) ?>">
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
                                <div class="form-group" style="margin:0;">
                                    <label>Titulo/Anunciante</label>
                                    <input type="text" name="titulo_3" value="<?= htmlspecialchars($b3['titulo']) ?>" placeholder="Nombre del anunciante">
                                </div>
                                <div class="form-group" style="margin:0;">
                                    <label>URL de destino</label>
                                    <input type="url" name="url_3" value="<?= htmlspecialchars($b3['url']) ?>" placeholder="https://...">
                                </div>
                            </div>
                            <div class="form-group" style="margin-top:15px;">
                                <label>Imagen (PNG con transparencia recomendado)</label>
                                <input type="file" name="imagen_3" accept="image/*">
                                <?php if (!empty($b3['imagen'])): ?>
                                <img src="../<?= $b3['imagen'] ?>" class="banner-preview" alt="Preview">
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                    <button type="submit" class="btn-primary" style="margin-top:20px;">Guardar Configuracion</button>
                </form>
            </div>
        </div>
    </div>

    <script>
    // Funcion para abrir/cerrar acordeon
    function toggleAccordion(zona) {
        const card = document.getElementById('accordion-' + zona);
        card.classList.toggle('open');
    }

    function actualizarBanners(zona, cantidad) {
        cantidad = parseInt(cantidad);

        // Mostrar/ocultar slots de banners
        for (let i = 1; i <= 3; i++) {
            const slot = document.getElementById('banner-' + zona + '-' + i);
            if (slot) {
                if (i <= cantidad) {
                    slot.classList.add('active');
                } else {
                    slot.classList.remove('active');
                }
            }
        }

        // Actualizar guía de tamaños
        document.querySelectorAll('#size-guide-' + zona + ' .size-item').forEach(item => {
            if (parseInt(item.dataset.cant) === cantidad) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });

        // Actualizar labels de los banners
        const labels = {
            1: ['(Centro - 970x250)'],
            2: ['Izquierda (450x250)', 'Derecha (450x250)'],
            3: ['Izquierda (350x250)', 'Centro (350x250)', 'Derecha (350x250)']
        };

        if (labels[cantidad]) {
            labels[cantidad].forEach((label, idx) => {
                const h4 = document.querySelector('#banner-' + zona + '-' + (idx + 1) + ' h4');
                if (h4) h4.textContent = 'Banner ' + label;
            });
        }
    }

    // Inicializar al cargar
    document.addEventListener('DOMContentLoaded', function() {
        const zonas = ['mundo-agro', 'agro-sustentabilidad', 'sustentabilidad-columnas'];
        zonas.forEach(zona => {
            const select = document.getElementById('cantidad-' + zona);
            if (select) {
                actualizarBanners(zona, select.value);
            }
        });
    });
    </script>
</body>
</html>
