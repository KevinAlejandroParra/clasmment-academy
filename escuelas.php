<?php
session_start();
require 'PHP/conexion.php';



// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_correo'])) {
    // No está autenticado, redirigir al inicio de sesión
    header('Location: login.php');
    exit();
}

// Verificar si el usuario pertenece a la escuela de administración  (por ejemplo, escuela_id = 1)
$usuario_administracion_id = 1025531531; 
if ($_SESSION['usuario_documento'] != $usuario_administracion_id) {
    // Usuario no pertenece a la escuela de administración, denegar acceso
    echo "Acceso denegado. No tienes permiso para ver este archivo.";
    exit();
}


// Obtener todas las escuelas
$escuelas_query = "SELECT escuela_id, escuela_nombre, escuela_descripcion, escuela_telefono, escuela_direccion, escuela_imagen_url, escuela_fecha_creacion, escuela_estado
 FROM escuelas WHERE escuela_estado = :estado order by escuela_id asc";
    $stmt = $conn->prepare($escuelas_query);
    $stmt->bindValue(":estado", "activo", PDO::PARAM_STR);
    $stmt->execute();
    $escuelas_result = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Almacen</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="py-3">
    <main class="container mx-auto">
        <div class="p-3 rounded">
            <div class="flex flex-wrap">
                <div class="w-full">
                    <h4 class="text-lg font-bold">Productos
                        <a href="nuevo.php" class="btn btn-primary float-right bg-blue-500 text-white py-2 px-4 rounded">Nuevo</a>
                    </h4>
                </div>
            </div>

            <div class="flex flex-wrap py-3">
                <div class="w-full">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Código</th>
                                <th>Descripción</th>
                                <th>Stock</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            foreach ($escuelas_result as $row) {
                            ?>
                                <tr>
                                    <td><?php echo $row['escuela_id']; ?></td>
                                    <td><?php echo $row['escuela_nombre']; ?></td>
                                    <td><?php echo $row['escuela_descripcion']; ?></td>
                                    <td><?php echo $row['escuela_telefono']; ?></td>
                                    <td><a href="editar.php?id=<?php echo $row['escuela_id']; ?>" class="bg-yellow-500 text-white py-1 px-3 rounded">Editar</a></td>
                                    <td><a href="eliminar.php?id=<?php echo $row['escuela_id']; ?>" class="bg-red-500 text-white py-1 px-3 rounded">Eliminar</a></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>
