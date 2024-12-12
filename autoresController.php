<?php
require_once 'conexion.php';

// Crear un nuevo autor
if (isset($_POST['create'])) {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $nacionalidad = $_POST['nacionalidad'];

    $sql = "INSERT INTO autores (nombre, apellido, fecha_nacimiento, nacionalidad, estatus) 
            VALUES (:nombre, :apellido, :fecha_nacimiento, :nacionalidad, 1)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':apellido', $apellido);
    $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento);
    $stmt->bindParam(':nacionalidad', $nacionalidad);

    if ($stmt->execute()) {
        header("Location: autores.php?success=created");
    } else {
        header("Location: autores.php?error=failed");
    }
}

// Leer todos los autores
function obtenerAutores($pdo) {
    $sql = "SELECT * FROM autores WHERE estatus = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Actualizar un autor
if (isset($_POST['update'])) {
    $id_autor = $_POST['id_autor'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $nacionalidad = $_POST['nacionalidad'];

    $sql = "UPDATE autores SET nombre = :nombre, apellido = :apellido, fecha_nacimiento = :fecha_nacimiento, nacionalidad = :nacionalidad WHERE id_autor = :id_autor";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':apellido', $apellido);
    $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento);
    $stmt->bindParam(':nacionalidad', $nacionalidad);
    $stmt->bindParam(':id_autor', $id_autor);

    if ($stmt->execute()) {
        header("Location: autores.php?success=updated");
    } else {
        header("Location: autores.php?error=failed");
    }
}

// Eliminar un autor
if (isset($_POST['delete'])) {
    $id_autor = $_POST['id_autor'];

    $sql = "UPDATE autores SET estatus = 0 WHERE id_autor = :id_autor";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_autor', $id_autor);

    if ($stmt->execute()) {
        header("Location: autores.php?success=deleted");
    } else {
        header("Location: autores.php?error=failed");
    }
}


// autoresController.php

function obtenerAutorPorId($pdo, $id_autor) {
    $sql = "SELECT * FROM autores WHERE id_autor = :id_autor";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_autor', $id_autor, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


?>
