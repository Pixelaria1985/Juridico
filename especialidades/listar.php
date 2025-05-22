<?php
require_once '../db.php';

$query = "SELECT * FROM especialidad ORDER BY nombre ASC";
$resultado = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Especialidades</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="container mt-5">
    <h2>Listado de Especialidades</h2>

    <a href="crear.php" class="btn btn-success mb-3">➕ Nueva Especialidad</a>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($fila = mysqli_fetch_assoc($resultado)) : ?>
                <tr id="fila-<?php echo $fila['id']; ?>">
                    <td><?php echo htmlspecialchars($fila['nombre']); ?></td>
                    <td>
                        <button class="btn btn-danger btn-sm eliminar-btn" data-id="<?php echo $fila['id']; ?>">🗑️ Eliminar</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <script src="../js/especialidades.js"></script>
</body>
</html>
