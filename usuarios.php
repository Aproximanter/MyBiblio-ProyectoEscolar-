<?php
require_once 'conexion.php';
require_once 'usuariosController.php';

$usuarios = obtenerUsuarios($pdo);

// Manejo de notificaciones
$notificacion = '';
$tipo_notificacion = '';
if (isset($_GET['success'])) {
    if ($_GET['success'] == 'created') {
        $notificacion = "Usuario agregado exitosamente.";
        $tipo_notificacion = "success";
    } elseif ($_GET['success'] == 'updated') {
        $notificacion = "Usuario actualizado exitosamente.";
        $tipo_notificacion = "success";
    } elseif ($_GET['success'] == 'deleted') {
        $notificacion = "Usuario eliminado exitosamente.";
        $tipo_notificacion = "success";
    }
} elseif (isset($_GET['error'])) {
    $notificacion = "Hubo un error en la operación.";
    $tipo_notificacion = "error";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuarios</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome para iconos -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Fancy Notifications CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancy-notifications/0.1.0/css/fancy-notifications.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .content {
            margin-left: 300px;
            padding: 20px;
        }
        .notif-message {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
    </style>
</head>
<body>

<!-- Menú lateral -->
<?php include 'sidebar.php'; ?>

<!-- Contenido principal -->
<div class="content">
    <div class="container-fluid">
        <h1 class="text-center mb-4">Registro de Personal</h1>

        <!-- Mostrar notificaciones -->
        <?php if (!empty($notificacion)): ?>
            <div class="notif-message">
                <div class="fancy-notification <?php echo $tipo_notificacion; ?>">
                    <?php echo $notificacion; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Formulario para crear usuario -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <i class="fas fa-user-plus"></i> Agregar Nuevo Usuario
            </div>
            <div class="card-body">
                <form method="POST" action="usuariosController.php">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Nombre del usuario" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" name="apellido" class="form-control" id="apellido" placeholder="Apellido del usuario" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="correo" class="form-label">Correo Electrónico</label>
                            <input type="email" name="correo" class="form-control" id="correo" placeholder="correo@ejemplo.com" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="form-control" id="telefono" placeholder="Número de teléfono" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" name="direccion" class="form-control" id="direccion" placeholder="Dirección del usuario" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="no_de_control" class="form-label">No. de Control</label>
                            <input type="text" name="no_de_control" class="form-control" id="no_de_control" placeholder="Número de control" required>
                        </div>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="estatus" name="estatus" checked>
                        <label class="form-check-label" for="estatus">Activo</label>
                    </div>
                    <button type="submit" name="create" class="btn btn-success"><i class="fas fa-plus"></i> Agregar Usuario</button>
                </form>
            </div>
        </div>

        <!-- Tabla de usuarios -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-users"></i> Lista de Usuarios
            </div>
            <div class="card-body">
                <table class="table table-striped table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th>No. de Control</th>
                            <th>Estatus</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($usuarios): ?>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($usuario['id_usuario']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['apellido']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['correo']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['telefono']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['direccion']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['no_de_control']); ?></td>
                                    <td>
                                        <?php echo ($usuario['estatus'] == 1) ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-secondary">Inactivo</span>'; ?>
                                    </td>
                                    <td>
                                        <!-- Botones de acciones -->
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $usuario['id_usuario']; ?>">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        <form method="POST" action="usuariosController.php" style="display:inline-block;">
                                            <input type="hidden" name="id_usuario" value="<?php echo $usuario['id_usuario']; ?>">
                                            <button type="submit" name="delete" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este usuario?');">
                                                <i class="fas fa-trash-alt"></i> Eliminar
                                            </button>
                                        </form>
                                        <a href="generarPDFUsuario.php?id=<?php echo $usuario['id_usuario']; ?>" target="_blank" class="btn btn-info btn-sm">
                                            <i class="fas fa-file-pdf"></i> PDF
                                        </a>
                                    </td>
                                </tr>

                                <!-- Modal para editar -->
                                <div class="modal fade" id="editModal<?php echo $usuario['id_usuario']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $usuario['id_usuario']; ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel<?php echo $usuario['id_usuario']; ?>">Editar Usuario</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form method="POST" action="usuariosController.php">
                                                <div class="modal-body">
                                                    <input type="hidden" name="id_usuario" value="<?php echo $usuario['id_usuario']; ?>">
                                                    <div class="row">
                                                        <div class="col-md-4 mb-3">
                                                            <label for="nombre<?php echo $usuario['id_usuario']; ?>" class="form-label">Nombre</label>
                                                            <input type="text" name="nombre" class="form-control" id="nombre<?php echo $usuario['id_usuario']; ?>" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="apellido<?php echo $usuario['id_usuario']; ?>" class="form-label">Apellido</label>
                                                            <input type="text" name="apellido" class="form-control" id="apellido<?php echo $usuario['id_usuario']; ?>" value="<?php echo htmlspecialchars($usuario['apellido']); ?>" required>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="correo<?php echo $usuario['id_usuario']; ?>" class="form-label">Correo Electrónico</label>
                                                            <input type="email" name="correo" class="form-control" id="correo<?php echo $usuario['id_usuario']; ?>" value="<?php echo htmlspecialchars($usuario['correo']); ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4 mb-3">
                                                            <label for="telefono<?php echo $usuario['id_usuario']; ?>" class="form-label">Teléfono</label>
                                                            <input type="text" name="telefono" class="form-control" id="telefono<?php echo $usuario['id_usuario']; ?>" value="<?php echo htmlspecialchars($usuario['telefono']); ?>" required>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="direccion<?php echo $usuario['id_usuario']; ?>" class="form-label">Dirección</label>
                                                            <input type="text" name="direccion" class="form-control" id="direccion<?php echo $usuario['id_usuario']; ?>" value="<?php echo htmlspecialchars($usuario['direccion']); ?>" required>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="no_de_control<?php echo $usuario['id_usuario']; ?>" class="form-label">No. de Control</label>
                                                            <input type="text" name="no_de_control" class="form-control" id="no_de_control<?php echo $usuario['id_usuario']; ?>" value="<?php echo htmlspecialchars($usuario['no_de_control']); ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="estatus<?php echo $usuario['id_usuario']; ?>" name="estatus" <?php echo ($usuario['estatus'] == 1) ? 'checked' : ''; ?>>
                                                        <label class="form-check-label" for="estatus<?php echo $usuario['id_usuario']; ?>">Activo</label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                    <button type="submit" name="update" class="btn btn-primary">Guardar cambios</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center">No hay usuarios registrados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- QR Code Modal -->
<div class="modal fade" id="qrCodeModal" tabindex="-1" aria-labelledby="qrCodeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qrCodeModalLabel">Código QR</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div id="qrcode"></div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS y dependencias -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- Fancy Notifications JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancy-notifications/0.1.0/js/fancy-notifications.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    // Auto cerrar las notificaciones después de 5 segundos
    document.addEventListener('DOMContentLoaded', function() {
        if (document.querySelector('.notif-message')) {
            setTimeout(function() {
                document.querySelector('.notif-message').remove();
            }, 5000);
        }
    });
</script>
</body>
</html>