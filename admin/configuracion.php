<?php
require_once __DIR__ . '/../functions.php';
verificarAdmin();

$mensaje = '';
$config = getSiteConfig();
$suscriptores = getSuscriptores();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'save_config') {
        $config['newsletter_email'] = filter_var($_POST['newsletter_email'], FILTER_SANITIZE_EMAIL);
        $config['nosotros_contenido'] = $_POST['nosotros_contenido'];
        guardarSiteConfig($config);
        $mensaje = 'Configuración guardada correctamente';
    } elseif ($_POST['action'] === 'delete_suscriptor') {
        $suscriptores = array_filter($suscriptores, fn($s) => $s['email'] !== $_POST['email']);
        file_put_contents(DATA_PATH . '/suscriptores.json', json_encode(array_values($suscriptores), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $suscriptores = getSuscriptores();
        $mensaje = 'Suscriptor eliminado';
    } elseif ($_POST['action'] === 'export_suscriptores') {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="suscriptores_' . date('Y-m-d') . '.csv"');
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Email', 'Fecha de suscripción']);
        foreach ($suscriptores as $s) {
            fputcsv($output, [$s['email'], $s['fecha']]);
        }
        fclose($output);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración | Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>body{background:#f5f8fa;}</style>
</head>
<body>
    <header class="admin-header">
        <div style="display:flex;align-items:center;gap:15px;">
            <span style="font-size:1.5rem;"></span>
            <div><h1 style="font-size:1.2rem;margin:0;">Panel de Administración</h1></div>
        </div>
        <nav class="admin-nav">
            <a href="index.php">Dashboard</a>
            <a href="articulos.php">Artículos</a>
            <a href="videos.php">Videos</a>
            <a href="publicidad.php">Publicidad</a>
            <a href="configuracion.php" class="active">Configuración</a>
            <a href="../index.php" target="_blank">Ver sitio</a>
            <a href="logout.php">Salir</a>
        </nav>
    </header>

    <div class="admin-content">
        <?php if ($mensaje): ?><div class="message success"><?= $mensaje ?></div><?php endif; ?>

        <h2 style="font-family:var(--font-display);font-size:2rem;margin-bottom:30px;">Configuración del Sitio</h2>

        <div class="admin-card" style="margin-bottom:30px;">
            <h2>Newsletter</h2>
            <form method="POST">
                <input type="hidden" name="action" value="save_config">
                <div class="form-group">
                    <label>Email para recibir suscripciones</label>
                    <input type="email" name="newsletter_email" value="<?= htmlspecialchars($config['newsletter_email'] ?? '') ?>" placeholder="tucorreo@ejemplo.com">
                    <small style="color:#666;display:block;margin-top:5px;">Cuando alguien se suscriba al newsletter, recibirás una notificación en este email.</small>
                </div>
                <button type="submit" class="btn-primary">Guardar</button>
            </form>
        </div>

        <div class="admin-card" style="margin-bottom:30px;">
            <h2>Suscriptores del Newsletter (<?= count($suscriptores) ?>)</h2>
            <?php if (!empty($suscriptores)): ?>
            <div style="margin-bottom:20px;">
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="action" value="export_suscriptores">
                    <button type="submit" class="btn-primary" style="background:#2d6a4f;">Exportar CSV</button>
                </form>
            </div>
            <table class="admin-table">
                <thead><tr><th>Email</th><th>Fecha</th><th>Acción</th></tr></thead>
                <tbody>
                <?php foreach ($suscriptores as $s): ?>
                <tr>
                    <td><?= htmlspecialchars($s['email']) ?></td>
                    <td><?= formatearFecha($s['fecha']) ?></td>
                    <td>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este suscriptor?')">
                            <input type="hidden" name="action" value="delete_suscriptor">
                            <input type="hidden" name="email" value="<?= htmlspecialchars($s['email']) ?>">
                            <button type="submit" style="background:#ffebee;color:#c62828;padding:4px 10px;border:none;border-radius:4px;cursor:pointer;font-size:0.85rem;">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p style="color:#999;">No hay suscriptores todavía.</p>
            <?php endif; ?>
        </div>

        <div class="admin-card">
            <h2>Página "Nosotros"</h2>
            <form method="POST">
                <input type="hidden" name="action" value="save_config">
                <input type="hidden" name="newsletter_email" value="<?= htmlspecialchars($config['newsletter_email'] ?? '') ?>">
                <div class="form-group">
                    <label>Contenido de la página Nosotros</label>
                    <textarea name="nosotros_contenido" style="min-height:400px;" placeholder="Escribí el contenido de la página Nosotros. Podés usar HTML: <p>, <h2>, <strong>, etc."><?= htmlspecialchars($config['nosotros_contenido'] ?? '') ?></textarea>
                    <small style="color:#666;display:block;margin-top:5px;">Usá HTML para dar formato: &lt;p&gt; para párrafos, &lt;h2&gt; para subtítulos, &lt;strong&gt; para negrita, &lt;a href="..."&gt; para enlaces.</small>
                </div>
                <button type="submit" class="btn-primary">Guardar contenido</button>
                <a href="../nosotros.php" target="_blank" class="btn-primary" style="background:#d4edda;color:#155724;margin-left:10px;">Ver página</a>
            </form>
        </div>
    </div>
</body>
</html>
