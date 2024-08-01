<?php
session_start();
require_once 'PHP/conexion.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_documento'])) {
    header("Location: login.php");
    exit();
}

$usuario_documento = $_SESSION['usuario_documento'];

// Obtener datos del usuario
$sql = "SELECT u.*, r.rol_nombre, e.escuela_nombre 
        FROM usuarios u 
        LEFT JOIN roles r ON u.rol_id = r.rol_id 
        LEFT JOIN escuelas e ON u.escuela_id = e.escuela_id 
        WHERE u.usuario_documento = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario_documento);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

// Obtener lista de roles y escuelas para los selectores
$roles = $conn->query("SELECT * FROM roles")->fetch_all(MYSQLI_ASSOC);
$escuelas = $conn->query("SELECT * FROM escuelas")->fetch_all(MYSQLI_ASSOC);

// Procesar formulario de actualización
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['actualizar'])) {
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $correo = $_POST['correo'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $nacimiento = $_POST['nacimiento'];
        $tipo_documento = $_POST['tipo_documento'];
        $rol_id = $_POST['rol_id'];
        $escuela_id = $_POST['escuela_id'];
        $estado = $_POST['usuario_estado'];

        // Manejo de la imagen de perfil
        if (isset($_FILES['imagen_perfil']) && $_FILES['imagen_perfil']['error'] == 0) {
            $imagen_perfil = '/IMG/usuarios/' . time() . '_' . $_FILES['imagen_perfil']['name'];
            move_uploaded_file($_FILES['imagen_perfil']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $imagen_perfil);
        } else {
            $imagen_perfil = $usuario['usuario_imagen_url'];
        }

        $sql_update = "UPDATE usuarios SET 
            usuario_nombre = ?, 
            usuario_apellido = ?, 
            usuario_correo = ?, 
            usuario_telefono = ?, 
            usuario_direccion = ?, 
            usuario_nacimiento = ?, 
            usuario_tipo_documento = ?, 
            usuario_imagen_url = ?,
            rol_id = ?,
            escuela_id = ?,
            usuario_estado = ?
            WHERE usuario_documento = ?";

        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("sssssssssiis", $nombre, $apellido, $correo, $telefono, $direccion, $nacimiento, $tipo_documento, $imagen_perfil, $rol_id, $escuela_id, $estado, $usuario_documento);
        
        if ($stmt_update->execute()) {
            $mensaje = "Perfil actualizado correctamente.";
            // Actualizar la información del usuario en la sesión
            $_SESSION['usuario_nombre'] = $nombre;
            // Recargar los datos del usuario
            $stmt->execute();
            $result = $stmt->get_result();
            $usuario = $result->fetch_assoc();
        } else {
            $error = "Error al actualizar el perfil: " . $conn->error;
        }
    } elseif (isset($_POST['eliminar'])) {
        $sql_delete = "UPDATE usuarios SET usuario_estado = 'inactivo' WHERE usuario_documento = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("s", $usuario_documento);
        
        if ($stmt_delete->execute()) {
            session_destroy();
            header("Location: login.php?mensaje=cuenta_eliminada");
            exit();
        } else {
            $error = "Error al eliminar la cuenta: " . $conn->error;
        }
    }
}

// Cerrar sesión
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario | Classment Academy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/eb36e646d1.js" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="IMG/logo_mini.png" type="image/x-icon">
    
</head>
<body class="font-sans">
    <div class="min-h-screen flex flex-col">
        <header class="bg-gray-800 text-white py-4 shadow-lg">
            <div class="container mx-auto px-4 flex justify-between items-center">
                <a href="index.php" class="text-2xl font-bold text-custom-yellow">Classment Academy</a>
                <nav>
                    <a href="index.php" class="text-white hover:text-custom-yellow transition duration-300 mr-4">Inicio</a>
                    <a href="?logout=true" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded transition duration-300">Cerrar Sesión</a>
                </nav>
            </div>
        </header>

        <main class="flex-grow container mx-auto px-4 py-8">
            <div class="bg-white rounded-lg shadow-2xl p-8 max-w-4xl mx-auto">
                <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Tu Perfil</h1>
                
                <?php if (isset($mensaje)): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline"><?php echo $mensaje; ?></span>
                    </div>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline"><?php echo $error; ?></span>
                    </div>
                <?php endif; ?>

                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
                    <div class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-6 mb-4">
                        <img class="h-32 w-32 object-cover rounded-full border-4 border-custom-yellow" src="<?php echo $usuario['usuario_imagen_url']; ?>" alt="Foto de perfil">
                        <div class="flex-grow">
                            <label class="block mb-2 font-semibold text-gray-700">
                                Cambiar foto de perfil
                                <input type="file" name="imagen_perfil" class="block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-full file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-custom-yellow file:text-black
                                    hover:file:bg-yellow-600
                                    cursor-pointer
                                "/>
                            </label>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="documento" class="block text-sm font-medium text-gray-700">Número de Documento</label>
                            <input type="text" id="documento" name="documento" value="<?php echo $usuario['usuario_documento']; ?>" readonly class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-custom-yellow focus:ring focus:ring-custom-yellow focus:ring-opacity-50 bg-gray-100">
                        </div>
                        <div>
                            <label for="tipo_documento" class="block text-sm font-medium text-gray-700">Tipo de Documento</label>
                            <select id="tipo_documento" name="tipo_documento" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-custom-yellow focus:ring focus:ring-custom-yellow focus:ring-opacity-50">
                                <option value="CC" <?php echo $usuario['usuario_tipo_documento'] == 'CC' ? 'selected' : ''; ?>>Cédula de Ciudadanía</option>
                                <option value="CE" <?php echo $usuario['usuario_tipo_documento'] == 'CE' ? 'selected' : ''; ?>>Cédula de Extranjería</option>
                                <option value="TI" <?php echo $usuario['usuario_tipo_documento'] == 'TI' ? 'selected' : ''; ?>>Tarjeta de Identidad</option>
                                <option value="PPN" <?php echo $usuario['usuario_tipo_documento'] == 'PPN' ? 'selected' : ''; ?>>Pasaporte</option>
                                <option value="NIT" <?php echo $usuario['usuario_tipo_documento'] == 'NIT' ? 'selected' : ''; ?>>NIT</option>
                                <option value="Otro" <?php echo $usuario['usuario_tipo_documento'] == 'Otro' ? 'selected' : ''; ?>>Otro</option>
                            </select>
                        </div>
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input type="text" id="nombre" name="nombre" value="<?php echo $usuario['usuario_nombre']; ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-custom-yellow focus:ring focus:ring-custom-yellow focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="apellido" class="block text-sm font-medium text-gray-700">Apellido</label>
                            <input type="text" id="apellido" name="apellido" value="<?php echo $usuario['usuario_apellido']; ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-custom-yellow focus:ring focus:ring-custom-yellow focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="correo" class="block text-sm font-medium text-gray-700">Correo</label>
                            <input type="email" id="correo" name="correo" value="<?php echo $usuario['usuario_correo']; ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-custom-yellow focus:ring focus:ring-custom-yellow focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
                            <input type="tel" id="telefono" name="telefono" value="<?php echo $usuario['usuario_telefono']; ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-custom-yellow focus:ring focus:ring-custom-yellow focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="direccion" class="block text-sm font-medium text-gray-700">Dirección</label>
                            <input type="text" id="direccion" name="direccion" value="<?php echo $usuario['usuario_direccion']; ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-custom-yellow focus:ring focus:ring-custom-yellow focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="nacimiento" class="block text-sm font-medium text-gray-700">Fecha de Nacimiento</label>
                            <input type="date" id="nacimiento" name="nacimiento" value="<?php echo $usuario['usuario_nacimiento']; ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-custom-yellow focus:ring focus:ring-custom-yellow focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="rol_id" class="block text-sm font-medium text-gray-700">Rol</label>
                            <select id="rol_id" name="rol_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-custom-yellow focus:ring focus:ring-custom-yellow focus:ring-opacity-50">
                                <?php foreach ($roles as $rol): ?>
                                    <option value="<?php echo $rol['rol_id']; ?>" <?php echo $usuario['rol_id'] == $rol['rol_id'] ? 'selected' : ''; ?>><?php echo $rol['rol_nombre']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="escuela_id" class="block text-sm font-medium text-gray-700">Escuela</label>
                            <select id="escuela_id" name="escuela_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-custom-yellow focus:ring focus:ring-custom-yellow focus:ring-opacity-50">
                                <?php foreach ($escuelas as $escuela): ?>
                                    <option value="<?php echo $escuela['escuela_id']; ?>" <?php echo $usuario['escuela_id'] == $escuela['escuela_id'] ? 'selected' : ''; ?>><?php echo $escuela['escuela_nombre']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="usuario_estado" class="block text-sm font-medium text-gray-700">Estado</label>
                            <select id="usuario_estado" name="usuario_estado" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-custom-yellow focus:ring focus:ring-custom-yellow focus:ring-opacity-50">
                                <option value="activo" <?php echo $usuario['usuario_estado'] == 'activo' ? 'selected' : ''; ?>>Activo</option>
                                <option value="inactivo" <?php echo $usuario['usuario_estado'] == 'inactivo' ? 'selected' : ''; ?>>Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end mt-6">
                    <button type="submit" name="eliminar" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300">
                    Actualizar Perfil
                        </button>
                    </div>
                    <div class="flex justify-end mt-4">
                        <button type="submit" name="eliminar" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300">
                            Eliminar Cuenta
                        </button>
                    </div>
                </form>
            </div>
        </main>

        <footer class="bg-gray-800 text-white py-4">
            <div class="container mx-auto px-4 text-center">
                &copy; <?php echo date('Y'); ?> Classment Academy. Todos los derechos reservados.
            </div>
        </footer>
    </div>
</body>
</html>
