<?php
session_start();
require '../PHP/conexion.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_correo'])) {
    header('Location: ../login.php');
    exit();
}

// Verificar si el usuario pertenece a la escuela de administración
$usuario_administracion_id = 1025531531; 
if ($_SESSION['usuario_documento'] != $usuario_administracion_id) {
    echo "Acceso denegado. No tienes permiso para ver este archivo.";
    exit();
}

// obtener todas las escuelas activas y excluir la escuela nombrada administración
$adminExcluir = 4;
$escuelas_query = "SELECT escuela_id, escuela_nombre, escuela_imagen_url, escuela_descripcion, escuela_direccion, escuela_telefono, escuela_correo, escuela_fecha_creacion FROM escuelas WHERE escuela_id <> :id AND escuela_estado = :estado";
$stmt = $conn->prepare($escuelas_query);
$stmt->bindValue(':id', $adminExcluir, PDO::PARAM_INT);
$stmt->bindValue(":estado", "activo", PDO::PARAM_STR);
$stmt->execute();
$schools = $stmt->fetchAll(PDO::FETCH_ASSOC);




// Manejar las operaciones CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update':
                // Lógica para actualizar una escuela
                $update_query = "UPDATE escuelas SET 
                    escuela_nombre = :nombre,
                    escuela_descripcion = :descripcion,
                    escuela_telefono = :telefono,
                    escuela_direccion = :direccion
                    WHERE escuela_id = :id";
                $stmt = $conn->prepare($update_query);
                $stmt->execute([
                    ':nombre' => $_POST['escuela_nombre'],
                    ':descripcion' => $_POST['escuela_descripcion'],
                    ':telefono' => $_POST['escuela_telefono'],
                    ':direccion' => $_POST['escuela_direccion'],
                    ':id' => $_POST['escuela_id']
                ]);
                break;
            case 'delete':
                // Lógica para eliminar una escuela (cambiar estado a inactivo)
                $delete_query = "UPDATE escuelas SET escuela_estado = 'inactivo' WHERE escuela_id = :id";
                $stmt = $conn->prepare($delete_query);
                $stmt->execute([':id' => $_POST['escuela_id']]);
                break;
        }
    }
}


?>

<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escuelas Aliadas - Classment Academy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@3.9.4/dist/full.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="../IMG/design/ca.jpg" type="image/x-icon">

    <style>
        .modal-box {
            max-width: 90vw;
        }
    </style>
</head>
<body class="bg-black-900 text-white">
     <!-- Barra de navegación -->
     <div class="container mx-auto p-4">
      <div class="navbar bg-transparent flex justify-between">
        <div class="navbar-start">
          <a href="../index.php" class="btn btn-ghost">
            <img src="../IMG/logo.png" alt="logo" class="w-f h-full" data-aos="zoom-in">
            <h1 class="text-4xl font-bold" data-aos="zoom-in">Classment Academy</h1>
          </a>
        </div>
        <div class="navbar-end hidden lg:flex pl-10pr-32" data-aos="zoom-in">
          <ul class="menu menu-horizontal px-1" data-aos="zoom-in">
            <li><a href="../index.php">Inicio</a></li>
            <li>
              <details>
                <summary>Servicios</summary>
                <ul class="p-2 bg-base-100">
                  <li><a href="../aliados.php">Escuelas</a></li>
                  <li><a href="cursos.php">Cursos</a></li>
                </ul>
              </details>
            </li>
            <li><a href="../index.php#nosotros">Nosotros</a></li>
          </ul>
        </div>
      </div>
    </div>
    
    <div class="container mx-auto p-4">
        <h1 class="text-4xl font-bold mb-8 text-center text-orange-400">Escuelas Aliadas</h1>
        <div class="text-center mb-8">
    <a href="../register_escuela.php" class="btn bg-orange-400 hover:bg-orange-500 text-white">
        <i class="fas fa-plus mr-2"></i>Crear Nueva Escuela
    </a>
</div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($schools as $escuela): ?>
                <div class="card bg-base-200 shadow-xl">
                    <figure><img src="../<?= htmlspecialchars($escuela['escuela_imagen_url']) ?>" alt="<?= htmlspecialchars($escuela['escuela_nombre']) ?>" class="w-full h-48 object-cover"/></figure>
                    <div class="card-body">
                        <h2 class="card-title text-orange-400"><?= htmlspecialchars($escuela['escuela_nombre']) ?></h2>
                        <p><?= htmlspecialchars(substr($escuela['escuela_descripcion'], 0, 100)) ?>...</p>
                        <div class="card-actions justify-end mt-4">
                            <label for="modal-<?= $escuela['escuela_id'] ?>" class="btn text-black bg-orange-400 hover:bg-orange-500">
                                <i class="fas fa-info-circle mr-2"></i>Ver Detalles
                            </label>
                            <label for="modal-edit-<?= $escuela['escuela_id'] ?>" class="btn text-black  bg-green-400 hover:bg-green-500">
                                <i class="fas fa-edit mr-2"></i>Editar
                            </label>
                            <label for="modal-delete-<?= $escuela['escuela_id'] ?>" class="btn text-black btn-error">
                                <i class="fas fa-trash-alt mr-2"></i>Eliminar
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Modal de Detalles -->
                <input type="checkbox" id="modal-<?= $escuela['escuela_id'] ?>" class="modal-toggle" />
                <div class="modal">
                    <div class="modal-box">
                        <h3 class="font-bold text-lg text-orange-400"><?= htmlspecialchars($escuela['escuela_nombre']) ?></h3>
                        <p class="py-4"><?= htmlspecialchars($escuela['escuela_descripcion']) ?></p>
                        <p><strong>Teléfono:</strong> <?= htmlspecialchars($escuela['escuela_telefono']) ?></p>
                        <p><strong>Dirección:</strong> <?= htmlspecialchars($escuela['escuela_direccion']) ?></p>
                        <p><strong>Fecha de Creación:</strong> <?= htmlspecialchars($escuela['escuela_fecha_creacion']) ?></p>
                        
                        <h4 class="font-bold mt-4 mb-2 text-orange-400">Cursos Asociados</h4>
                        <div class="overflow-x-auto">
                            <table class="table w-full">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Precio</th>
                                        <th>Cupos</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    // Consulta para obtener los cursos de la escuela actual
                                    $cursos_query = "SELECT curso_nombre, curso_precio, curso_cupos, curso_estado FROM cursos WHERE escuela_id = :escuela_id";
                                    $stmt_cursos = $conn->prepare($cursos_query);
                                    $stmt_cursos->bindValue(':escuela_id', $escuela['escuela_id'], PDO::PARAM_INT);
                                    $stmt_cursos->execute();
                                    $cursos = $stmt_cursos->fetchAll(PDO::FETCH_ASSOC);

                                    foreach ($cursos as $curso): 
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($curso['curso_nombre']) ?></td>
                                        <td>$<?= htmlspecialchars($curso['curso_precio']) ?> COP</td>
                                        <td><?= htmlspecialchars($curso['curso_cupos']) ?></td>
                                        <td><?= htmlspecialchars($curso['curso_estado']) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="modal-action">
                            <label for="modal-<?= $escuela['escuela_id'] ?>" class="btn">Cerrar</label>
                        </div>
                    </div>
                </div>

                <!-- Modal de Edición -->
                <input type="checkbox" id="modal-edit-<?= $escuela['escuela_id'] ?>" class="modal-toggle" />
                <div class="modal">
                    <div class="modal-box">
                        <h3 class="font-bold text-lg text-orange-400">Editar Escuela</h3>
                        <form action="escuelas.php" method="POST" class="space-y-4">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="escuela_id" value="<?= $escuela['escuela_id'] ?>">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Nombre</span>
                                </label>
                                <input type="text" name="escuela_nombre" value="<?= htmlspecialchars($escuela['escuela_nombre']) ?>" class="input input-bordered" required>
                            </div>
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Descripción</span>
                                </label>
                                <textarea name="escuela_descripcion" class="textarea textarea-bordered" required><?= htmlspecialchars($escuela['escuela_descripcion']) ?></textarea>
                            </div>
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Teléfono</span>
                                </label>
                                <input type="text" name="escuela_telefono" value="<?= htmlspecialchars($escuela['escuela_telefono']) ?>" class="input input-bordered" required>
                            </div>
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Dirección</span>
                                </label>
                                <input type="text" name="escuela_direccion" value="<?= htmlspecialchars($escuela['escuela_direccion']) ?>" class="input input-bordered" required>
                            </div>
                            <div class="modal-action">
                                <button type="submit" class="btn text-black bg-orange-400">Guardar Cambios</button>
                                <label for="modal-edit-<?= $escuela['escuela_id'] ?>" class="btn">Cancelar</label>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Modal de Eliminación -->
                <input type="checkbox" id="modal-delete-<?= $escuela['escuela_id'] ?>" class="modal-toggle" />
                <div class="modal">
                    <div class="modal-box">
                        <h3 class="font-bold text-lg text-red-500">Confirmar Eliminación</h3>
                        <p class="py-4">¿Estás seguro de que deseas eliminar la escuela "<?= htmlspecialchars($escuela['escuela_nombre']) ?>"? Esta acción no se puede deshacer.</p>
                        <form action="escuelas.php" method="POST">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="escuela_id" value="<?= $escuela['escuela_id'] ?>">
                            <div class="modal-action">
                                <button type="submit" class="btn btn-error">Sí, Eliminar</button>
                                <label for="modal-delete-<?= $escuela['escuela_id'] ?>" class="btn">Cancelar</label>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>