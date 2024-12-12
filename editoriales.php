<?php
require_once 'conexion.php';
require_once 'editorialesController.php';

$editoriales = obtenerEditoriales($pdo);

// Manejo de notificaciones
$notificacion = '';
$tipo_notificacion = '';
if (isset($_GET['success'])) {
    $tipo_notificacion = 'success';
    if ($_GET['success'] == 'created') {
        $notificacion = "Editorial agregada exitosamente.";
    } elseif ($_GET['success'] == 'updated') {
        $notificacion = "Editorial actualizada exitosamente.";
    } elseif ($_GET['success'] == 'deleted') {
        $notificacion = "Editorial eliminada exitosamente.";
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
    <title>Manejo de Editoriales</title>
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
        <h1 class="text-center mb-4">Manejo de Editoriales</h1>

        <!-- Mostrar notificaciones -->
        <?php if (!empty($notificacion)): ?>
            <div class="notif-message">
                <div class="fancy-notification <?php echo $tipo_notificacion; ?>">
                    <?php echo $notificacion; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Formulario para crear editorial -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <i class="fas fa-plus"></i> Agregar Nueva Editorial
            </div>
            <div class="card-body">
                <form method="POST" action="editorialesController.php">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Nombre de la editorial" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" name="direccion" class="form-control" id="direccion" placeholder="Dirección de la editorial" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="form-control" id="telefono" placeholder="Teléfono de la editorial" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="correo" class="form-label">Correo Electrónico</label>
                            <input type="email" name="correo" class="form-control" id="correo" placeholder="Correo electrónico de la editorial" required>
                        </div>
                    </div>
                    <button type="submit" name="create" class="btn btn-success"><i class="fas fa-plus"></i> Agregar Editorial</button>
                </form>
            </div>
        </div>

        <!-- Tabla de editoriales -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-building"></i> Lista de Editoriales
            </div>
            <div class="card-body">
                <table class="table table-striped table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th>Correo Electrónico</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($editoriales): ?>
                            <?php foreach ($editoriales as $editorial): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($editorial['id_editorial']); ?></td>
                                    <td><?php echo htmlspecialchars($editorial['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($editorial['direccion']); ?></td>
                                    <td><?php echo htmlspecialchars($editorial['telefono']); ?></td>
                                    <td><?php echo htmlspecialchars($editorial['correo']); ?></td>
                                    <td>
                                        <!-- Botones de acciones -->
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $editorial['id_editorial']; ?>">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        <form method="POST" action="editorialesController.php" style="display:inline-block;">
                                            <input type="hidden" name="id_editorial" value="<?php echo $editorial['id_editorial']; ?>">
                                            <button type="submit" name="delete" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta editorial?');">
                                                <i class="fas fa-trash-alt"></i> Eliminar
                                            </button>
                                        </form>
                                        <a href="generarPDFEditorial.php?id=<?php echo $editorial['id_editorial']; ?>" target="_blank" class="btn btn-info btn-sm">
                                            <i class="fas fa-file-pdf"></i> PDF
                                        </a>
                                    </td>
                                </tr>

                                <!-- Modal para editar -->
                                <div class="modal fade" id="editModal<?php echo $editorial['id_editorial']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $editorial['id_editorial']; ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel<?php echo $editorial['id_editorial']; ?>">Editar Editorial</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form method="POST" action="editorialesController.php">
                                                <div class="modal-body">
                                                    <input type="hidden" name="id_editorial" value="<?php echo $editorial['id_editorial']; ?>">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label for="nombre<?php echo $editorial['id_editorial']; ?>" class="form-label">Nombre</label>
                                                            <input type="text" name="nombre" class="form-control" id="nombre<?php echo $editorial['id_editorial']; ?>" value="<?php echo htmlspecialchars($editorial['nombre']); ?>" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label for="direccion<?php echo $editorial['id_editorial']; ?>" class="form-label">Dirección</label>
                                                            <input type="text" name="direccion" class="form-control" id="direccion<?php echo $editorial['id_editorial']; ?>" value="<?php echo htmlspecialchars($editorial['direccion']); ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label for="telefono<?php echo $editorial['id_editorial']; ?>" class="form-label">Teléfono</label>
                                                            <input type="text" name="telefono" class="form-control" id="telefono<?php echo $editorial['id_editorial']; ?>" value="<?php echo htmlspecialchars($editorial['telefono']); ?>" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label for="correo<?php echo $editorial['id_editorial']; ?>" class="form-label">Correo Electrónico</label>
                                                            <input type="email" name="correo" class="form-control" id="correo<?php echo $editorial['id_editorial']; ?>" value="<?php echo htmlspecialchars($editorial['correo']); ?>" required>
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
                                <td colspan="6" class="text-center">No hay editoriales registradas.</td>
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