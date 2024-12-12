<?php
require_once 'conexion.php';

// multasController.php
function obtenerMultas($pdo) {
    $sql = "SELECT m.*, u.nombre AS usuario_nombre, u.apellido AS usuario_apellido 
            FROM multas m 
            JOIN usuarios u ON m.id_usuario = u.id_usuario";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerMultaPorId($pdo, $id_multa) {
    $sql = "SELECT * FROM multas WHERE id_multa = :id_multa";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_multa', $id_multa, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function crearMulta($pdo, $id_usuario, $monto, $descripcion, $estatus) {
    $sql = "INSERT INTO multas (id_usuario, monto, descripcion, estatus) VALUES (:id_usuario, :monto, :descripcion, :estatus)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->bindParam(':monto', $monto, PDO::PARAM_STR);
    $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
    $stmt->bindParam(':estatus', $estatus, PDO::PARAM_INT);
    return $stmt->execute();
}

function actualizarMulta($pdo, $id_multa, $id_usuario, $monto, $descripcion, $estatus) {
    $sql = "UPDATE multas SET id_usuario = :id_usuario, monto = :monto, descripcion = :descripcion, estatus = :estatus WHERE id_multa = :id_multa";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_multa', $id_multa, PDO::PARAM_INT);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->bindParam(':monto', $monto, PDO::PARAM_STR);
    $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
    $stmt->bindParam(':estatus', $estatus, PDO::PARAM_INT);
    return $stmt->execute();
}

function eliminarMulta($pdo, $id_multa) {
    $sql = "DELETE FROM multas WHERE id_multa = :id_multa";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_multa', $id_multa, PDO::PARAM_INT);
    return $stmt->execute();
}

// Manejo de las solicitudes POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        $id_usuario = $_POST['id_usuario'];
        $monto = $_POST['monto'];
        $descripcion = $_POST['descripcion'];
        $estatus = $_POST['estatus'];
        if (crearMulta($pdo, $id_usuario, $monto, $descripcion, $estatus)) {
            header('Location: multas.php?success=created');
        } else {
            header('Location: multas.php?error=1');
        }
    } elseif (isset($_POST['update'])) {
        $id_multa = $_POST['id_multa'];
        $id_usuario = $_POST['id_usuario'];
        $monto = $_POST['monto'];
        $descripcion = $_POST['descripcion'];
        $estatus = $_POST['estatus'];
        if (actualizarMulta($pdo, $id_multa, $id_usuario, $monto, $descripcion, $estatus)) {
            header('Location: multas.php?success=updated');
        } else {
            header('Location: multas.php?error=1');
        }
    } elseif (isset($_POST['delete'])) {
        $id_multa = $_POST['id_multa'];
        if (eliminarMulta($pdo, $id_multa)) {
            header('Location: multas.php?success=deleted');
        } else {
            header('Location: multas.php?error=1');
        }
    }
    exit;
}
?>