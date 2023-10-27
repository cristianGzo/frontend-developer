<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/newProduct.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --white: #FFFFFF;
            --black: #000000;
            --very-light-pink: #C7C7C7;
            --text-input-field: #F7F7F7;
            --hospital-green: #D6FFB7;
            --sm: 14px;
            --md: 16px;
            --lg: 18px;
        }

        body {
            margin: 0;
            font-family: 'Quicksand', 'sans-serif';
        }

        .contactUs {
            padding: 20px;
            border: 1px solid var(--very-light-pink);
            border-radius: 10px;
            width: 60%;
            margin: 0 auto;
        }

        .contactUs h3 {
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--very-light-pink);
            border-radius: 5px;
            font-size: var(--md);
        }

        .button-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100px;
        }

        button {
            text-decoration: none;
            color: #ff9f1c;
            background-color: transparent;
            border: 2px solid;
            border-color: #FFC15E;
            padding: 8px;
            border-radius: 8px;

        }

        button:hover {
            border: 1px solid greenyellow;
            color: green;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function insertar() {
            var formData = $('#formRegister').serialize();
            $.ajax({
                type: "POST",
                url: "../controlador/productoControlador.php?opc=1",
                data: formData,
                success: function(data) {
                    window.location.href = '../cards.php';
                },
            })
        }
    </script>

    <script>
        function obtenerCategoria() {
            $.ajax({
                type: "POST",
                data: {},
                url: "../controlador/categoriaControlador.php?opc=1",
                success: function(data) {
                    $('#miDropdown').html(data);
                }
            });
        }
        $(document).ready(function() {
            obtenerCategoria();
        });
    </script>
    <script>
        function obtenerImagen() {
            $.ajax({
                type: "POST",
                data: {},
                url: "../controlador/productoControlador.php?opc=2",
                success: function(data) {
                    $('#miDropdown2').html(data);
                }
            });
        }
        $(document).ready(function() {
            obtenerImagen();
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Obtén una referencia al elemento <select> de categorías
            var selectCategoria = document.getElementById("miDropdown");
            var selectImagen =document.getElementById("miDropdown2");
            // Agrega un oyente de eventos al elemento <select>
            selectCategoria.addEventListener("change", function() {
                // Obtén el valor seleccionado
                var selectedOption = selectCategoria.options[selectCategoria.selectedIndex];
                var idCategoria = selectedOption.value;

                $.ajax({
                    type: "POST",
                    url: "../controlador/categoriaControlador.php?opc=2", // Reemplaza con la URL de tu script
                    data: {
                        idCategoria: idCategoria
                    },
                    success: function(idObtenido) {
                        alert("ID de la categoría seleccionada: " + idObtenido);
                        // Ahora puedes usar idObtenido para lo que necesites
                    }
                });
            });

            selectImagen.addEventListener("change", function() {
                // Obtén el valor seleccionado
                var selectedOption = selectImagen.options[selectImagen.selectedIndex];
                var imagen = selectedOption.value;
                alert('la imagen es' + imagen );

            });


        });
    </script>

    <title>Document</title>
</head>

<body>
    <main>
        <div class="contactUs container">
            <h3>Registrar producto</h3>
            <form id="formRegister">
                <div class="form-group">
                    <label for="txtNombre">Nombre</label>
                    <input type="text" id="txtNombre" name="txtNombre" />
                </div>
                <div class="form-group">
                    <label for="txtAp">Descripcion</label>
                    <input type="text" id="txtAp" name="txtAp" />
                </div>
                <div class="form-group">
                    <label for="txtPm">Precio</label>
                    <input type="text" id="txtPm" name="txtPm" />
                </div>

                <div class="form-group">
                    <label for="miDropdown">Categoria</label>
                    <select id="miDropdown" class="categorias">
                        <option value="opcion1"></option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="miDropdown2">Imagen</label>
                    <select id="miDropdown2" class="imagenes">
                        <option value="opcion1"></option>
                    </select>
                </div>
                <div class="button-container">
                    <button type="submit">Enviar</button>
                </div>
            </form>
        </div>
    </main>
</body>

</html>