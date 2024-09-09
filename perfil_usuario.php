<?php
session_start();
require_once 'PHP/conexion.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_documento'])) {
    header("Location: login.php");
    exit();
}

$usuario_documento = $_SESSION['usuario_documento'];

// Obtener datos del usuario logueado
$usuario_query = "SELECT * FROM usuarios WHERE usuario_documento = :usuario_documento";
$stmt = $conn->prepare($usuario_query);
$stmt->bindValue(':usuario_documento', $usuario_documento, PDO::PARAM_STR);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    // Si no se encuentra el usuario, redirigir o manejar el error
    echo "<script>alert('Usuario no encontrado');</script>";
    exit();
}


// Obtener roles
$roles_query = "SELECT rol_id, rol_nombre FROM roles";
$stmt = $conn->prepare($roles_query);
$stmt->execute();
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener escuelas
$escuelas_query = "SELECT escuela_id, escuela_nombre FROM escuelas WHERE escuela_estado = :estado";
$stmt = $conn->prepare($escuelas_query);
$stmt->bindValue(":estado", "activo", PDO::PARAM_STR);
$stmt->execute();
$escuelas_result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Procesar formulario de actualización y eliminación
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['actualizar'])) {
      

        // Manejo de la imagen de perfil
        if (isset($_FILES['usuario_imagen_url']) && $_FILES['usuario_imagen_url']['error'] == 0) {
            $upload_dir = '/IMG/usuarios/';
            $timestamp = time();
            $filename = basename($_FILES['usuario_imagen_url']['name']);
            $usuario_imagen_url = $upload_dir . $timestamp . '_' . $filename;

            // Verificar que el directorio de destino exista
            if (!is_dir($_SERVER['DOCUMENT_ROOT'] . $upload_dir)) {
                mkdir($_SERVER['DOCUMENT_ROOT'] . $upload_dir, 0755, true);
            }

            if (!move_uploaded_file($_FILES['usuario_imagen_url']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $usuario_imagen_url)) {
                // MODIFICACIÓN: Manejo de error en la subida de la imagen
                echo "<script>alert('Error al subir la imagen de perfil');</script>";
                $usuario_imagen_url = $usuario['usuario_imagen_url'];
            }
        } else {
            // MODIFICACIÓN: Corregido el acceso a la URL de la imagen existente
            $usuario_imagen_url = $usuario['usuario_imagen_url'];
        }

                    // Preparar la consulta de actualización
            $sql_update = "UPDATE usuarios SET 
            usuario_nombre = :usuario_nombre, 
            usuario_apellido = :usuario_apellido, 
            usuario_correo = :usuario_correo, 
            usuario_telefono = :usuario_telefono, 
            usuario_direccion = :usuario_direccion, 
            usuario_nacimiento = :usuario_nacimiento, 
            usuario_tipo_documento = :usuario_tipo_documento, 
            rol_id = :rol_id, 
            escuela_id = :escuela_id, 
            usuario_imagen_url = :usuario_imagen_url, 
            usuario_estado = :usuario_estado 
            WHERE usuario_documento = :usuario_documento";

            $stmt = $conn->prepare($sql_update);

            $stmt->bindValue(':usuario_nombre', $usuario_nombre, PDO::PARAM_STR);
            $stmt->bindValue(':usuario_apellido', $usuario_apellido, PDO::PARAM_STR);
            $stmt->bindValue(':usuario_correo', $usuario_correo, PDO::PARAM_STR);
            $stmt->bindValue(':usuario_telefono', $usuario_telefono, PDO::PARAM_STR);
            $stmt->bindValue(':usuario_direccion', $usuario_direccion, PDO::PARAM_STR);
            $stmt->bindValue(':usuario_nacimiento', $usuario_nacimiento, PDO::PARAM_STR);
            $stmt->bindValue(':usuario_tipo_documento', $usuario_tipo_documento, PDO::PARAM_STR);
            $stmt->bindValue(':rol_id', $rol_id, PDO::PARAM_INT);
            $stmt->bindValue(':escuela_id', $escuela_id, PDO::PARAM_INT);
            $stmt->bindValue(':usuario_imagen_url', $usuario_imagen_url, PDO::PARAM_STR);
            $stmt->bindValue(':usuario_estado', $usuario_estado, PDO::PARAM_STR);
            $stmt->bindValue(':usuario_documento', $usuario_documento, PDO::PARAM_STR);

            try {
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                echo "<script>alert('Usuario actualizado correctamente');</script>";
            } else {
                echo "<script>alert('No se realizaron cambios');</script>";
            }
            } catch (PDOException $e) {
            echo "Error en la actualización: " . $e->getMessage();
            }

        // MODIFICACIÓN: Cambiado 'affected_rows' a 'rowCount()' para PDO
        if ($stmt->rowCount() > 0) {
            echo "<script>alert('Usuario actualizado correctamente');</script>";
            // Actualizar la variable $usuario con los nuevos datos
            $usuario['usuario_nombre'] = $usuario_nombre;
            $usuario['usuario_apellido'] = $usuario_apellido;
            $usuario['usuario_correo'] = $usuario_correo;
            $usuario['usuario_telefono'] = $usuario_telefono;
            $usuario['usuario_direccion'] = $usuario_direccion;
            $usuario['usuario_nacimiento'] = $usuario_nacimiento;
            $usuario['usuario_tipo_documento'] = $usuario_tipo_documento;
            $usuario['rol_id'] = $rol_id;
            $usuario['escuela_id'] = $escuela_id;
            $usuario['usuario_imagen_url'] = $usuario_imagen_url;
            $usuario['usuario_estado'] = $usuario_estado;
        } else {
            echo "<script>alert('No se realizaron cambios');</script>";
        }
    } elseif (isset($_POST['eliminar'])) {
        // Preparar la consulta de eliminación
        $sql_delete = "DELETE FROM usuarios WHERE usuario_documento = :usuario_documento";
        $stmt = $conn->prepare($sql_delete);
        // MODIFICACIÓN: Agregado ':' al parámetro
        $stmt->bindValue(':usuario_documento', $usuario_documento, PDO::PARAM_STR);
        $stmt->execute();

        // MODIFICACIÓN: Cambiado 'affected_rows' a 'rowCount()' para PDO
        if ($stmt->rowCount() > 0) {
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
                        <?php if (!empty($usuario['usuario_imagen_url'])): ?>
                            <img src="<?= htmlspecialchars($usuario['usuario_imagen_url']) ?>" alt="Imagen de Perfil" class="mt-2 w-32 h-32 rounded-full object-cover border-4 border-yellow-500">
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="usuario_nombre" class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-user mr-2"></i>Nombre
                        </label>
                        <input type="text" id="usuario_nombre" name="usuario_nombre" value="<?= htmlspecialchars($usuario['usuario_nombre']) ?>" class="w-full p-2 border rounded-md input-sports" required>
                    </div>

                    <div>
                        <label for="usuario_apellido" class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-user mr-2"></i>Apellido
                        </label>
                        <input type="text" id="usuario_apellido" name="usuario_apellido" value="<?= htmlspecialchars($usuario['usuario_apellido']) ?>" class="w-full p-2 border rounded-md input-sports" required>
                    </div>

                    <div>
                        <label for="usuario_correo" class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-envelope mr-2"></i>Correo
                        </label>
                        <input type="email" id="usuario_correo" name="usuario_correo" value="<?= htmlspecialchars($usuario['usuario_correo']) ?>" class="w-full p-2 border rounded-md input-sports" required>
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
                        <select id="usuario_tipo_documento" name="usuario_tipo_documento" class="w-full p-2 border rounded-md input-sports" required>
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
                        <select id="rol_id" name="rol_id" class="w-full p-2 border rounded-md input-sports" required>
                            <?php foreach ($roles as $rol): ?>
                                <option value="<?= htmlspecialchars($rol['rol_id']) ?>" <?= $usuario['rol_id'] == $rol['rol_id'] ? 'selected' : '' ?>><?= htmlspecialchars($rol['rol_nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="escuela_id" class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-school mr-2"></i>Escuela
                        </label>
                        <select id="escuela_id" name="escuela_id" class="w-full p-2 border rounded-md input-sports" required>
                            <?php foreach ($escuelas_result as $escuela): ?>
                                <option value="<?= htmlspecialchars($escuela['escuela_id']) ?>" <?= $usuario['escuela_id'] == $escuela['escuela_id'] ? 'selected' : '' ?>><?= htmlspecialchars($escuela['escuela_nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="usuario_estado" class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-toggle-on mr-2"></i>Estado
                        </label>
                        <select id="usuario_estado" name="usuario_estado" class="w-full p-2 border rounded-md input-sports" required>
                            <option value="activo" <?= $usuario['usuario_estado'] == 'activo' ? 'selected' : '' ?>>Activo</option>
                            <option value="inactivo" <?= $usuario['usuario_estado'] == 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-4">
                    <button type="submit" name="actualizar" class="btn-sports px-6 py-2 rounded-full shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i>Actualizar
                    </button>
                    <button type="submit" name="eliminar" class="bg-red-500 text-white px-6 py-2 rounded-full shadow-md hover:bg-red-600 hover:shadow-lg transition duration-300" onclick="return confirm('¿Estás seguro de eliminar tu cuenta? Esta acción no se puede deshacer.');">
                        <i class="fas fa-trash-alt mr-2"></i>Eliminar
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
