<?php
require_once 'conexion.php';

// librosController.php
function obtenerLibros($pdo) {
    $sql = "SELECT l.*, a.nombre AS autor_nombre, a.apellido AS autor_apellido, e.nombre AS editorial_nombre 
            FROM libros l 
            JOIN autores a ON l.id_autor = a.id_autor 
            JOIN editoriales e ON l.id_editorial = e.id_editorial";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerLibroPorId($pdo, $id_libro) {
    $sql = "SELECT * FROM libros WHERE id_libro = :id_libro";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_libro', $id_libro, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function crearLibro($pdo, $titulo, $materia, $codigo_barras, $codigo_color, $signatura, $cantidad, $id_autor, $id_editorial, $estatus) {
    $sql = "INSERT INTO libros (titulo, materia, codigo_barras, codigo_color, signatura, cantidad, id_autor, id_editorial, estatus) VALUES (:titulo, :materia, :codigo_barras, :codigo_color, :signatura, :cantidad, :id_autor, :id_editorial, :estatus)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
    $stmt->bindParam(':materia', $materia, PDO::PARAM_STR);
    $stmt->bindParam(':codigo_barras', $codigo_barras, PDO::PARAM_STR);
    $stmt->bindParam(':codigo_color', $codigo_color, PDO::PARAM_STR);
    $stmt->bindParam(':signatura', $signatura, PDO::PARAM_STR);
    $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
    $stmt->bindParam(':id_autor', $id_autor, PDO::PARAM_INT);
    $stmt->bindParam(':id_editorial', $id_editorial, PDO::PARAM_INT);
    $stmt->bindParam(':estatus', $estatus, PDO::PARAM_INT);
    return $stmt->execute();
}

function actualizarLibro($pdo, $id_libro, $titulo, $materia, $codigo_barras, $codigo_color, $signatura, $cantidad, $id_autor, $id_editorial, $estatus) {
    $sql = "UPDATE libros SET titulo = :titulo, materia = :materia, codigo_barras = :codigo_barras, codigo_color = :codigo_color, signatura = :signatura, cantidad = :cantidad, id_autor = :id_autor, id_editorial = :id_editorial, estatus = :estatus WHERE id_libro = :id_libro";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_libro', $id_libro, PDO::PARAM_INT);
    $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
    $stmt->bindParam(':materia', $materia, PDO::PARAM_STR);
    $stmt->bindParam(':codigo_barras', $codigo_barras, PDO::PARAM_STR);
    $stmt->bindParam(':codigo_color', $codigo_color, PDO::PARAM_STR);
    $stmt->bindParam(':signatura', $signatura, PDO::PARAM_STR);
    $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
    $stmt->bindParam(':id_autor', $id_autor, PDO::PARAM_INT);
    $stmt->bindParam(':id_editorial', $id_editorial, PDO::PARAM_INT);
    $stmt->bindParam(':estatus', $estatus, PDO::PARAM_INT);
    return $stmt->execute();
}

function eliminarLibro($pdo, $id_libro) {
    $sql = "DELETE FROM libros WHERE id_libro = :id_libro";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_libro', $id_libro, PDO::PARAM_INT);
    return $stmt->execute();
}

// Manejo de las solicitudes POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        $titulo = $_POST['titulo'];
        $materia = $_POST['materia'];
        $codigo_barras = $_POST['codigo_barras'];
        $codigo_color = $_POST['codigo_color'];
        $signatura = $_POST['signatura'];
        $cantidad = $_POST['cantidad'];
        $id_autor = $_POST['id_autor'];
        $id_editorial = $_POST['id_editorial'];
        $estatus = $_POST['estatus'];
        if (crearLibro($pdo, $titulo, $materia, $codigo_barras, $codigo_color, $signatura, $cantidad, $id_autor, $id_editorial, $estatus)) {
            header('Location: libros.php?success=created');
        } else {
            header('Location: libros.php?error=1');
        }
    } elseif (isset($_POST['update'])) {
        $id_libro = $_POST['id_libro'];
        $titulo = $_POST['titulo'];
        $materia = $_POST['materia'];
        $codigo_barras = $_POST['codigo_barras'];
        $codigo_color = $_POST['codigo_color'];
        $signatura = $_POST['signatura'];
        $cantidad = $_POST['cantidad'];
        $id_autor = $_POST['id_autor'];
        $id_editorial = $_POST['id_editorial'];
        $estatus = $_POST['estatus'];
        if (actualizarLibro($pdo, $id_libro, $titulo, $materia, $codigo_barras, $codigo_color, $signatura, $cantidad, $id_autor, $id_editorial, $estatus)) {
            header('Location: libros.php?success=updated');
        } else {
            header('Location: libros.php?error=1');
        }
    } elseif (isset($_POST['delete'])) {
        $id_libro = $_POST['id_libro'];
        if (eliminarLibro($pdo, $id_libro)) {
            header('Location: libros.php?success=deleted');
        } else {
            header('Location: libros.php?error=1');
        }
    }
    exit;
}
?>