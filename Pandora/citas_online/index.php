<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reserva de Citas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 50vh;
            justify-content: center;
        }

        h1 {
            margin-bottom: 20px;
            color: #333;
        }

        .container {
            width: 50%;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            box-sizing: border-box;
        }

        form {
            width: 100%;
        }

        label {
            display: block;
            margin-bottom: 10px;
            width: 50%;
            text-align: right;
            padding-right: 10px;
            box-sizing: border-box;
            float: left;
        }

        input[type="text"],
        input[type="email"],
        select {
            width: 50%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>
    <h1>Reserva de Citas Online</h1>
    <div class="container">
        <form id="reservaCitaForm" action="reservar_cita.php" method="POST">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required><br>

            <label for="dni">DNI:</label>
            <input type="text" id="dni" name="dni" required><br>

            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" required><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>

            <label for="tipoCita">Tipo de Cita:</label>
            <select id="tipoCita" name="tipoCita" required>
                <option value="Primera Consulta">Primera Consulta</option>
            </select><br>

            <input type="submit" value="Reservar Cita">
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $("#dni").on("blur", function() {
                var dni = $(this).val();
                $.ajax({
                    url: 'verificar_dni.php',
                    type: 'POST',
                    data: {
                        dni: dni
                    },
                    success: function(response) {
                        if (response === 'existe') {
                            $("#tipoCita").html('<option value="Revisión">Revisión</option>');
                        } else {
                            $("#tipoCita").html('<option value="Primera Consulta">Primera Consulta</option>');
                        }
                    }
                });
            });

            $("#reservaCitaForm").on("submit", function(e) {
                e.preventDefault();
                var email = $("#email").val();
                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    alert("Por favor, introduzca un email válido.");
                    return false;
                }
                this.submit();
            });
        });
    </script>
</body>

</html>