<?php
$host = "localhost";        // Servidor
$usuario = "root";          // Usuario de MySQL (ajustar si usás otro)
$contrasena = "";           // Contraseña (vacío si estás en local sin contraseña)
$base_de_datos = "sistema_legal";  // Nombre de la base de datos

$conexion = mysqli_connect($host, $usuario, $contrasena, $base_de_datos);

// Verificar conexión
if (!$conexion) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

// Opcional: configurar charset para evitar problemas con acentos
mysqli_set_charset($conexion, "utf8");
?>
