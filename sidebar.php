<!-- sidebar.php -->
<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

?>
<div class="sidebar">
<div class="logo-container">
        <img src="logo.jpg" alt="MyBiblioTecZamora Logo" class="logo">
    </div>
    <a href="dashboard.php"><i class="fa fa-home"></i> Inicio</a>

    <!-- Submenú de Gestión de Usuarios -->
    <div class="dropdown">
        <a href="#" class="dropdown-toggle"><i class="fa fa-users"></i> Gestión de Usuarios</a>
        <div class="dropdown-menu">
            <a href="usuarios.php"><i class="fa fa-users"></i> Gestionar Personal</a>
            <a href="servicio.php"><i class="fa fa-clock"></i> Gestionar Servicio</a>
            <a href="cartasnoadeudo.php"><i class="fa fa-inbox"></i> Gestionar Cartas</a>
        </div>
    </div>

    <!-- Submenú de Gestión de Libros -->
    <div class="dropdown">
        <a href="#" class="dropdown-toggle"><i class="fa fa-book"></i> Gestión de Libros</a>
        <div class="dropdown-menu">
            <a href="libros.php"><i class="fa fa-book"></i> Gestionar Libros</a>
            <a href="autores.php"><i class="fa fa-user"></i> Gestionar Autores</a>
            <a href="editoriales.php"><i class="fa fa-pencil"></i> Gestionar Editoriales</a>
        </div>
    </div>

    <!-- Submenú de Préstamos y Penalizaciones -->
    <div class="dropdown">
        <a href="#" class="dropdown-toggle"><i class="fa fa-ban"></i> Préstamos y Penalizaciones</a>
        <div class="dropdown-menu">
            <a href="prestamos.php"><i class="fa fa-home"></i> Gestionar Préstamos</a>
            <a href="extravios.php"><i class="fa fa-minus-circle"></i> Gestionar Extravíos</a>
            <a href="multas.php"><i class="fa fa-credit-card"></i> Gestionar Multas</a>
        </div>
    </div>

    <!-- Submenú de Gestión de Espacios -->
    <div class="dropdown">
        <a href="#" class="dropdown-toggle"><i class="fa fa-coffee"></i> Gestión de Espacios</a>
        <div class="dropdown-menu">
            <a href="cubiculos.php"><i class="fa fa-coffee"></i> Gestionar Cubículos</a>
            <a href="reservasCubiculos.php"><i class="fa fa-clock"></i> Gestionar Horarios</a>
        </div>
    </div>

    <div class="dropdown">
        <a href="#" class="dropdown-toggle"><i class="fa fa-book"></i> Gestión de Reportes</a>
        <div class="dropdown-menu">
            <a href="reporteUsuarios.php"><i class="fa fa-users"></i> Reportes Usuarios</a>
            <a href="reporteLibros.php"><i class="fa fa-book"></i> Reportes libros</a>
            <a href="autoresReporte.php"><i class="fa fa-user"></i> Reportes Autores</a>
            <a href="reporteMultas.php"><i class="fa fa-credit-card"></i> Reportes Multas</a>
            <a href="reportePrestamos.php"><i class="fa fa-home"></i> Reportes Prestamos</a> 
            <a href="reporteServicios.php"><i class="fa fa-calendar-check"></i> Reportes Servicios</a> 
        </div>
    </div>

    <!-- Cerrar Sesión -->
    <a href="logout.php"><i class="fa fa-sign-out-alt"></i> Cerrar Sesión</a>
    <!-- Al final de la barra lateral, antes de cerrar el div -->
<div class="sidebar-bottom">
    <img src="LogoZamora.png" alt="Imagen de fondo" class="sidebar-image">
</div>

</div>

<style>

    /* Estilo para la imagen en la parte inferior */
.sidebar-bottom {
    position: absolute;
    bottom: 0;
    width: 100%;
    text-align: center;
    padding: 10px 0;
}

.sidebar-image {
    width: 75%; /* Ajusta el ancho de la imagen al contenedor */
    object-fit: cover; /* Asegura que la imagen cubra todo el contenedor sin distorsionarse */
    max-height: 100px; 
}

    
     /* Estilos para el contenedor del logo */
     .logo-container {
        text-align: center;
        margin-bottom: 20px;
    }

    /* Estilos para la imagen del logo */
    .logo {
        max-width: 100%; /* Ajusta el ancho máximo al contenedor */
        max-height: 100px; /* Ajusta el alto máximo */
        object-fit: contain; /* Escala la imagen sin distorsionarla */
    }


    /* General styles for the sidebar */
    .sidebar {
        height: 100vh;
        background-color: #6a0dad; /* Black background */
        padding: 15px;
        color: white;
        position: fixed;
        width: 300px;
        overflow-y: auto;
        font-family: Arial, sans-serif;
    }

    /* Title styles */
    .sidebar h3 {
        color: #f8f9fa;
        font-size: 20px;
        margin-bottom: 20px;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    /* Links */
    .sidebar a {
        color: white; /* White text for links */
        text-decoration: none;
        display: block;
        margin-bottom: 10px;
        padding: 10px;
        border-radius: 5px;
        transition: background-color 0.3s, padding-left 0.3s;
    }

    /* Hover effects */
    .sidebar a:hover {
        background-color: #495057;
        padding-left: 15px; /* Subtle padding animation */
    }

    /* Dropdown styles */
    .dropdown {
        margin-bottom: 15px;
    }

    .dropdown-toggle {
        cursor: pointer;
        font-weight: bold;
        display: block;
        padding: 10px;
        border-radius: 5px;
    }

    /* Dropdown menu styles */
  /* Dropdown menu styles */
.dropdown-menu {
    display: none;
    padding-left: 10px;
    margin-top: 5px;
    background-color: #343a40; /* Fondo oscuro consistente con la barra lateral */
    border-radius: 5px; /* Bordes redondeados */
    padding: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Sombra para mejor apariencia */
}

.dropdown-menu a {
    margin-bottom: 5px;
    padding: 8px;
    border-radius: 5px;
    color: white; /* Asegura que las letras sean visibles */
    background-color: transparent; /* Fondo transparente por defecto */
    transition: background-color 0.3s;
}

.dropdown-menu a:hover {
    background-color: #495057; /* Color de fondo al pasar el cursor */
}


    /* Active state for dropdown */
    .dropdown-toggle.active {
        background-color: #495057;
        padding-left: 15px;
    }
    
</style>


<script>
    // Toggle dropdown menus
    document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
        toggle.addEventListener('click', function () {
            const dropdownMenu = this.nextElementSibling;

            // Toggle the menu visibility
            const isVisible = dropdownMenu.style.display === 'block';
            dropdownMenu.style.display = isVisible ? 'none' : 'block';

            // Add active state
            this.classList.toggle('active', !isVisible);
        });
    });
</script>
