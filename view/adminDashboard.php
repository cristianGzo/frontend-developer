<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <style>
        body {
            display: flex;
            flex-direction: column;
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
        }

        #sidebar {
            width: 250px;
            height: 100vh;
            background-color: #ffffff;
            box-shadow: 5px 0px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            z-index: 1;
            position: fixed;

        }


        #graficoForm {

            padding: 20px;

            margin-left: 320px;
        }

        #sidebar h2 {
            color: #333;
            text-align: center;
        }

        #sidebar ul {
            list-style: none;
            padding: 0;
        }

        #sidebar ul li {
            margin-bottom: 10px;
        }

        #sidebar ul li a {
            text-decoration: none;
            color: #333;
            display: flex;
            align-items: center;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        #sidebar ul li a:hover {
            background-color: #4caf50;
            border-radius: 40px;
            color: #fff;
        }

        #myChart {
            max-width: 600px;
            /* Ajusta según tus necesidades */
            margin-left: 320px;
            /* Ajusta según tus necesidades */

        }


        .icon {
            margin-right: 10px;
        }

        #modalAgregarCategoria {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }





        #modalEliminarProducto {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-contents {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .closes {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .closes:hover,
        .closes:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        #listaProductos {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
        }

        button {
            background-color: #4caf50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
    <title>Document</title>

    <script>
        function abrirModal() {
            document.getElementById('modalAgregarCategoria').style.display = 'block';
        }

        function cerrarModal() {
            document.getElementById('modalAgregarCategoria').style.display = 'none';
        }

        function agregarCategoria() {
            var nombreCategoria = document.getElementById('nombreCategoria').value;
            // Validar que el nombre de la categoría no esté vacío
            if (nombreCategoria.trim() === '') {
                alert('Por favor, ingresa un nombre para la categoría.');
                return;
            }

            $.ajax({
                type: 'POST',
                url: '../controlador/categoriaControlador.php?opc=3',
                data: {
                    nombre: nombreCategoria
                },
                success: function(response) {
                    console.log(response);
                    alert('Categoría agregada exitosamente.');
                    cerrarModal();
                },
                error: function(error) {
                    console.error(error);
                    // Aquí puedes manejar el caso de error
                    alert('Ocurrió un error al agregar la categoría.');
                }
            });
        }

        function cargarListaProductos(callback) {
            $.ajax({
                type: 'POST',
                url: '../controlador/ProductoControlador.php?opc=3',
                success: function(data) {
                    console.log(data);
                    var listaProductos = $('#listaProductos');
                    listaProductos.empty(); // Limpia la lista antes de agregar nuevos elementos

                    var productos = JSON.parse(data);

                    productos.forEach(function(producto) {
                        // Crea una opción con el valor del id y muestra el nombre y la descripción
                        listaProductos.append('<option value="' + producto.idProducto + '">' + producto.nombre + ' - ' + producto.descripcion + ' - ' + producto.precio + '</option>');
                    });
                    if (typeof callback === 'function') {
                    callback();
                }
                },
                error: function(error) {
                    console.error(error);
                    alert('Ocurrió un error al obtener la lista de productos.');
                }
            });
        }

        function abrirEliminarProductoModal() {
            cargarListaProductos(function(){
            document.getElementById('modalEliminarProducto').style.display = 'block';
            });
        }
        function cerrarModalEliminarProducto() {
            document.getElementById('modalEliminarProducto').style.display = 'none';
        }

        function eliminarProductoSeleccionado() {
            var idProductoSeleccionado = $('#listaProductos').val();
            console.log(idProductoSeleccionado);
            // Validar que se haya seleccionado un producto
            if (!idProductoSeleccionado) {
                alert('Por favor, selecciona un producto.');
                return;
            }

            $.ajax({
                type: 'POST',
                url: '../controlador/productoControlador.php?opc=4', 
                data: {
                    idProducto: idProductoSeleccionado
                },
                success: function(response) {
                    console.log(response);
                    alert('Producto eliminado exitosamente.');
                    cerrarModalEliminarProducto();
                },
                error: function(error) {
                    console.error(error);
                    alert('Ocurrió un error al eliminar el producto.');
                }
            });
        }
    </script>
</head>

<body>
    <div id="sidebar">
        <h2>Dashboard</h2>
        <ul>
            <li><a href="#"><i class="fas fa-chart-bar icon"></i>Dashboard</a></li>
            <li><a href="./newProduct.php"><i class="fas fa-plus icon"></i>Add Product</a></li>
            <li><a href="#" onclick="abrirEliminarProductoModal()"><i class="fas fa-trash-alt icon"></i>Delete Product</a></li>
            <li><a href="#"><i class="fas fa-pencil-alt icon"></i>Edit Product</a></li>
            <li><a href="javascript:void(0);" onclick="abrirModal()"><i class="fas fa-plus icon"></i>Add category</a></li>
            <li><a href="#"><i class="fas fa-trash-alt icon"></i>Delete category</a></li>

        </ul>
    </div>

    <!--<div id="content">-->
    <form id="graficoForm">
        <label for="fecha_inicio">Fecha de Inicio:</label>
        <input type="date" name="fecha_inicio" required>

        <label for="fecha_fin">Fecha de Fin:</label>
        <input type="date" name="fecha_fin" required>

        <input type="submit" value="Generar Gráfico">
    </form>
    <canvas id="myChart" width="400" height="200"></canvas>
    <!--</div>-->



    <script>
        /*$('#graficoForm').submit(function(event) {
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
        });*/
        function generarGrafico(data) {
            // Configurar datos para el gráfico
            var chartData = JSON.parse(data);
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
                    // Llamar a la función para generar el gráfico
                    generarGrafico(datas);
                }
            });
        });
    </script>

    <div id="modalAgregarCategoria" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal()">&times;</span>
            <label for="nombreCategoria">Nombre de la categoría:</label>
            <input type="text" id="nombreCategoria" required>
            <button onclick="agregarCategoria()">Agregar Categoría</button>
        </div>
    </div>

    <div id="modalEliminarProducto" class="modals">
        <div class="modal-contents">
            <span class="closes" onclick="cerrarModalEliminarProducto()">&times;</span>
            <label for="listaProductos">Selecciona un producto:</label>
            <select id="listaProductos"></select>
            <button onclick="eliminarProductoSeleccionado()">Eliminar Producto</button>
        </div>
    </div>


</body>

</html>