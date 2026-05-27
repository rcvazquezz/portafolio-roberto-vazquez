<?php
/**
 * admin/setup.php — Script de instalación del primer admin
 *
 * Visita este archivo UNA SOLA VEZ desde el navegador para crear
 * tu usuario administrador con contraseña hasheada en bcrypt.
 *
 * ¡ELIMINA ESTE ARCHIVO después de crear el usuario!
 * Dejarlo en producción es un riesgo de seguridad.
 *
 * URL local: http://localhost/portafolio-roberto-vazquez/admin/setup.php
 */

define('ADMIN_ROOT', __DIR__);
require_once ADMIN_ROOT . '/config/app.php';

use Core\Database;

/* Autoloader mínimo solo para Database */
spl_autoload_register(function (string $class): void {
    $map = ['Core\\' => ADMIN_ROOT . '/src/Core/'];
    foreach ($map as $ns => $dir) {
        if (str_starts_with($class, $ns)) {
            $file = $dir . substr($class, strlen($ns)) . '.php';
            if (file_exists($file)) require_once $file;
            return;
        }
    }
});

$message = '';
$success = false;

/* ── Verificar si ya existe un admin ──────────────────────── */
try {
    $pdo   = Database::getInstance();
    $count = (int) $pdo->query('SELECT COUNT(*) FROM admin_users')->fetchColumn();

    if ($count > 0) {
        $message = '⚠️ Ya existe un usuario admin. Elimina este archivo.';
    }
} catch (\Exception $e) {
    $message = 'Error al conectar con la base de datos: ' . $e->getMessage();
}

/* ── Procesar el formulario de creación ────────────────────── */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $count === 0) {
    $name     = trim($_POST['name']     ?? '');
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm  = trim($_POST['confirm']  ?? '');

    if (empty($name) || empty($email) || empty($password)) {
        $message = 'Todos los campos son obligatorios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'El email no es válido.';
    } elseif (strlen($password) < 8) {
        $message = 'La contraseña debe tener al menos 8 caracteres.';
    } elseif ($password !== $confirm) {
        $message = 'Las contraseñas no coinciden.';
    } else {
        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $stmt = $pdo->prepare(
            'INSERT INTO admin_users (name, email, password_hash) VALUES (?, ?, ?)'
        );
        $stmt->execute([$name, $email, $hash]);
        $success = true;
        $message = '✅ Usuario admin creado. Ahora elimina este archivo (admin/setup.php) del servidor.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Setup — Portfolio CMS</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-950 flex items-center justify-center font-sans text-gray-100">
  <div class="w-full max-w-md px-4">

    <div class="text-center mb-8">
      <div class="w-12 h-12 rounded-xl bg-violet-600 flex items-center justify-center mx-auto mb-4">
        <span class="text-white font-bold text-lg">RV</span>
      </div>
      <h1 class="text-xl font-semibold">Portfolio CMS — Setup</h1>
      <p class="text-sm text-gray-500 mt-1">Crear el primer usuario administrador</p>
    </div>

    <?php if (!empty($message)): ?>
      <div class="mb-5 px-4 py-3 rounded-lg text-sm border
                  <?= $success ? 'bg-green-900/40 text-green-300 border-green-800' : 'bg-red-900/40 text-red-300 border-red-800' ?>">
        <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>

    <?php if (!$success && ($count ?? 0) === 0): ?>
      <form method="POST" class="bg-gray-900 border border-gray-800 rounded-xl p-6 space-y-4">
        <div>
          <label class="block text-xs font-medium text-gray-400 mb-1.5">Nombre</label>
          <input type="text" name="name" required
                 value="Roberto Vázquez"
                 class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-violet-600">
        </div>
        <div>
          <label class="block text-xs font-medium text-gray-400 mb-1.5">Email</label>
          <input type="email" name="email" required
                 value="rcvazquezantelo2006@gmail.com"
                 class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-violet-600">
        </div>
        <div>
          <label class="block text-xs font-medium text-gray-400 mb-1.5">Contraseña <span class="text-gray-600">(mín. 8 caracteres)</span></label>
          <input type="password" name="password" required minlength="8"
                 class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-violet-600">
        </div>
        <div>
          <label class="block text-xs font-medium text-gray-400 mb-1.5">Confirmar contraseña</label>
          <input type="password" name="confirm" required
                 class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-violet-600">
        </div>
        <button type="submit"
                class="w-full bg-violet-600 hover:bg-violet-700 text-white text-sm font-medium py-2.5 rounded-lg transition-colors">
          Crear administrador
        </button>
      </form>
    <?php elseif ($success): ?>
      <div class="text-center">
        <a href="<?= APP_URL ?>/login"
           class="inline-block bg-violet-600 hover:bg-violet-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition-colors">
          Ir al panel →
        </a>
      </div>
    <?php endif; ?>

    <p class="text-center text-xs text-red-500 mt-6 font-medium">
      ⚠️ Elimina este archivo del servidor tras crear el usuario.
    </p>

  </div>
</body>
</html>
