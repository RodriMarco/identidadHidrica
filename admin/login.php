<?php
session_start();
require_once __DIR__ . '/../config.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['usuario'] === ADMIN_USER && $_POST['password'] === ADMIN_PASS) {
        $_SESSION['admin_logged'] = true;
        header('Location: index.php');
        exit;
    }
    $error = 'Usuario o contraseña incorrectos';
}
if (!empty($_SESSION['admin_logged'])) { header('Location: index.php'); exit; }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | <?= SITE_NAME ?></title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DM Sans', sans-serif; background: linear-gradient(135deg, #0a1628, #162447); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-box { background: white; padding: 50px; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); width: 100%; max-width: 400px; }
        .login-logo { text-align: center; margin-bottom: 30px; }
        .login-logo h1 { font-size: 1.8rem; color: #0a1628; }
        .login-logo p { color: #666; font-size: 0.9rem; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; }
        .form-group input { width: 100%; padding: 14px 18px; border: 2px solid #e0e0e0; border-radius: 10px; font-size: 1rem; }
        .form-group input:focus { border-color: #2d9cdb; outline: none; }
        .btn-login { width: 100%; padding: 16px; background: linear-gradient(135deg, #2d9cdb, #1f6f8b); color: white; border: none; border-radius: 10px; font-size: 1rem; font-weight: 600; cursor: pointer; }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(45,156,219,0.3); }
        .error { background: #fee; color: #c00; padding: 12px; border-radius: 8px; margin-bottom: 20px; text-align: center; }
        .back-link { display: block; text-align: center; margin-top: 20px; color: #666; }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="login-logo">
            <h1>🔐 Panel Admin</h1>
            <p><?= SITE_NAME ?></p>
        </div>
        <?php if ($error): ?><div class="error"><?= $error ?></div><?php endif; ?>
        <form method="POST">
            <div class="form-group"><label>Usuario</label><input type="text" name="usuario" required autofocus></div>
            <div class="form-group"><label>Contraseña</label><input type="password" name="password" required></div>
            <button type="submit" class="btn-login">Ingresar</button>
        </form>
        <a href="../index.php" class="back-link">← Volver al sitio</a>
    </div>
</body>
</html>
