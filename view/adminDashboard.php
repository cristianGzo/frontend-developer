<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <title>Document</title>
</head>

<body>
    <form id="graficoForm">
        <label for="fecha_inicio">Fecha de Inicio:</label>
        <input type="date" name="fecha_inicio" required>

        <label for="fecha_fin">Fecha de Fin:</label>
        <input type="date" name="fecha_fin" required>

        <input type="submit" value="Generar Gráfico">


    </form>

    <canvas id="myChart" width="400" height="200"></canvas>


    <script>
        $('#graficoForm').submit(function(event) {
            // Evitar que se envíe el formulario de la manera tradicional
            event.preventDefault();

            // Obtener los datos del formulario
            var formData = $(this).serialize();

            Chart.helpers.each(Chart.instances, function(instance) {
                instance.destroy();
            });
            // Realizar una solicitud AJAX para obtener datos del servidor
            $.ajax({
                type: 'POST',
                url: '../controlador/adminDashCtrl.php?opc=1',
                data: formData,
                success: function(datas) {
                    console.log(datas);
                    // Configurar datos para el gráfico
                    var chartData = JSON.parse(datas);

                    // Obtener el contexto del lienzo
                    var ctx = document.getElementById('myChart').getContext('2d');

                    // Crear un gráfico de barras
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: chartData.map(item => item.nombreProducto),
                            datasets: [{
                                label: 'Ventas por Producto',
                                data: chartData.map(item => item.totalVentas),
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }
            });
        });
    </script>

</body>

</html>