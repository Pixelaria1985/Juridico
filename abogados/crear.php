<?php
require_once '../db.php';

// Verificar si se envió el formulario
$mensaje = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre    = $_POST['nombre'];
    $apellido  = $_POST['apellido'];
    $direccion = $_POST['direccion'];
    $telefono  = $_POST['telefono'];
    $celular   = $_POST['celular'];

    // Validación mínima (puede ampliarse)
    if (!empty($nombre) && !empty($apellido)) {
        $sql = "INSERT INTO abogado (nombre, apellido, direccion, telefono, celular)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "sssss", $nombre, $apellido, $direccion, $telefono, $celular);
        if (mysqli_stmt_execute($stmt)) {
            $mensaje = "✅ Abogado guardado correctamente.";
        } else {
            $mensaje = "❌ Error al guardar: " . mysqli_error($conexion);
        }
        mysqli_stmt_close($stmt);
    } else {
        $mensaje = "⚠️ Nombre y Apellido son obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Abogado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Registrar Nuevo Abogado</h2>

    <?php if ($mensaje): ?>
        <div class="alert alert-info"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label>Nombre *</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Apellido *</label>
            <input type="text" name="apellido" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Dirección</label>
            <input type="text" name="direccion" class="form-control">
        </div>
        <div class="mb-3">
            <label>Teléfono</label>
            <input type="text" name="telefono" class="form-control">
        </div>
        <div class="mb-3">
            <label>Celular</label>
            <input type="text" name="celular" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Guardar Abogado</button>
        <a href="listar.php" class="btn btn-secondary">Volver</a>
    </form>
</body>
</html>
