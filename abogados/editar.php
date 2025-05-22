<?php
require_once '../db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$mensaje = "";

// Si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id        = intval($_POST['id']);
    $nombre    = $_POST['nombre'];
    $apellido  = $_POST['apellido'];
    $direccion = $_POST['direccion'];
    $telefono  = $_POST['telefono'];
    $celular   = $_POST['celular'];

    if (!empty($nombre) && !empty($apellido)) {
        $query = "UPDATE abogado SET nombre=?, apellido=?, direccion=?, telefono=?, celular=? WHERE id=?";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, "sssssi", $nombre, $apellido, $direccion, $telefono, $celular, $id);

        if (mysqli_stmt_execute($stmt)) {
            $mensaje = "✅ Abogado actualizado correctamente.";
        } else {
            $mensaje = "❌ Error al actualizar: " . mysqli_error($conexion);
        }

        mysqli_stmt_close($stmt);
    } else {
        $mensaje = "⚠️ Nombre y apellido son obligatorios.";
    }
}

// Obtener datos actuales si es la primera carga
$query = "SELECT * FROM abogado WHERE id = ?";
$stmt = mysqli_prepare($conexion, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$abogado = mysqli_fetch_assoc($resultado);

if (!$abogado) {
    echo "<div class='alert alert-danger'>Abogado no encontrado.</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Abogado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Editar Abogado</h2>

    <?php if ($mensaje): ?>
        <div class="alert alert-info"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="hidden" name="id" value="<?php echo $abogado['id']; ?>">

        <div class="mb-3">
            <label>Nombre *</label>
            <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($abogado['nombre']); ?>" required>
        </div>

        <div class="mb-3">
            <label>Apellido *</label>
            <input type="text" name="apellido" class="form-control" value="<?php echo htmlspecialchars($abogado['apellido']); ?>" required>
        </div>

        <div class="mb-3">
            <label>Dirección</label>
            <input type="text" name="direccion" class="form-control" value="<?php echo htmlspecialchars($abogado['direccion']); ?>">
        </div>

        <div class="mb-3">
            <label>Teléfono</label>
            <input type="text" name="telefono" class="form-control" value="<?php echo htmlspecialchars($abogado['telefono']); ?>">
        </div>

        <div class="mb-3">
            <label>Celular</label>
            <input type="text" name="celular" class="form-control" value="<?php echo htmlspecialchars($abogado['celular']); ?>">
        </div>

        <button type="submit" class="btn btn-primary">Modificar</button>
        <a href="listar.php" class="btn btn-secondary">Volver</a>
    </form>
</body>
</html>
