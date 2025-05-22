<?php
require_once '../db.php';

$mensaje = "";

// Si el formulario es enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];

    // Validación básica
    if (!empty($nombre)) {
        $query = "INSERT INTO especialidad (nombre) VALUES (?)";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, "s", $nombre);

        if (mysqli_stmt_execute($stmt)) {
            $mensaje = "✅ Especialidad creada correctamente.";
        } else {
            $mensaje = "❌ Error al crear especialidad: " . mysqli_error($conexion);
        }

        mysqli_stmt_close($stmt);
    } else {
        $mensaje = "⚠️ El nombre de la especialidad es obligatorio.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Especialidad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Crear Nueva Especialidad</h2>

    <?php if ($mensaje): ?>
        <div class="alert alert-info"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label>Nombre *</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Crear Especialidad</button>
        <a href="listar.php" class="btn btn-secondary">Volver</a>
    </form>
</body>
</html>
