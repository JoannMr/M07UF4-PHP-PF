<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <title>Productos</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-800">

    <!-- Protección: si no hay token, redirigimos al login -->
    <script>
        if (!localStorage.getItem('token')) {
            window.location.href = window.location.origin + '/gestor-productes/public/login';
        }
    </script>

    <h1 class="text-3xl font-bold text-center mt-10">Listado de productos (Vista protegida)</h1>

    <!-- Aquí luego montaremos la tabla de productos con Vue -->
    <div id="products" class="mt-10"></div>

</body>
</html>
