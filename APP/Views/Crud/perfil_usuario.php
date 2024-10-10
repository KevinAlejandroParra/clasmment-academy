<?php
require "../../../PUBLIC/Config/conexion.php";
session_start();

// verificar si el usuario se ha autenticado
if (!isset($_SESSION['usuario_documento'])) {
    header("Location: login.php");
    exit();
}

$usuario = $_SESSION['usuario_documento'];
$error_message = $success_message = '';

try {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta que obtiene la información del usuario
    $usuarios_query = "SELECT u.*, r.rol_nombre, e.escuela_nombre 
                       FROM usuarios u
                       INNER JOIN roles r ON u.rol_id = r.rol_id
                       INNER JOIN escuelas e ON u.escuela_id = e.escuela_id
                       WHERE u.usuario_estado = :estado AND u.usuario_documento = :usuario_documento";
    $stmt = $conn->prepare($usuarios_query);
    $stmt->bindValue(":estado", "activo", PDO::PARAM_STR);
    $stmt->bindValue(":usuario_documento", $usuario, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception("No se encontró el usuario.");
    }
 
    //Consulta que obtiene la lista de roles
    $roles_query = "SELECT * FROM roles";
    $roles_stmt = $conn->query($roles_query);
    $roles = $roles_stmt->fetchAll(PDO::FETCH_ASSOC);

// Consulta que obtiene la lista de escuelas
    $escuelas_query = "SELECT * FROM escuelas";
    $escuelas_stmt = $conn->query($escuelas_query);
    $escuelas = $escuelas_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Manejo de formularios
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['update'])) {
            // Manejo de la imagen de perfil
            if (isset($_FILES['usuario_imagen_url']) && $_FILES['usuario_imagen_url']['error'] == 0) {
                $usuario_imagen_url = '../../../PUBLIC/Img/' . time() . '_' . $_FILES['usuario_imagen_url']['name'];
                move_uploaded_file($_FILES['usuario_imagen_url']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $usuario_imagen_url);
            } else {
                $usuario_imagen_url = $user['usuario_imagen_url'];
            }

            // Actualizar información del usuario
            $update_query = "UPDATE usuarios SET 
                usuario_nombre = :nombre,
                usuario_apellido = :apellido,
                usuario_correo = :correo,
                usuario_telefono = :telefono,
                usuario_direccion = :direccion,
                usuario_nacimiento = :nacimiento,
                rol_id = :rol_id,
                escuela_id = :escuela_id,
                usuario_imagen_url = :imagen_url
                WHERE usuario_documento = :documento";
            
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bindValue(':nombre', $_POST['usuario_nombre'], PDO::PARAM_STR);
            $update_stmt->bindValue(':apellido', $_POST['usuario_apellido'], PDO::PARAM_STR);
            $update_stmt->bindValue(':correo', $_POST['usuario_correo'], PDO::PARAM_STR);
            $update_stmt->bindValue(':telefono', $_POST['usuario_telefono'], PDO::PARAM_STR);
            $update_stmt->bindValue(':direccion', $_POST['usuario_direccion'], PDO::PARAM_STR);
            $update_stmt->bindValue(':nacimiento', $_POST['usuario_nacimiento'], PDO::PARAM_STR);
            $update_stmt->bindValue(':rol_id', $_POST['rol_id'], PDO::PARAM_INT);
            $update_stmt->bindValue(':escuela_id', $_POST['escuela_id'], PDO::PARAM_INT);
            $update_stmt->bindValue(':imagen_url', $usuario_imagen_url, PDO::PARAM_STR);
            $update_stmt->bindValue(':documento', $usuario, PDO::PARAM_STR);

            if ($update_stmt->execute()) {
                $success_message = "Perfil actualizado exitosamente.";
                // Actualizar datos del usuario
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $error_message = "Error al actualizar el perfil.";
            }
        } elseif (isset($_POST['delete'])) {
            // Desactivar cuenta de usuario (borrado suave)
            $delete_query = "UPDATE usuarios SET usuario_estado = 'inactivo' WHERE usuario_documento = :documento";
            $delete_stmt = $conn->prepare($delete_query);
            $delete_stmt->bindValue(':documento', $usuario, PDO::PARAM_STR);

            if ($delete_stmt->execute()) {
                session_destroy();
                header("Location: login.php");
                exit();
            } else {
                $error_message = "Error al desactivar la cuenta.";
            }
        }
    }
} catch (PDOException $e) {
    $error_message = "Error de base de datos: " . $e->getMessage();
} catch (Exception $e) {
    $error_message = $e->getMessage();
}
?>

<!DOCTYPE html>
<html data-theme="black" lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu Perfil - Classment Academy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@3.9.4/dist/full.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <style>
    .gradient-circle {
      position: absolute;
      border-radius: 50%;
      filter: blur(50px);
    }
    .alert {
        transition: opacity 0.3s ease-in-out;
    }
    </style>
</head>
<body class="bg-black h-screen overflow-auto">
    <!-- Barra de navegación -->
    <div class="container mx-auto p-4">
      <div class="navbar bg-transparent flex justify-between">
        <div class="navbar-start">
          <a href="../../../PUBLIC/index.php" class="btn btn-ghost">
            <img src="../../../PUBLIC/IMG/logo.png" alt="logo" class="w-f h-full" data-aos="zoom-in">
            <h1 class="text-4xl font-bold" data-aos="zoom-in">Classment Academy</h1>
          </a>
        </div>
        <div class="navbar-end hidden lg:flex pl-10pr-32" data-aos="zoom-in">
          <ul class="menu menu-horizontal px-1" data-aos="zoom-in">
            <li><a href="../PUBLIC/index.php">Inicio</a></li>
            <li>
              <details>
                <summary>Servicios</summary>
                <ul class="p-2 bg-base-100">
                  <li><a href="escuelas.php">Escuelas</a></li>
                  <li><a href="cursos.php">Cursos</a></li>
                </ul>
              </details>
            </li>
            <li><a href="nosotros.php">Nosotros</a></li>
          </ul>
        </div>
      </div>
    </div>
    <!-- Círculos de gradiente -->
    <div class="gradient-circle w-32 h-32 bg-orange-300 top-10 left-10 opacity-50"></div>
    <div class="gradient-circle w-64 h-64 bg-orange-400 top-10 right-10 opacity-30"></div>
    <div class="gradient-circle w-64 h-64 bg-orange-300 bottom-10 left-10 opacity-30"></div>
    <div class="gradient-circle w-64 h-64 bg-orange-300 bottom-10 right-1/4 opacity-30"></div>

    <!-- Contenido principal -->
    <div class="container mx-auto p-4">
        <?php if ($error_message): ?>
            <div id="error-alert" class="alert alert-error shadow-lg mb-8" data-aos="fade-down">
                <div class="flex justify-between w-full">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span><?= htmlspecialchars($error_message) ?></span>
                    </div>
                    <button onclick="closeAlert('error-alert')" class="btn btn-ghost btn-sm">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <div id="success-alert" class="alert alert-success shadow-lg mb-8" data-aos="fade-down">
                <div class="flex justify-between w-full">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span><?= htmlspecialchars($success_message) ?></span>
                    </div>
                    <button onclick="closeAlert('success-alert')" class="btn btn-ghost btn-sm">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        <?php endif; ?>

        <!-- Tarjeta de perfil principal -->
        <div class="card bg-base-200 shadow-xl mb-8 border-t-4 border-orange-400" data-aos="fade-up">
            <div class="card-body">
                <div class="flex flex-col md:flex-row items-center">
                    <div class="avatar">
                        <div class="w-24 rounded-full ring ring-orange-400 ring-offset-base-100 ring-offset-2">
                            <img src="<?= htmlspecialchars($user['usuario_imagen_url']) ?>" alt="Foto de perfil" />
                        </div>
                    </div>
                    <div class="ml-4 text-center md:text-left">
                        <h2 class="card-title text-3xl text-orange-400"><?= htmlspecialchars($user['usuario_nombre']) . ' ' . htmlspecialchars($user['usuario_apellido']) ?></h2>
                        <p class="text-xl"><?= htmlspecialchars($user['usuario_correo']) ?></p>
                        <div class="mt-4 flex space-x-4 ">
                            <label for="edit-modal" class="btn btn-primary ">
                                <i class="fas fa-edit mr-2"></i>Editar Perfil
                            </label>
                            <?php if ($user['rol_id'] == 5): ?>
                             <a href="../Crud/escuela_perfil.php" class="btn btn-primary ">
                                <i class="fa-solid fa-school"></i>Ver mi escuela
                            </a>
                            <?php endif; ?>
                       
                            <a href="../../Helpers/logout.php" class="btn btn-secondary">
                              <i class="fas fa-sign-out-alt mr-2"></i>Cerrar Sesión
                               </a>

                            <label for="delete-modal" class="btn btn-error">
                                <i class="fas fa-trash-alt mr-2"></i>Eliminar Cuenta
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información completa del usuario -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="stat bg-base-200 rounded-lg p-4 shadow-md" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-figure text-orange-400">
                    <i class="fas fa-id-card fa-2x"></i>
                </div>
                <div class="stat-title">Tipo de Documento</div>
                <div class="stat-value text-orange-400"><?= htmlspecialchars($user['usuario_tipo_documento']) ?></div>
            </div>
            <div class="stat bg-base-200 rounded-lg p-4 shadow-md" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-figure text-orange-400">
                    <i class="fas fa-fingerprint fa-2x"></i>
                </div>
                <div class="stat-title">Número de Documento</div>
                <div class="stat-value text-orange-400"><?= htmlspecialchars($user['usuario_documento']) ?></div>
            </div>
            <div class="stat bg-base-200 rounded-lg p-4 shadow-md" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-figure text-orange-400">
                    <i class="fas fa-phone fa-2x"></i>
                </div>
                <div class="stat-title">Teléfono</div>
                <div class="stat-value text-orange-400"><?= htmlspecialchars($user['usuario_telefono']) ?></div>
            </div>
            <div class="stat bg-base-200 rounded-lg p-4 shadow-md" data-aos="fade-up" data-aos-delay="400">
                <div class="stat-figure text-orange-400">
                    <i class="fas fa-map-marker-alt fa-2x"></i>
                </div>
                <div class="stat-title">Dirección</div>
                <div class="stat-value text-orange-400 text-lg"><?= htmlspecialchars($user['usuario_direccion']) ?></div>
            </div>
            <div class="stat bg-base-200 rounded-lg p-4 shadow-md" data-aos="fade-up" data-aos-delay="500">
                <div class="stat-figure text-orange-400">
                    <i class="fas fa-birthday-cake fa-2x"></i>
                </div>
                <div class="stat-title">Fecha de Nacimiento</div>
                <div class="stat-value text-orange-400"><?= htmlspecialchars($user['usuario_nacimiento']) ?></div>
            </div>
            <div class="stat bg-base-200 rounded-lg p-4 shadow-md" data-aos="fade-up" data-aos-delay="600">
                <div class="stat-figure text-orange-400">
                    <i class="fas fa-user-tag fa-2x"></i>
                </div>
                <div class="stat-title">Rol</div>
                <div class="stat-value text-orange-400"><?= htmlspecialchars($user['rol_nombre']) ?></div>
            </div>
            <div class="stat bg-base-200 rounded-lg p-4 shadow-md" data-aos="fade-up">
                <div class="stat-figure text-orange-400">
                    <i class="fas fa-school fa-2x"></i>
                </div>
                <div class="stat-title">Escuela</div>
                <div class="stat-value text-orange-400"><?= htmlspecialchars($user['escuela_nombre']) ?></div>
            </div>
            <div class="stat bg-base-200 rounded-lg p-4 shadow-md" data-aos="fade-up">
                <div class="stat-figure text-orange-400">
                    <i class="fas fa-calendar-alt fa-2x"></i>
                </div>
                <div class="stat-title">Fecha de Creación</div>
                <div class="stat-value text-orange-400"><?= htmlspecialchars(date('d/m/Y', strtotime($user['usuario_fecha_creacion']))) ?></div>
            </div>
        </div>

        <!-- Modal para editar perfil -->
        <input type="checkbox" id="edit-modal" class="modal-toggle" />
        <div class="modal">
            <div class="modal-box bg-base-300 w-11/12 max-w-3xl">
                <h3 class="font-bold text-lg text-orange-400 mb-4">Edita tu Perfil</h3>
                <form action="perfil_usuario.php" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text text-orange-400">Nombre</span>
                        </label>
                        <input type="text" name="usuario_nombre" value="<?= htmlspecialchars($user['usuario_nombre']) ?>" class="input input-bordered bg-base-200" required />
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text text-orange-400">Apellido</span>
                        </label>
                        <input type="text" name="usuario_apellido" value="<?= htmlspecialchars($user['usuario_apellido']) ?>" class="input input-bordered bg-base-200" required />
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text text-orange-400">Correo</span>
                        </label>
                        <input type="email" name="usuario_correo" value="<?= htmlspecialchars($user['usuario_correo']) ?>" class="input input-bordered bg-base-200" required />
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text text-orange-400">Teléfono</span>
                        </label>
                        <input type="text" name="usuario_telefono" value="<?= htmlspecialchars($user['usuario_telefono']) ?>" class="input input-bordered bg-base-200" />
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text text-orange-400">Dirección</span>
                        </label>
                        <input type="text" name="usuario_direccion" value="<?= htmlspecialchars($user['usuario_direccion']) ?>" class="input input-bordered bg-base-200" />
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text text-orange-400">Fecha de Nacimiento</span>
                        </label>
                        <input type="date" name="usuario_nacimiento" value="<?= htmlspecialchars($user['usuario_nacimiento']) ?>" class="input input-bordered bg-base-200" />
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text text-orange-400">Rol</span>
                        </label>
                        <select name="rol_id" class="select select-bordered bg-base-200">
                            <?php foreach ($roles as $rol): ?>
                                <option value="<?= $rol['rol_id'] ?>" <?= $user['rol_id'] == $rol['rol_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($rol['rol_nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text text-orange-400">Escuela</span>
                        </label>
                        <select name="escuela_id" class="select select-bordered bg-base-200">
                            <?php foreach ($escuelas as $escuela): ?>
                                <option value="<?= $escuela['escuela_id'] ?>" <?= $user['escuela_id'] == $escuela['escuela_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($escuela['escuela_nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-control col-span-2">
                        <label class="label">
                            <span class="label-text text-orange-400">Foto de Perfil</span>
                        </label>
                        <input type="file" name="usuario_imagen_url" class="file-input file-input-bordered bg-base-200 w-full" />
                    </div>
                    <div class="modal-action col-span-2">
                        <button type="submit" name="update" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>Guardar Cambios
                        </button>
                        <label for="edit-modal" class="btn">Cancelar</label>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal para confirmar eliminación de cuenta -->
        <input type="checkbox" id="delete-modal" class="modal-toggle" />
        <div class="modal">
            <div class="modal-box bg-base-300 border-t-4 border-red-500">
                <h3 class="font-bold text-lg text-red-500">¿Estás seguro de que quieres eliminar tu cuenta?</h3>
                <p class="py-4">Esta acción no se puede deshacer. Todos tus datos serán eliminados permanentemente.</p>
                <div class="modal-action">
                    <form action="perfil_usuario.php" method="POST">
                        <button type="submit" name="delete" class="btn btn-error">
                            <i class="fas fa-trash-alt mr-2"></i>Sí, Eliminar mi Cuenta
                        </button>
                    </form>
                    <label for="delete-modal" class="btn">Cancelar</label>
                </div>
            </div>
        </div>
    </div>

    <script>
        AOS.init({
            duration: 1000,
            once: true,
        });

        function closeAlert(alertId) {
            const alert = document.getElementById(alertId);
            if (alert) {
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 300);
            }
        }

        // Cerrar automáticamente las alertas después de 5 segundos
        setTimeout(() => {
            closeAlert('success-alert');
            closeAlert('error-alert');
        }, 5000);
    </script>

                    
