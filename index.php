<?php
// Incluimos la conexión a la base de datos si es necesario
require_once 'db.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1 class="mb-4">Panel de Administración</h1>

    <!-- Menú de navegación principal -->
    <div class="list-group">
        <a href="abogados/listar.php" class="list-group-item list-group-item-action">
            Ver Abogados
        </a>
        <a href="clientes/listar.php" class="list-group-item list-group-item-action">
            Ver Clientes
        </a>
        <a href="especialidades/listar.php" class="list-group-item list-group-item-action">
            Ver Especialidades
        </a>
        <a href="casos/crear.php" class="list-group-item list-group-item-action">
            Crear Caso
        </a>
        <a href="fojas/crear.php" class="list-group-item list-group-item-action">
            Crear Foja
        </a>
        <a href="estadisticas.php" class="list-group-item list-group-item-action">
            Ver Estadísticas
        </a>
    </div>

    <!-- Mensaje informativo (opcional) -->
    <div class="mt-4 alert alert-info">
        <strong>Bienvenido al sistema de gestión de casos legales.</strong><br>
        Desde aquí podrás gestionar abogados, clientes, casos y mucho más.
    </div>

</body>
</html>
