<?php
require_once '../db.php';

$filtro = "";
if (isset($_GET['buscar']) && !empty(trim($_GET['buscar']))) {
    $filtro = trim($_GET['buscar']);
    $query = "SELECT * FROM cliente WHERE nombre LIKE ?";
    $stmt = mysqli_prepare($conexion, $query);
    $buscar = "%" . $filtro . "%";
    mysqli_stmt_bind_param($stmt, "s", $buscar);
} else {
    $query = "SELECT * FROM cliente ORDER BY apellido ASC";
    $stmt = mysqli_prepare($conexion, $query);
}
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="container mt-5">
    <h2>Listado de Clientes</h2>

    <form class="mb-4" method="GET" action="">
        <div class="input-group">
            <input type="text" name="buscar" class="form-control" placeholder="Buscar por nombre" value="<?php echo htmlspecialchars($filtro); ?>">
            <button class="btn btn-outline-primary" type="submit">Buscar</button>
            <a href="crear.php" class="btn btn-success ms-2">➕ Nuevo Cliente</a>
        </div>
    </form>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Celular</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($fila = mysqli_fetch_assoc($resultado)) : ?>
                <tr id="fila-<?php echo $fila['id']; ?>">
                    <td><?php echo htmlspecialchars($fila['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($fila['apellido']); ?></td>
                    <td><?php echo htmlspecialchars($fila['direccion']); ?></td>
                    <td><?php echo htmlspecialchars($fila['telefono']); ?></td>
                    <td><?php echo htmlspecialchars($fila['celular']); ?></td>
                    <td>
                        <a href="editar.php?id=<?php echo $fila['id']; ?>" class="btn btn-warning btn-sm">✏️ Modificar</a>
                        <button class="btn btn-danger btn-sm eliminar-btn" data-id="<?php echo $fila['id']; ?>">🗑️ Eliminar</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <script src="../js/clientes.js"></script>
</body>
</html>
