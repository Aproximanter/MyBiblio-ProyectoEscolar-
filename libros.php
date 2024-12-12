<?php
require_once 'conexion.php';
require_once 'librosController.php';
require_once 'autoresController.php'; // Asegúrate de tener un controlador para los autores
require_once 'editorialesController.php'; // Asegúrate de tener un controlador para las editoriales

$libros = obtenerLibros($pdo);
$autores = obtenerAutores($pdo);
$editoriales = obtenerEditoriales($pdo);

// Manejo de notificaciones
$notificacion = '';
$tipo_notificacion = '';
if (isset($_GET['success'])) {
    $tipo_notificacion = 'success';
    if ($_GET['success'] == 'created') {
        $notificacion = "Libro agregado exitosamente.";
    } elseif ($_GET['success'] == 'updated') {
        $notificacion = "Libro actualizado exitosamente.";
    } elseif ($_GET['success'] == 'deleted') {
        $notificacion = "Libro eliminado exitosamente.";
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
    <title>Manejo de Libros</title>
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
        <h1 class="text-center mb-4">Manejo de Libros</h1>

        <!-- Mostrar notificaciones -->
        <?php if (!empty($notificacion)): ?>
            <div class="notif-message">
                <div class="fancy-notification <?php echo $tipo_notificacion; ?>">
                    <?php echo $notificacion; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Formulario para crear libro -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <i class="fas fa-plus"></i> Agregar Nuevo Libro
            </div>
            <div class="card-body">
                <form method="POST" action="librosController.php">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="titulo" class="form-label">Título</label>
                            <input type="text" name="titulo" class="form-control" id="titulo" placeholder="Título del libro" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="materia" class="form-label">Materia</label>
                            <input type="text" name="materia" class="form-control" id="materia" placeholder="Materia del libro" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="codigo_barras" class="form-label">Código de Barras</label>
                            <input type="text" name="codigo_barras" class="form-control" id="codigo_barras" placeholder="Código de barras" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="codigo_color" class="form-label">Código de Color</label>
                            <input type="text" name="codigo_color" class="form-control" id="codigo_color" placeholder="Código de color" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="signatura" class="form-label">Signatura</label>
                            <input type="text" name="signatura" class="form-control" id="signatura" placeholder="Signatura" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="cantidad" class="form-label">Cantidad</label>
                            <input type="number" name="cantidad" class="form-control" id="cantidad" placeholder="Cantidad" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="id_autor" class="form-label">Autor</label>
                            <select name="id_autor" id="id_autor" class="form-control" required>
                                <?php foreach ($autores as $autor): ?>
                                    <option value="<?php echo $autor['id_autor']; ?>">
                                        <?php echo $autor['nombre'] . ' ' . $autor['apellido']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="id_editorial" class="form-label">Editorial</label>
                            <select name="id_editorial" id="id_editorial" class="form-control" required>
                                <?php foreach ($editoriales as $editorial): ?>
                                    <option value="<?php echo $editorial['id_editorial']; ?>">
                                        <?php echo $editorial['nombre']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="estatus" class="form-label">Estatus</label>
                            <select name="estatus" id="estatus" class="form-control" required>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" name="create" class="btn btn-success"><i class="fas fa-plus"></i> Agregar Libro</button>
                </form>
            </div>
        </div>

        <!-- Tabla de libros -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-book"></i> Lista de Libros
            </div>
            <div class="card-body">
                <table class="table table-striped table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Materia</th>
                            <th>Código de Barras</th>
                            <th>Código de Color</th>
                            <th>Signatura</th>
                            <th>Cantidad</th>
                            <th>Autor</th>
                            <th>Editorial</th>
                            <th>Estatus</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($libros): ?>
                            <?php foreach ($libros as $libro): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($libro['id_libro']); ?></td>
                                    <td><?php echo htmlspecialchars($libro['titulo']); ?></td>
                                    <td><?php echo htmlspecialchars($libro['materia']); ?></td>
                                    <td><?php echo htmlspecialchars($libro['codigo_barras']); ?></td>
                                    <td><?php echo htmlspecialchars($libro['codigo_color']); ?></td>
                                    <td><?php echo htmlspecialchars($libro['signatura']); ?></td>
                                    <td><?php echo htmlspecialchars($libro['cantidad']); ?></td>
                                    <td><?php echo htmlspecialchars($libro['autor_nombre'] . ' ' . $libro['autor_apellido']); ?></td>
                                    <td><?php echo htmlspecialchars($libro['editorial_nombre']); ?></td>
                                    <td>
                                        <?php echo ($libro['estatus'] == 1) ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-secondary">Inactivo</span>'; ?>
                                    </td>
                                    <td>
                                        <!-- Botones de acciones -->
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $libro['id_libro']; ?>">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        <form method="POST" action="librosController.php" style="display:inline-block;">
                                            <input type="hidden" name="id_libro" value="<?php echo $libro['id_libro']; ?>">
                                            <button type="submit" name="delete" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este libro?');">
                                                <i class="fas fa-trash-alt"></i> Eliminar
                                            </button>
                                        </form>
                                        <a href="generarPDFLibro.php?id=<?php echo $libro['id_libro']; ?>" target="_blank" class="btn btn-info btn-sm">
                                            <i class="fas fa-file-pdf"></i> PDF
                                        </a>
                                    </td>
                                </tr>

                                <!-- Modal para editar -->
                                <div class="modal fade" id="editModal<?php echo $libro['id_libro']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $libro['id_libro']; ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel<?php echo $libro['id_libro']; ?>">Editar Libro</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form method="POST" action="librosController.php">
                                                <div class="modal-body">
                                                    <input type="hidden" name="id_libro" value="<?php echo $libro['id_libro']; ?>">
                                                    <div class="row">
                                                        <div class="col-md-4 mb-3">
                                                            <label for="titulo<?php echo $libro['id_libro']; ?>" class="form-label">Título</label>
                                                            <input type="text" name="titulo" class="form-control" id="titulo<?php echo $libro['id_libro']; ?>" value="<?php echo htmlspecialchars($libro['titulo']); ?>" required>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="materia<?php echo $libro['id_libro']; ?>" class="form-label">Materia</label>
                                                            <input type="text" name="materia" class="form-control" id="materia<?php echo $libro['id_libro']; ?>" value="<?php echo htmlspecialchars($libro['materia']); ?>" required>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="codigo_barras<?php echo $libro['id_libro']; ?>" class="form-label">Código de Barras</label>
                                                            <input type="text" name="codigo_barras" class="form-control" id="codigo_barras<?php echo $libro['id_libro']; ?>" value="<?php echo htmlspecialchars($libro['codigo_barras']); ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4 mb-3">
                                                            <label for="codigo_color<?php echo $libro['id_libro']; ?>" class="form-label">Código de Color</label>
                                                            <input type="text" name="codigo_color" class="form-control" id="codigo_color<?php echo $libro['id_libro']; ?>" value="<?php echo htmlspecialchars($libro['codigo_color']); ?>" required>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="signatura<?php echo $libro['id_libro']; ?>" class="form-label">Signatura</label>
                                                            <input type="text" name="signatura" class="form-control" id="signatura<?php echo $libro['id_libro']; ?>" value="<?php echo htmlspecialchars($libro['signatura']); ?>" required>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="cantidad<?php echo $libro['id_libro']; ?>" class="form-label">Cantidad</label>
                                                            <input type="number" name="cantidad" class="form-control" id="cantidad<?php echo $libro['id_libro']; ?>" value="<?php echo htmlspecialchars($libro['cantidad']); ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4 mb-3">
                                                            <label for="id_autor<?php echo $libro['id_libro']; ?>" class="form-label">Autor</label>
                                                            <select name="id_autor" id="id_autor<?php echo $libro['id_libro']; ?>" class="form-control" required>
                                                                <?php foreach ($autores as $autor): ?>
                                                                    <option value="<?php echo $autor['id_autor']; ?>" <?php echo ($libro['id_autor'] == $autor['id_autor']) ? 'selected' : ''; ?>>
                                                                        <?php echo $autor['nombre'] . ' ' . $autor['apellido']; ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="id_editorial<?php echo $libro['id_libro']; ?>" class="form-label">Editorial</label>
                                                            <select name="id_editorial" id="id_editorial<?php echo $libro['id_libro']; ?>" class="form-control" required>
                                                                <?php foreach ($editoriales as $editorial): ?>
                                                                    <option value="<?php echo $editorial['id_editorial']; ?>" <?php echo ($libro['id_editorial'] == $editorial['id_editorial']) ? 'selected' : ''; ?>>
                                                                        <?php echo $editorial['nombre']; ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="estatus<?php echo $libro['id_libro']; ?>" class="form-label">Estatus</label>
                                                            <select name="estatus" id="estatus<?php echo $libro['id_libro']; ?>" class="form-control" required>
                                                                <option value="1" <?php echo ($libro['estatus'] == 1) ? 'selected' : ''; ?>>Activo</option>
                                                                <option value="0" <?php echo ($libro['estatus'] == 0) ? 'selected' : ''; ?>>Inactivo</option>
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
                                <td colspan="11" class="text-center">No hay libros registrados.</td>
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