<?php
require_once 'conexion.php';
require_once 'multasController.php';
require_once 'usuariosController.php'; // Asegúrate de tener un controlador para los usuarios

$multas = obtenerMultas($pdo);
$usuarios = obtenerUsuarios($pdo);

// Manejo de notificaciones
$notificacion = '';
$tipo_notificacion = '';
if (isset($_GET['success'])) {
    $tipo_notificacion = 'success';
    if ($_GET['success'] == 'created') {
        $notificacion = "Multa agregada exitosamente.";
    } elseif ($_GET['success'] == 'updated') {
        $notificacion = "Multa actualizada exitosamente.";
    } elseif ($_GET['success'] == 'deleted') {
        $notificacion = "Multa eliminada exitosamente.";
    }
} elseif (isset($_GET['error'])) {
    $tipo_notificacion = 'error';
    $notificacion = "Hubo un error en la operación.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manejo de Multas</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome para iconos -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Fancy Notifications CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancy-notifications/1.0.0/fancy-notifications.min.css">
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
        <h1 class="text-center mb-4">Manejo de Multas</h1>

        <!-- Mostrar notificaciones -->
        <?php if (!empty($notificacion)): ?>
            <div class="notif-message">
                <div class="fancy-notification <?php echo $tipo_notificacion; ?>">
                    <?php echo $notificacion; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Formulario para crear multa -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <i class="fas fa-plus"></i> Agregar Nueva Multa
            </div>
            <div class="card-body">
                <form method="POST" action="multasController.php">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="id_usuario" class="form-label">Usuario</label>
                            <select name="id_usuario" id="id_usuario" class="form-control" required>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <option value="<?php echo $usuario['id_usuario']; ?>">
                                        <?php echo $usuario['nombre'] . ' ' . $usuario['apellido']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="monto" class="form-label">Monto</label>
                            <input type="number" name="monto" class="form-control" id="monto" placeholder="Monto de la multa" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <input type="text" name="descripcion" class="form-control" id="descripcion" placeholder="Descripción de la multa" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="estatus" class="form-label">Estatus</label>
                            <select name="estatus" id="estatus" class="form-control" required>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" name="create" class="btn btn-success"><i class="fas fa-plus"></i> Agregar Multa</button>
                </form>
            </div>
        </div>

        <!-- Tabla de multas -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-list"></i> Lista de Multas
            </div>
            <div class="card-body">
                <table class="table table-striped table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Monto</th>
                            <th>Descripción</th>
                            <th>Estatus</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($multas): ?>
                            <?php foreach ($multas as $multa): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($multa['id_multa']); ?></td>
                                    <td><?php echo htmlspecialchars($multa['usuario_nombre'] . ' ' . $multa['usuario_apellido']); ?></td>
                                    <td><?php echo htmlspecialchars($multa['monto']); ?></td>
                                    <td><?php echo htmlspecialchars($multa['descripcion']); ?></td>
                                    <td>
                                        <?php echo ($multa['estatus'] == 1) ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-secondary">Inactivo</span>'; ?>
                                    </td>
                                    <td>
                                        <!-- Botones de acciones -->
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $multa['id_multa']; ?>">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        <form method="POST" action="multasController.php" style="display:inline-block;">
                                            <input type="hidden" name="id_multa" value="<?php echo $multa['id_multa']; ?>">
                                            <button type="submit" name="delete" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta multa?');">
                                                <i class="fas fa-trash-alt"></i> Eliminar
                                            </button>
                                        </form>
                                        <a href="generarPDFMulta.php?id=<?php echo $multa['id_multa']; ?>" target="_blank" class="btn btn-info btn-sm">
                                            <i class="fas fa-file-pdf"></i> PDF
                                        </a>
                                    </td>
                                </tr>

                                <!-- Modal para editar -->
                                <div class="modal fade" id="editModal<?php echo $multa['id_multa']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $multa['id_multa']; ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel<?php echo $multa['id_multa']; ?>">Editar Multa</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form method="POST" action="multasController.php">
                                                <div class="modal-body">
                                                    <input type="hidden" name="id_multa" value="<?php echo $multa['id_multa']; ?>">
                                                    <div class="row">
                                                        <div class="col-md-4 mb-3">
                                                            <label for="id_usuario<?php echo $multa['id_multa']; ?>" class="form-label">Usuario</label>
                                                            <select name="id_usuario" id="id_usuario<?php echo $multa['id_multa']; ?>" class="form-control" required>
                                                                <?php foreach ($usuarios as $usuario): ?>
                                                                    <option value="<?php echo $usuario['id_usuario']; ?>" <?php echo ($multa['id_usuario'] == $usuario['id_usuario']) ? 'selected' : ''; ?>>
                                                                        <?php echo $usuario['nombre'] . ' ' . $usuario['apellido']; ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="monto<?php echo $multa['id_multa']; ?>" class="form-label">Monto</label>
                                                            <input type="number" name="monto" class="form-control" id="monto<?php echo $multa['id_multa']; ?>" value="<?php echo htmlspecialchars($multa['monto']); ?>" required>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="descripcion<?php echo $multa['id_multa']; ?>" class="form-label">Descripción</label>
                                                            <input type="text" name="descripcion" class="form-control" id="descripcion<?php echo $multa['id_multa']; ?>" value="<?php echo htmlspecialchars($multa['descripcion']); ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4 mb-3">
                                                            <label for="estatus<?php echo $multa['id_multa']; ?>" class="form-label">Estatus</label>
                                                            <select name="estatus" id="estatus<?php echo $multa['id_multa']; ?>" class="form-control" required>
                                                                <option value="1" <?php echo ($multa['estatus'] == 1) ? 'selected' : ''; ?>>Activo</option>
                                                                <option value="0" <?php echo ($multa['estatus'] == 0) ? 'selected' : ''; ?>>Inactivo</option>
                                                            </select>
                                                        </div>
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
                                <td colspan="6" class="text-center">No hay multas registradas.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS y dependencias -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- Fancy Notifications JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancy-notifications/1.0.0/fancy-notifications.min.js"></script>
</body>
</html>