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

// Procesar formulario de actualización y eliminación
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['actualizar'])) {
        $usuario_nombre = $_POST['usuario_nombre'];
        $usuario_apellido = $_POST['usuario_apellido'];
        $usuario_correo = $_POST['usuario_correo'];
        $usuario_telefono = $_POST['usuario_telefono'];
        $usuario_direccion = $_POST['usuario_direccion'];
        $usuario_nacimiento = $_POST['usuario_nacimiento'];
        $usuario_tipo_documento = $_POST['usuario_tipo_documento'];
        $rol_id = $_POST['rol_id'];
        $escuela_id = $_POST['escuela_id'];
        $usuario_estado = isset($_POST['usuario_estado']) ? $_POST['usuario_estado'] : $usuario['usuario_estado'];

        // Manejo de la imagen de perfil
        if (isset($_FILES['usuario_imagen_url']) && $_FILES['usuario_imagen_url']['error'] == 0) {
            $usuario_imagen_url = '/IMG/usuarios/' . time() . '_' . $_FILES['usuario_imagen_url']['name'];
            move_uploaded_file($_FILES['usuario_imagen_url']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $usuario_imagen_url);
        } else {
            $usuario_imagen_url = $usuario['usuario_imagen_url'];
        }

        $sql_update = "UPDATE usuarios SET 
            usuario_nombre = ?, 
            usuario_apellido = ?, 
            usuario_correo = ?, 
            usuario_telefono = ?, 
            usuario_direccion = ?, 
            usuario_nacimiento = ?, 
            usuario_tipo_documento = ?, 
            rol_id = ?, 
            escuela_id = ?, 
            usuario_imagen_url = ?, 
            usuario_estado = ? 
            WHERE usuario_documento = ?";

        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param("sssssssiissi", $usuario_nombre, $usuario_apellido, $usuario_correo, $usuario_telefono, $usuario_direccion, $usuario_nacimiento, $usuario_tipo_documento, $rol_id, $escuela_id, $usuario_imagen_url, $usuario_estado, $usuario_documento);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "<script>alert('Usuario actualizado correctamente');</script>";
        } else {
            echo "<script>alert('No se realizaron cambios');</script>";
        }
    } elseif (isset($_POST['eliminar'])) {
        $sql_delete = "DELETE FROM usuarios WHERE usuario_documento = ?";
        $stmt = $conn->prepare($sql_delete);
        $stmt->bind_param("s", $usuario_documento);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "<script>alert('Usuario eliminado correctamente');</script>";
            session_destroy();
            header("Location: login.php");
            exit();
        } else {
            echo "<script>alert('Error al eliminar el usuario');</script>";
        }
    } elseif (isset($_POST['salir'])) {
        session_destroy();
        header("Location: index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario - Deportes</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="IMG/logo_mini.png" type="image/x-icon">
    
    <style>
        body {
            background-color: #fff9e6;
            font-family: 'Roboto', sans-serif;
        }
        .sports-gradient {
            background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
        }
        .btn-sports {
            background-color: #FFD700;
            color: #000;
            transition: all 0.3s ease;
        }
        .btn-sports:hover {
            background-color: #FFA500;
            transform: translateY(-2px);
        }
        .input-sports {
            border: 2px solid #FFD700;
            transition: all 0.3s ease;
        }
        .input-sports:focus {
            border-color: #FFA500;
            box-shadow: 0 0 0 2px rgba(255, 215, 0, 0.2);
        }
    </style>
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
                        <?php if ($usuario['usuario_imagen_url']): ?>
                            <img src="<?= htmlspecialchars($usuario['usuario_imagen_url']) ?>" alt="Imagen de Perfil" class="mt-2 w-32 h-32 rounded-full object-cover border-4 border-yellow-500">
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="usuario_nombre" class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-user mr-2"></i>Nombre
                        </label>
                        <input type="text" id="usuario_nombre" name="usuario_nombre" value="<?= htmlspecialchars($usuario['usuario_nombre']) ?>" class="w-full p-2 border rounded-md input-sports">
                    </div>

                    <div>
                        <label for="usuario_apellido" class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-user mr-2"></i>Apellido
                        </label>
                        <input type="text" id="usuario_apellido" name="usuario_apellido" value="<?= htmlspecialchars($usuario['usuario_apellido']) ?>" class="w-full p-2 border rounded-md input-sports">
                    </div>

                    <div>
                        <label for="usuario_correo" class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-envelope mr-2"></i>Correo
                        </label>
                        <input type="email" id="usuario_correo" name="usuario_correo" value="<?= htmlspecialchars($usuario['usuario_correo']) ?>" class="w-full p-2 border rounded-md input-sports">
                    </div>

                    <div>
                        <label for="usuario_telefono" class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-phone mr-2"></i>Teléfono
                        </label>
                        <input type="text" id="usuario_telefono" name="usuario_telefono" value="<?= htmlspecialchars($usuario['usuario_telefono']) ?>" class="w-full p-2 border rounded-md input-sports">
                    </div>

                    <div>
                        <label for="usuario_direccion" class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-map-marker-alt mr-2"></i>Dirección
                        </label>
                        <input type="text" id="usuario_direccion" name="usuario_direccion" value="<?= htmlspecialchars($usuario['usuario_direccion']) ?>" class="w-full p-2 border rounded-md input-sports">
                    </div>

                    <div>
                        <label for="usuario_nacimiento" class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-birthday-cake mr-2"></i>Fecha de Nacimiento
                        </label>
                        <input type="date" id="usuario_nacimiento" name="usuario_nacimiento" value="<?= htmlspecialchars($usuario['usuario_nacimiento']) ?>" class="w-full p-2 border rounded-md input-sports">
                    </div>

                    <div>
                        <label for="usuario_tipo_documento" class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-id-card mr-2"></i>Tipo de Documento
                        </label>
                        <select id="usuario_tipo_documento" name="usuario_tipo_documento" class="w-full p-2 border rounded-md input-sports">
                            <option value="CC" <?= $usuario['usuario_tipo_documento'] == 'CC' ? 'selected' : '' ?>>Cédula de Ciudadanía</option>
                            <option value="CE" <?= $usuario['usuario_tipo_documento'] == 'CE' ? 'selected' : '' ?>>Cédula de Extranjería</option>
                            <option value="TI" <?= $usuario['usuario_tipo_documento'] == 'TI' ? 'selected' : '' ?>>Tarjeta de Identidad</option>
                            <option value="PPN" <?= $usuario['usuario_tipo_documento'] == 'PPN' ? 'selected' : '' ?>>Pasaporte</option>
                            <option value="NIT" <?= $usuario['usuario_tipo_documento'] == 'NIT' ? 'selected' : '' ?>>NIT</option>
                            <option value="SSN" <?= $usuario['usuario_tipo_documento'] == 'SSN' ? 'selected' : '' ?>>SSN</option>
                            <option value="EIN" <?= $usuario['usuario_tipo_documento'] == 'EIN' ? 'selected' : '' ?>>EIN</option>
                        </select>
                    </div>

                    <div>
                        <label for="rol_id" class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-users mr-2"></i>Rol
                        </label>
                        <select id="rol_id" name="rol_id" class="w-full p-2 border rounded-md input-sports">
                            <?php foreach ($roles as $rol): ?>
                                <option value="<?= $rol['rol_id'] ?>" <?= $usuario['rol_id'] == $rol['rol_id'] ? 'selected' : '' ?>><?= htmlspecialchars($rol['rol_nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="escuela_id" class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-school mr-2"></i>Escuela
                        </label>
                        <select id="escuela_id" name="escuela_id" class="w-full p-2 border rounded-md input-sports">
                            <?php foreach ($escuelas as $escuela): ?>
                                <option value="<?= $escuela['escuela_id'] ?>" <?= $usuario['escuela_id'] == $escuela['escuela_id'] ? 'selected' : '' ?>><?= htmlspecialchars($escuela['escuela_nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="usuario_estado" class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-toggle-on mr-2"></i>Estado
                        </label>
                        <select id="usuario_estado" name="usuario_estado" class="w-full p-2 border rounded-md input-sports">
                            <option value="activo" <?= $usuario['usuario_estado'] == 'activo' ? 'selected' : '' ?>>Activo</option>
                            <option value="inactivo" <?= $usuario['usuario_estado'] == 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-4">
                    <button type="submit" name="actualizar" class="btn-sports px-6 py-2 rounded-full shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i>Actualizar
                    </button>
                    <button type="submit" name="eliminar" class="bg-red-500 text-white px-6 py-2 rounded-full shadow-md hover:bg-red-600 hover:shadow-lg transition duration-300">
                        <i class="fas fa-trash-alt mr-2"></i>Eliminar
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>