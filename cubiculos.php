<?php
require_once 'conexion.php';
require_once 'cubiculosController.php';

$cubiculos = obtenerCubiculos($pdo);

// Manejo de notificaciones
$notificacion = '';
$tipo_notificacion = '';
if (isset($_GET['success'])) {
    $tipo_notificacion = 'success';
    if ($_GET['success'] == 'created') {
        $notificacion = "Cubículo agregado exitosamente.";
    } elseif ($_GET['success'] == 'updated') {
        $notificacion = "Cubículo actualizado exitosamente.";
    } elseif ($_GET['success'] == 'deleted') {
        $notificacion = "Cubículo eliminado exitosamente.";
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
    <title>Manejo de Cubículos</title>
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
        <h1 class="text-center mb-4">Manejo de Cubículos</h1>

        <!-- Mostrar notificaciones -->
        <?php if (!empty($notificacion)): ?>
            <div class="notif-message">
                <div class="fancy-notification <?php echo $tipo_notificacion; ?>">
                    <?php echo $notificacion; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Formulario para crear cubículo -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <i class="fas fa-plus"></i> Agregar Nuevo Cubículo
            </div>
            <div class="card-body">
                <form method="POST" action="cubiculosController.php">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tipo" class="form-label">Tipo</label>
                            <input type="text" name="tipo" class="form-control" id="tipo" placeholder="Tipo de cubículo" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="estatus" class="form-label">Estatus</label>
                            <select name="estatus" id="estatus" class="form-control" required>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" name="create" class="btn btn-success"><i class="fas fa-plus"></i> Agregar Cubículo</button>
                </form>
            </div>
        </div>

        <!-- Tabla de cubículos -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-th-list"></i> Lista de Cubículos
            </div>
            <div class="card-body">
                <table class="table table-striped table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Tipo</th>
                            <th>Estatus</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($cubiculos): ?>
                            <?php foreach ($cubiculos as $cubiculo): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($cubiculo['id_cubiculo']); ?></td>
                                    <td><?php echo htmlspecialchars($cubiculo['tipo']); ?></td>
                                    <td>
                                        <?php echo ($cubiculo['estatus'] == 1) ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-secondary">Inactivo</span>'; ?>
                                    </td>
                                    <td>
                                        <!-- Botones de acciones -->
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $cubiculo['id_cubiculo']; ?>">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        <form method="POST" action="cubiculosController.php" style="display:inline-block;">
                                            <input type="hidden" name="id_cubiculo" value="<?php echo $cubiculo['id_cubiculo']; ?>">
                                            <button type="submit" name="delete" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este cubículo?');">
                                                <i class="fas fa-trash-alt"></i> Eliminar
                                            </button>
                                        </form>
                                        <a href="generarPDFCubiculo.php?id=<?php echo $cubiculo['id_cubiculo']; ?>" target="_blank" class="btn btn-info btn-sm">
                                            <i class="fas fa-file-pdf"></i> PDF
                                        </a>
                                    </td>
                                </tr>

                                <!-- Modal para editar -->
                                <div class="modal fade" id="editModal<?php echo $cubiculo['id_cubiculo']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $cubiculo['id_cubiculo']; ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel<?php echo $cubiculo['id_cubiculo']; ?>">Editar Cubículo</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form method="POST" action="cubiculosController.php">
                                                <div class="modal-body">
                                                    <input type="hidden" name="id_cubiculo" value="<?php echo $cubiculo['id_cubiculo']; ?>">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label for="tipo<?php echo $cubiculo['id_cubiculo']; ?>" class="form-label">Tipo</label>
                                                            <input type="text" name="tipo" class="form-control" id="tipo<?php echo $cubiculo['id_cubiculo']; ?>" value="<?php echo htmlspecialchars($cubiculo['tipo']); ?>" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label for="estatus<?php echo $cubiculo['id_cubiculo']; ?>" class="form-label">Estatus</label>
                                                            <select name="estatus" id="estatus<?php echo $cubiculo['id_cubiculo']; ?>" class="form-control" required>
                                                                <option value="1" <?php echo ($cubiculo['estatus'] == 1) ? 'selected' : ''; ?>>Activo</option>
                                                                <option value="0" <?php echo ($cubiculo['estatus'] == 0) ? 'selected' : ''; ?>>Inactivo</option>
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
                                <td colspan="4" class="text-center">No hay cubículos registrados.</td>
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