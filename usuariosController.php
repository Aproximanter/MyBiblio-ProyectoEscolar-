<?php
require_once 'conexion.php';

// Inicializar variables de notificación
$notificacion = '';
$tipo_notificacion = '';

// Crear un nuevo usuario
if (isset($_POST['create'])) {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $no_de_control = $_POST['no_de_control'];
    $estatus = isset($_POST['estatus']) ? 1 : 0;

    $sql = "INSERT INTO usuarios (nombre, apellido, correo, telefono, direccion, no_de_control, estatus) 
            VALUES (:nombre, :apellido, :correo, :telefono, :direccion, :no_de_control, :estatus)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':apellido', $apellido);
    $stmt->bindParam(':correo', $correo);
    $stmt->bindParam(':telefono', $telefono);
    $stmt->bindParam(':direccion', $direccion);
    $stmt->bindParam(':no_de_control', $no_de_control);
    $stmt->bindParam(':estatus', $estatus, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $notificacion = "Usuario agregado exitosamente.";
        $tipo_notificacion = "success";
        header("Location: usuarios.php"); // Redireccionar a usuarios.php
        exit();
    } else {
        $notificacion = "Error al agregar el usuario.";
        $tipo_notificacion = "danger";
    }
}

// Leer todos los usuarios
function obtenerUsuarios($pdo) {
    $sql = "SELECT * FROM usuarios WHERE estatus = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Actualizar un usuario
if (isset($_POST['update'])) {
    $id_usuario = $_POST['id_usuario'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $no_de_control = $_POST['no_de_control'];
    $estatus = isset($_POST['estatus']) ? 1 : 0;

    $sql = "UPDATE usuarios 
            SET nombre = :nombre, apellido = :apellido, correo = :correo, telefono = :telefono, 
                direccion = :direccion, no_de_control = :no_de_control, estatus = :estatus 
            WHERE id_usuario = :id_usuario";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':apellido', $apellido);
    $stmt->bindParam(':correo', $correo);
    $stmt->bindParam(':telefono', $telefono);
    $stmt->bindParam(':direccion', $direccion);
    $stmt->bindParam(':no_de_control', $no_de_control);
    $stmt->bindParam(':estatus', $estatus, PDO::PARAM_INT);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $notificacion = "Usuario actualizado exitosamente.";
        $tipo_notificacion = "success";
        header("Location: usuarios.php"); // Redireccionar a usuarios.php
        exit();
    } else {
        $notificacion = "Error al actualizar el usuario.";
        $tipo_notificacion = "danger";
    }
}

// Eliminar un usuario
if (isset($_POST['delete'])) {
    $id_usuario = $_POST['id_usuario'];

    $sql = "UPDATE usuarios SET estatus = 0 WHERE id_usuario = :id_usuario";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $notificacion = "Usuario eliminado exitosamente.";
        $tipo_notificacion = "success";
        header("Location: usuarios.php"); // Redireccionar a usuarios.php
        exit();
    } else {
        $notificacion = "Error al eliminar el usuario.";
        $tipo_notificacion = "danger";
    }
}
// usuariosController.php

function obtenerUsuarioPorId($pdo, $id_usuario) {
    $sql = "SELECT * FROM usuarios WHERE id_usuario = :id_usuario";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Otras funciones como obtenerUsuarios, etc.
?>
