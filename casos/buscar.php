<?php
require_once '../db.php';

$mensaje = "";
$caso = null;
$fojas = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numero_expediente = $_POST['numero_expediente'];

    if (!empty($numero_expediente)) {
        // Buscar el caso por el número de expediente
        $query = "SELECT caso.id, caso.numero_expediente, caso.nombre, caso.estado, 
                         abogado.nombre AS abogado_nombre, cliente.nombre AS cliente_nombre, especialidad.nombre AS especialidad_nombre
                  FROM caso
                  JOIN abogado ON caso.abogado_id = abogado.id
                  JOIN cliente ON caso.cliente_id = cliente.id
                  JOIN especialidad ON caso.especialidad_id = especialidad.id
                  WHERE caso.numero_expediente = ?";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, "s", $numero_expediente);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        $caso = mysqli_fetch_assoc($resultado);

        // Si el caso existe, obtener las fojas asociadas
        if ($caso) {
            $fojasQuery = "SELECT foja.id, foja.observacion, foja.imagen FROM foja WHERE foja.caso_id = ?";
            $fojasStmt = mysqli_prepare($conexion, $fojasQuery);
            mysqli_stmt_bind_param($fojasStmt, "i", $caso['id']);
            mysqli_stmt_execute($fojasStmt);
            $fojasResult = mysqli_stmt_get_result($fojasStmt);
            $fojas = mysqli_fetch_all($fojasResult, MYSQLI_ASSOC);
        } else {
            $mensaje = "❌ No se encontró el caso con ese número de expediente.";
        }
    } else {
        $mensaje = "⚠️ Debes ingresar un número de expediente.";
    }
}

// Cambiar el estado del caso (Abierto / Cerrado)
if (isset($_GET['cambiar_estado']) && isset($_GET['caso_id'])) {
    $caso_id = $_GET['caso_id'];
    $nuevo_estado = ($_GET['estado'] == 'Abierto') ? 'Cerrado' : 'Abierto';

    $updateQuery = "UPDATE caso SET estado = ? WHERE id = ?";
    $updateStmt = mysqli_prepare($conexion, $updateQuery);
    mysqli_stmt_bind_param($updateStmt, "si", $nuevo_estado, $caso_id);

    if (mysqli_stmt_execute($updateStmt)) {
        header("Location: buscar.php?estado_cambiado=true&numero_expediente=" . $caso['numero_expediente']);
        exit();
    } else {
        $mensaje = "❌ Error al cambiar el estado del caso.";
    }

    mysqli_stmt_close($updateStmt);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Buscar Caso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Buscar Caso</h2>

    <form method="POST" action="">
        <div class="mb-3">
            <label>Número de Expediente *</label>
            <input type="text" name="numero_expediente" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>

    <?php if ($mensaje): ?>
        <div class="alert alert-info mt-3"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <?php if ($caso): ?>
        <div class="mt-4">
            <h4>Detalles del Caso</h4>
            <ul class="list-group">
                <li class="list-group-item"><strong>Expediente:</strong> <?php echo htmlspecialchars($caso['numero_expediente']); ?></li>
                <li class="list-group-item"><strong>Nombre del Caso:</strong> <?php echo htmlspecialchars($caso['nombre']); ?></li>
                <li class="list-group-item"><strong>Abogado:</strong> <?php echo htmlspecialchars($caso['abogado_nombre']); ?></li>
                <li class="list-group-item"><strong>Cliente:</strong> <?php echo htmlspecialchars($caso['cliente_nombre']); ?></li>
                <li class="list-group-item"><strong>Especialidad:</strong> <?php echo htmlspecialchars($caso['especialidad_nombre']); ?></li>
                <li class="list-group-item"><strong>Estado:</strong> 
                    <span class="badge <?php echo $caso['estado'] == 'Abierto' ? 'bg-success' : 'bg-danger'; ?>">
                        <?php echo htmlspecialchars($caso['estado']); ?>
                    </span>
                    <a href="buscar.php?cambiar_estado=true&caso_id=<?php echo $caso['id']; ?>&estado=<?php echo $caso['estado']; ?>" class="btn btn-link">Cambiar Estado</a>
                </li>
            </ul>
        </div>

        <h4 class="mt-4">Fojas Asociadas</h4>
        <?php if (count($fojas) > 0): ?>
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Observación</th>
                        <th>Imagen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($fojas as $foja): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($foja['observacion']); ?></td>
                            <td>
                                <?php if ($foja['imagen']): ?>
                                    <a href="<?php echo $foja['imagen']; ?>" target="_blank">Ver Imagen</a>
                                <?php else: ?>
                                    Sin imagen
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay fojas asociadas a este caso.</p>
        <?php endif; ?>
    <?php endif; ?>

</body>
</html>
