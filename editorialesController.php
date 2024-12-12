<?php
require_once 'conexion.php';

function obtenerEditoriales($pdo) {
    $sql = "SELECT * FROM editoriales";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerEditorialPorId($pdo, $id_editorial) {
    $sql = "SELECT * FROM editoriales WHERE id_editorial = :id_editorial";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_editorial', $id_editorial, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function crearEditorial($pdo, $nombre, $direccion, $telefono, $correo) {
    $sql = "INSERT INTO editoriales (nombre, direccion, telefono, correo) VALUES (:nombre, :direccion, :telefono, :correo)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':direccion', $direccion, PDO::PARAM_STR);
    $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
    $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
    return $stmt->execute();
}

function actualizarEditorial($pdo, $id_editorial, $nombre, $direccion, $telefono, $correo) {
    $sql = "UPDATE editoriales SET nombre = :nombre, direccion = :direccion, telefono = :telefono, correo = :correo WHERE id_editorial = :id_editorial";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_editorial', $id_editorial, PDO::PARAM_INT);
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':direccion', $direccion, PDO::PARAM_STR);
    $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
    $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
    return $stmt->execute();
}

function eliminarEditorial($pdo, $id_editorial) {
    $sql = "DELETE FROM editoriales WHERE id_editorial = :id_editorial";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_editorial', $id_editorial, PDO::PARAM_INT);
    return $stmt->execute();
}

// Manejo de las solicitudes POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        $nombre = $_POST['nombre'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $correo = $_POST['correo'];
        if (crearEditorial($pdo, $nombre, $direccion, $telefono, $correo)) {
            header('Location: editoriales.php?success=created');
        } else {
            header('Location: editoriales.php?error=1');
        }
    } elseif (isset($_POST['update'])) {
        $id_editorial = $_POST['id_editorial'];
        $nombre = $_POST['nombre'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $correo = $_POST['correo'];
        if (actualizarEditorial($pdo, $id_editorial, $nombre, $direccion, $telefono, $correo)) {
            header('Location: editoriales.php?success=updated');
        } else {
            header('Location: editoriales.php?error=1');
        }
    } elseif (isset($_POST['delete'])) {
        $id_editorial = $_POST['id_editorial'];
        if (eliminarEditorial($pdo, $id_editorial)) {
            header('Location: editoriales.php?success=deleted');
        } else {
            header('Location: editoriales.php?error=1');
        }
    }
    exit;
}
?>