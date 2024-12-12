<?php
require_once 'conexion.php';

// Contar registros (Usuarios, Autores y Libros)
$sqlUsuarios = "SELECT COUNT(*) AS total_usuarios FROM usuarios WHERE estatus = 1";
$sqlAutores = "SELECT COUNT(*) AS total_autores FROM autores WHERE estatus = 1";
$sqlLibros = "SELECT COUNT(*) AS total_libros FROM libros WHERE estatus = 1";

$stmtUsuarios = $pdo->prepare($sqlUsuarios);
$stmtAutores = $pdo->prepare($sqlAutores);
$stmtLibros = $pdo->prepare($sqlLibros);

$stmtUsuarios->execute();
$stmtAutores->execute();
$stmtLibros->execute();

$totalUsuarios = $stmtUsuarios->fetch(PDO::FETCH_ASSOC)['total_usuarios'];
$totalAutores = $stmtAutores->fetch(PDO::FETCH_ASSOC)['total_autores'];
$totalLibros = $stmtLibros->fetch(PDO::FETCH_ASSOC)['total_libros'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <!-- Incluir la barra lateral -->
    <?php include 'sidebar.php'; ?>

    <!-- Contenido del Dashboard -->
    <div class="content" style="margin-left: 300px; padding: 20px;">
        <h1>Bienvenido al Panel de Control</h1>
        <p>Usuario logueado: <strong><?php echo $_SESSION['usuario']; ?></strong></p>
        
        <!-- Cards con información -->
        <div class="row">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total de Usuarios</h5>
                        <p class="card-text"><strong><?php echo $totalUsuarios; ?></strong></p>
                        <a href="usuarios.php" class="btn btn-light">Ver Usuarios</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total de Autores</h5>
                        <p class="card-text"><strong><?php echo $totalAutores; ?></strong></p>
                        <a href="autores.php" class="btn btn-light">Ver Autores</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total de Libros</h5>
                        <p class="card-text"><strong><?php echo $totalLibros; ?></strong></p>
                        <a href="libros.php" class="btn btn-light">Ver Libros</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Espacio para agregar más estadísticas y funcionalidades -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Actividad Reciente
                    </div>
                    <div class="card-body">
                        <p>Aquí puedes mostrar un registro de las acciones recientes realizadas en el sistema.</p>
                        <!-- Podrías agregar una tabla de logs o registros recientes -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
