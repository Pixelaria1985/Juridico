<?php
require_once '../db.php';

// Obtener los abogados, clientes y especialidades para mostrarlos en el formulario
$abogadosQuery = "SELECT id, nombre, apellido FROM abogado ORDER BY apellido ASC";
$clientesQuery = "SELECT id, nombre, apellido FROM cliente ORDER BY apellido ASC";
$especialidadesQuery = "SELECT id, nombre FROM especialidad ORDER BY nombre ASC";

$abogados = mysqli_query($conexion, $abogadosQuery);
$clientes = mysqli_query($conexion, $clientesQuery);
$especialidades = mysqli_query($conexion, $especialidadesQuery);

$mensaje = "";

// Si el formulario es enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $abogado_id        = $_POST['abogado_id'];
    $cliente_id        = $_POST['cliente_id'];
    $especialidad_id   = $_POST['especialidad_id'];
    $numero_expediente = $_POST['numero_expediente'];
    $nombre            = $_POST['nombre'];
    $estado            = $_POST['estado'];

    // Validación básica
    if (!empty($abogado_id) && !empty($cliente_id) && !empty($especialidad_id) && !empty($numero_expediente) && !empty($nombre)) {
        $query = "INSERT INTO caso (abogado_id, cliente_id, especialidad_id, numero_expediente, nombre, estado) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, "iiisss", $abogado_id, $cliente_id, $especialidad_id, $numero_expediente, $nombre, $estado);

        if (mysqli_stmt_execute($stmt)) {
            $mensaje = "✅ Caso creado correctamente.";
        } else {
            $mensaje = "❌ Error al crear caso: " . mysqli_error($conexion);
        }

        mysqli_stmt_close($stmt);
    } else {
        $mensaje = "⚠️ Todos los campos son obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Caso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Crear Nuevo Caso</h2>

    <?php if ($mensaje): ?>
        <div class="alert alert-info"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label>Abogado *</label>
            <select name="abogado_id" class="form-select" required>
                <option value="">Seleccione un abogado</option>
                <?php while ($abogado = mysqli_fetch_assoc($abogados)): ?>
                    <option value="<?php echo $abogado['id']; ?>"><?php echo htmlspecialchars($abogado['apellido'] . ', ' . $abogado['nombre']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Cliente *</label>
            <select name="cliente_id" class="form-select" required>
                <option value="">Seleccione un cliente</option>
                <?php while ($cliente = mysqli_fetch_assoc($clientes)): ?>
                    <option value="<?php echo $cliente['id']; ?>"><?php echo htmlspecialchars($cliente['apellido'] . ', ' . $cliente['nombre']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Especialidad *</label>
            <select name="especialidad_id" class="form-select" required>
                <option value="">Seleccione una especialidad</option>
                <?php while ($especialidad = mysqli_fetch_assoc($especialidades)): ?>
                    <option value="<?php echo $especialidad['id']; ?>"><?php echo htmlspecialchars($especialidad['nombre']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Número de Expediente *</label>
            <input type="text" name="numero_expediente" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Nombre del Caso *</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Estado *</label>
            <select name="estado" class="form-select" required>
                <option value="Abierto">Abierto</option>
                <option value="Cerrado">Cerrado</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Crear Caso</button>
        <a href="listar.php" class="btn btn-secondary">Volver</a>
    </form>
</body>
</html>
