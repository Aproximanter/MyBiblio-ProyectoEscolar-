<?php
require_once 'conexion.php';
require_once 'autoresController.php';

$autores = obtenerAutores($pdo);

// Manejo de notificaciones
$notificacion = '';
if (isset($_GET['success'])) {
    if ($_GET['success'] == 'created') {
        $notificacion = "Autor agregado exitosamente.";
    } elseif ($_GET['success'] == 'updated') {
        $notificacion = "Autor actualizado exitosamente.";
    } elseif ($_GET['success'] == 'deleted') {
        $notificacion = "Autor eliminado exitosamente.";
    }
} elseif (isset($_GET['error'])) {
    $notificacion = "Hubo un error en la operación.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manejo de Autores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancy-notifications/0.1.0/css/fancy-notifications.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
    
        .content {
            margin-left: 250px;
            padding: 20px;
        }
        .notif-message {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 9999;
        }
    </style>
</head>
<body>

<!-- Menú lateral -->
<?php include 'sidebar.php'; ?>


<!-- Contenido principal -->
<div class="content">
    <div class="container mt-5">
        <h1 class="text-center">Sistema de Autores</h1>

        <!-- Mostrar notificaciones -->
        <?php if ($notificacion): ?>
            <div class="notif-message">
                <div class="fancy-notification success">
                    <?php echo $notificacion; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Formulario para crear autor -->
        <form method="POST" action="autoresController.php" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="nombre" class="form-control" placeholder="Nombre" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="apellido" class="form-control" placeholder="Apellido" required>
                </div>
                <div class="col-md-3">
                    <input type="date" name="fecha_nacimiento" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="nacionalidad" class="form-control" placeholder="Nacionalidad" required>
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" name="create" class="btn btn-success"><i class="fas fa-plus"></i> Agregar Autor</button>
            </div>
        </form>

        <!-- Tabla de autores -->
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Nacionalidad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($autores as $autor): ?>
                <tr>
                    <td><?php echo $autor['id_autor']; ?></td>
                    <td><?php echo $autor['nombre']; ?></td>
                    <td><?php echo $autor['apellido']; ?></td>
                    <td><?php echo $autor['fecha_nacimiento']; ?></td>
                    <td><?php echo $autor['nacionalidad']; ?></td>
                    <td>
                        <!-- Botones de acciones -->
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $autor['id_autor']; ?>"><i class="fas fa-edit"></i> Editar</button>
                        <form method="POST" action="autoresController.php" style="display:inline-block;">
                            <input type="hidden" name="id_autor" value="<?php echo $autor['id_autor']; ?>">
                            <button type="submit" name="delete" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Eliminar</button>
                        </form>
                        <a href="generarPDFAutores.php?id=<?php echo $autor['id_autor']; ?>" target="_blank" class="btn btn-info btn-sm"><i class="fas fa-file-pdf"></i> PDF</a>
                    </td>
                </tr>

                <!-- Modal para editar -->
                <div class="modal fade" id="editModal<?php echo $autor['id_autor']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Editar Autor</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form method="POST" action="autoresController.php">
                                <div class="modal-body">
                                    <input type="hidden" name="id_autor" value="<?php echo $autor['id_autor']; ?>">
                                    <div class="mb-3">
                                        <label>Nombre</label>
                                        <input type="text" name="nombre" class="form-control" value="<?php echo $autor['nombre']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Apellido</label>
                                        <input type="text" name="apellido" class="form-control" value="<?php echo $autor['apellido']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Fecha de Nacimiento</label>
                                        <input type="date" name="fecha_nacimiento" class="form-control" value="<?php echo $autor['fecha_nacimiento']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Nacionalidad</label>
                                        <input type="text" name="nacionalidad" class="form-control" value="<?php echo $autor['nacionalidad']; ?>" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" name="update" class="btn btn-primary">Guardar cambios</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancy-notifications/0.1.0/js/fancy-notifications.min.js"></script>
</body>
</html>