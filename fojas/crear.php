<?php
require_once '../db.php';

$mensaje = "";

// Obtener los casos para mostrarlos en el formulario
$query = "SELECT id, numero_expediente, nombre FROM caso ORDER BY numero_expediente ASC";
$casos = mysqli_query($conexion, $query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $caso_id      = $_POST['caso_id'];
    $observacion  = $_POST['observacion'];
    $imagen       = $_FILES['imagen'];

    // Validación de campos
    if (!empty($caso_id) && !empty($observacion)) {
        // Subir la imagen si se ha seleccionado
        $ruta_imagen = null;
        if ($imagen['error'] == 0) {
            $carpeta_destino = '../imagenes/fojas/';
            $nombre_imagen   = uniqid() . '-' . basename($imagen['name']);
            $ruta_imagen     = $carpeta_destino . $nombre_imagen;
            move_uploaded_file($imagen['tmp_name'], $ruta_imagen);
        }

        $query = "INSERT INTO foja (caso_id, observacion, imagen) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, "iss", $caso_id, $observacion, $ruta_imagen);

        if (mysqli_stmt_execute($stmt)) {
            $mensaje = "✅ Foja creada correctamente.";
        } else {
            $mensaje = "❌ Error al crear foja: " . mysqli_error($conexion);
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
    <title>Crear Foja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Crear Nueva Foja</h2>

    <?php if ($mensaje): ?>
        <div class="alert alert-info"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" action="">
        <div class="mb-3">
            <label>Seleccionar Caso *</label>
            <select name="caso_id" class="form-select" required>
                <option value="">Seleccione un caso</option>
                <?php while ($caso = mysqli_fetch_assoc($casos)): ?>
                    <option value="<?php echo $caso['id']; ?>"><?php echo htmlspecialchars($caso['numero_expediente'] . ' - ' . $caso['nombre']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Observación *</label>
            <textarea name="observacion" class="form-control" rows="4" required></textarea>
        </div>

        <div class="mb-3">
            <label>Imagen (Opcional)</label>
            <input type="file" name="imagen" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Crear Foja</button>
        <a href="listar.php" class="btn btn-secondary">Volver</a>
    </form>
</body>
</html>
