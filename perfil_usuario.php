<?php
require 'PHP/conexion.php';
session_start();

    if (!isset($_SESSION['usuario_documento'])) {
    header("Location: login.php");
    exit();
}

    $usuario = $_SESSION['usuario_documento'];
    $usuarios_query = "SELECT usuario_documento, usuario_tipo_documento, usuario_nombre, usuario_apellido, usuario_correo, usuario_telefono, usuario_direccion, usuario_nacimiento,
    usuario_imagen_url, usuario_fecha_creacion, usuario_ultima_actualizacion, usuario_estado, rol_id, escuela_id
    FROM usuarios WHERE usuario_estado = :estado AND usuario_documento = :usuario_documento";

    $stmt = $conn->prepare($usuarios_query);
    $stmt->bindValue(":estado", "activo", PDO::PARAM_STR);
    $stmt->bindValue(":usuario_documento", $usuario, PDO::PARAM_INT);

    $stmt->execute();
    $usuarios_result = $stmt->fetch(PDO::FETCH_ASSOC);


?>

<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Almacen</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="bg-yellow-50">
    <div class="container mx-auto p-4">
        <nav class="flex justify-between items-center mb-6">
            <a href="index.php" class="text-2xl font-bold text-yellow-600"><i class="fas fa-arrow-left mr-2"></i>Volver</a>
            <form action="perfil_usuario.php" method="POST" class="inline">
                <button type="submit" name="salir" class="btn-sports px-4 py-2 rounded-full shadow-md hover:shadow-lg">
                    <i class="fas fa-sign-out-alt mr-2"></i>Salir
                </button>
            </form>
        </nav>

        <div class="bg-white rounded-lg shadow-xl overflow-hidden">
            <div class="sports-gradient text-white p-6">
                <h2 class="text-3xl font-bold mb-2"><i class="fas fa-user-circle mr-2"></i>Perfil de Usuario</h2>
                <p class="text-lg">Mantén actualizada tu información deportiva</p>
            </div>

            <form action="perfil_usuario.php" method="POST" enctype="multipart/form-data" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <label for="usuario_imagen_url" class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-camera mr-2"></i>Imagen de Perfil
                        </label>
                        <input type="file" id="usuario_imagen_url" name="usuario_imagen_url" class="w-full p-2 border rounded-md input-sports">
                        <?php if (!empty($_SESSION['usuario_imagen_url'])): ?>
                            <img src="<?= htmlspecialchars($_SESSION['usuario_imagen_url']) ?>" alt="Imagen de Perfil" class="mt-2 w-32 h-32 rounded-full object-cover border-4 border-yellow-500">
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="usuario_nombre" class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-user mr-2"></i>Nombre
                        </label>
                        <input type="text" id="usuario_nombre" name="usuario_nombre" value="<?= htmlspecialchars($_SESSION['usuario_nombre']) ?>" class="w-full p-2 border rounded-md input-sports" required>
                    </div>

                    <div>
                        <label for="usuario_apellido" class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-user mr-2"></i>Apellido
                        </label>
                        <input type="text" id="usuario_apellido" name="usuario_apellido" value="<?= htmlspecialchars($_SESSION['usuario_apellido']) ?>" class="w-full p-2 border rounded-md input-sports" required>
                    </div>

                    <div>
                        <label for="usuario_correo" class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-envelope mr-2"></i>Correo
                        </label>
                        <input type="email" id="usuario_correo" name="usuario_correo" value="<?= htmlspecialchars($_SESSION['usuario_correo']) ?>" class="w-full p-2 border rounded-md input-sports" required>
                    </div>

                    <div>
                        <label for="usuario_telefono" class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-phone mr-2"></i>Teléfono
                        </label>
                        <input type="text" id="usuario_telefono" name="usuario_telefono" value="<?= htmlspecialchars($_SESSION['usuario_telefono']) ?>" class="w-full p-2 border rounded-md input-sports">
                    </div>

                    <div>
                        <label for="usuario_direccion" class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-map-marker-alt mr-2"></i>Dirección
                        </label>
                        <input type="text" id="usuario_direccion" name="usuario_direccion" value="<?= htmlspecialchars($_SESSION['usuario_direccion']) ?>" class="w-full p-2 border rounded-md input-sports">
                    </div>

                    <div>
                        <label for="usuario_nacimiento" class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-birthday-cake mr-2"></i>Fecha de Nacimiento
                        </label>
                        <input type="date" id="usuario_nacimiento" name="usuario_nacimiento" value="<?= htmlspecialchars($_SESSION['usuario_nacimiento']) ?>" class="w-full p-2 border rounded-md input-sports">
                    </div>
                </div>
    </main>
</body>
