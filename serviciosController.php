<?php
require_once 'conexion.php';


function obtenerServicios($pdo) {
    $sql = "SELECT s.*, u.nombre, u.apellido FROM servicio s 
            JOIN usuarios u ON s.id_usuario = u.id_usuario";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function obtenerServicioPorId($pdo, $id_servicio) {
    $sql = "SELECT * FROM servicio  WHERE id_servicio = :id_servicio";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_servicio', $id_servicio, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function crearServicio($pdo, $id_usuario, $fecha_servicio, $hora_inicio, $hora_fin, $estatus) {
    $sql = "INSERT INTO servicio (id_usuario, fecha_servicio, hora_inicio, hora_fin, estatus) VALUES (:id_usuario, :fecha_servicio, :hora_inicio, :hora_fin, :estatus)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->bindParam(':fecha_servicio', $fecha_servicio, PDO::PARAM_STR);
    $stmt->bindParam(':hora_inicio', $hora_inicio, PDO::PARAM_STR);
    $stmt->bindParam(':hora_fin', $hora_fin, PDO::PARAM_STR);
    $stmt->bindParam(':estatus', $estatus, PDO::PARAM_INT);
    return $stmt->execute();
}

function actualizarServicio($pdo, $id_servicio, $id_usuario, $fecha_servicio, $hora_inicio, $hora_fin, $estatus) {
    $sql = "UPDATE servicio SET id_usuario = :id_usuario, fecha_servicio = :fecha_servicio, hora_inicio = :hora_inicio, hora_fin = :hora_fin, estatus = :estatus WHERE id_servicio = :id_servicio";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_servicio', $id_servicio, PDO::PARAM_INT);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->bindParam(':fecha_servicio', $fecha_servicio, PDO::PARAM_STR);
    $stmt->bindParam(':hora_inicio', $hora_inicio, PDO::PARAM_STR);
    $stmt->bindParam(':hora_fin', $hora_fin, PDO::PARAM_STR);
    $stmt->bindParam(':estatus', $estatus, PDO::PARAM_INT);
    return $stmt->execute();
}

function eliminarServicio($pdo, $id_servicio) {
    $sql = "DELETE FROM servicio WHERE id_servicio = :id_servicio";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_servicio', $id_servicio, PDO::PARAM_INT);
    return $stmt->execute();
}

// Manejo de las solicitudes POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        $id_usuario = $_POST['id_usuario'];
        $fecha_servicio = $_POST['fecha_servicio'];
        $hora_inicio = $_POST['hora_inicio'];
        $hora_fin = $_POST['hora_fin'];
        $estatus = $_POST['estatus'];
        if (crearServicio($pdo, $id_usuario, $fecha_servicio, $hora_inicio, $hora_fin, $estatus)) {
            header('Location: servicio.php');
        } else {
            header('Location: servicio.php');
        }
    } elseif (isset($_POST['update'])) {
        $id_servicio = $_POST['id_servicio'];
        $id_usuario = $_POST['id_usuario'];
        $fecha_servicio = $_POST['fecha_servicio'];
        $hora_inicio = $_POST['hora_inicio'];
        $hora_fin = $_POST['hora_fin'];
        $estatus = $_POST['estatus'];
        if (actualizarServicio($pdo, $id_servicio, $id_usuario, $fecha_servicio, $hora_inicio, $hora_fin, $estatus)) {
            header('Location: servicio.php');
        } else {
            header('Location: servicio.php');
        }
    } elseif (isset($_POST['delete'])) {
        $id_servicio = $_POST['id_servicio'];
        if (eliminarServicio($pdo, $id_servicio)) {
            header('Location: servicio.php');
        } else {
            header('Location: servicio.php');
        }
    }
    exit;
}
?>