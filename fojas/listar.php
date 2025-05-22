<?php
require_once '../db.php';

$caso_id = isset($_GET['caso_id']) ? intval($_GET['caso_id']) : 0;

$query = "SELECT foja.id, foja.observacion, foja.imagen, caso.numero_expediente, caso.nombre
          FROM foja
          JOIN caso ON foja.caso_id = caso.id
          WHERE caso.id = ?";

$stmt = mysqli_prepare($conexion, $query);
mysqli_stmt_bind_param($stmt, "i", $caso_id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Fojas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Listado de Fojas para el Caso: <?php echo htmlspecialchars($caso['numero_expediente'] . ' - ' . $caso['nombre']); ?></h2>

    <a href="crear.php" class="btn btn-success mb-3">➕ Nueva Foja</a>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Observación</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($foja = mysqli_fetch_assoc($resultado)) : ?>
                <tr id="fila-<?php echo $foja['id']; ?>">
                    <td><?php echo htmlspecialchars($foja['observacion']); ?></td>
                    <td>
                        <?php if ($foja['imagen']): ?>
                            <a href="<?php echo $foja['imagen']; ?>" target="_blank">Ver Imagen</a>
                        <?php else: ?>
                            Sin imagen
                        <?php endif; ?>
                    </td>
                    <td>
                        <!-- Aquí puedes agregar un botón de eliminar, si es necesario -->
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <?php mysqli_stmt_close($stmt); ?>
</body>
</html>
