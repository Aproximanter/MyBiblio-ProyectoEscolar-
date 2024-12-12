<?php
require_once 'conexion.php';

$notificacion = '';
$tipo_notificacion = '';

session_start();

if (isset($_POST['login'])) {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    // Verificar si el usuario existe
    $sql = "SELECT * FROM cuentas WHERE usuario = :usuario AND estatus = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':usuario', $usuario);
    $stmt->execute();
    $cuenta = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cuenta && password_verify($contrasena, $cuenta['contrasena'])) {
        // Iniciar sesión
        $_SESSION['usuario'] = $cuenta['usuario'];
        header("Location: dashboard.php");
        exit();
    } else {
        $notificacion = "Usuario o contraseña incorrectos.";
        $tipo_notificacion = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: auto;
            margin-top: 100px;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .login-container h1 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <h1 class="text-center">Login de Usuarios</h1>

            <?php if (!empty($notificacion)): ?>
                <div class="alert alert-<?php echo $tipo_notificacion; ?>" role="alert">
                    <?php echo $notificacion; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label for="usuario" class="form-label">Usuario</label>
                    <input type="text" name="usuario" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="contrasena" class="form-label">Contraseña</label>
                    <input type="password" name="contrasena" class="form-control" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100 mb-2">Iniciar Sesión</button>
                <a href="registro.php" class="btn btn-secondary w-100">Regístrame</a>
            </form>
        </div>
    </div>
</body>
</html>