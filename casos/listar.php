<?php
require_once '../db.php';

$query = "SELECT caso.id, caso.numero_expediente, caso.nombre, caso.estado, 
                 abogado.nombre AS abogado_nombre, cliente.nombre AS cliente_nombre, especialidad.nombre AS especialidad_nombre
          FROM caso
          JOIN abogado ON caso.abogado_id = abogado.id
          JOIN cliente ON caso.cliente_id = cliente.id
          JOIN especialidad ON caso.especialidad_id = especialidad.id
          ORDER BY caso.id DESC";
$resultado = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Casos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Listado de Casos</h2>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Expediente</th>
                <th>Nombre</th>
                <th>Abogado</th>
                <th>Cliente</th>
                <th>Especialidad</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($fila = mysqli_fetch_assoc($resultado)) : ?>
                <tr id="fila-<?php echo $fila['id']; ?>">
                    <td><?php echo htmlspecialchars($fila['numero_expediente']); ?></td>
                    <td><?php echo htmlspecialchars($fila['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($fila['abogado_nombre']); ?></td>
                    <td><?php echo htmlspecialchars($fila['cliente_nombre']); ?></td>
                    <td><?php echo htmlspecialchars($fila['especialidad_nombre']); ?></td>
                    <td class="<?php echo $fila['estado'] === 'Abierto' ? 'text-success' : 'text-danger'; ?>">
                        <?php echo htmlspecialchars($fila['estado']); ?>
                    </td>
                    <td>
                        <a href="editar.php?id=<?php echo $fila['id']; ?>" class="btn btn-warning btn-sm">✏️ Modificar</a>
                        <!-- Aquí se podría añadir la opción de eliminar -->
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
