<?php
require_once 'db.php';

// Obtener estadísticas de casos (Abiertos y Cerrados)
$query_estado = "SELECT estado, COUNT(*) AS cantidad FROM caso GROUP BY estado";
$resultado_estado = mysqli_query($conexion, $query_estado);
$estado_data = [];
while ($row = mysqli_fetch_assoc($resultado_estado)) {
    $estado_data[$row['estado']] = $row['cantidad'];
}

// Obtener estadísticas de los casos por abogado
$query_abogado = "SELECT abogado.nombre, COUNT(caso.id) AS cantidad_casos 
                  FROM caso 
                  JOIN abogado ON caso.abogado_id = abogado.id 
                  GROUP BY abogado.id";
$resultado_abogado = mysqli_query($conexion, $query_abogado);
$abogados_data = [];
while ($row = mysqli_fetch_assoc($resultado_abogado)) {
    $abogados_data[] = [
        'nombre' => $row['nombre'],
        'cantidad_casos' => $row['cantidad_casos']
    ];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estadísticas de Casos</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Estadísticas de Casos</h2>

    <!-- Gráfico de pastel: Casos Abiertos y Cerrados -->
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Estado de los Casos</h5>
            <canvas id="estadoChart"></canvas>
        </div>
    </div>

    <!-- Gráfico de barras: Casos por Abogado -->
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Casos por Abogado</h5>
            <canvas id="abogadoChart"></canvas>
        </div>
    </div>

    <script>
        // Datos para el gráfico de pastel (Casos Abiertos y Cerrados)
        var estadoData = <?php echo json_encode($estado_data); ?>;
        var estadoChartData = {
            labels: ['Abierto', 'Cerrado'],
            datasets: [{
                data: [estadoData['Abierto'] || 0, estadoData['Cerrado'] || 0],
                backgroundColor: ['#28a745', '#dc3545']
            }]
        };

        // Gráfico de Pastel: Estado de los Casos
        var ctxEstado = document.getElementById('estadoChart').getContext('2d');
        new Chart(ctxEstado, {
            type: 'pie',
            data: estadoChartData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + ' casos';
                            }
                        }
                    }
                }
            }
        });

        // Datos para el gráfico de barras (Casos por Abogado)
        var abogadoData = <?php echo json_encode($abogados_data); ?>;
        var abogadoLabels = abogadoData.map(function(item) { return item.nombre; });
        var abogadoCasos = abogadoData.map(function(item) { return item.cantidad_casos; });

        var abogadoChartData = {
            labels: abogadoLabels,
            datasets: [{
                label: 'Número de Casos',
                data: abogadoCasos,
                backgroundColor: '#007bff',
                borderColor: '#0056b3',
                borderWidth: 1
            }]
        };

        // Gráfico de Barras: Casos por Abogado
        var ctxAbogado = document.getElementById('abogadoChart').getContext('2d');
        new Chart(ctxAbogado, {
            type: 'bar',
            data: abogadoChartData,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.dataset.label + ': ' + tooltipItem.raw + ' casos';
                            }
                        }
                    }
                }
            }
        });
    </script>

</body>
</html>
