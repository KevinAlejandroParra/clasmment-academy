<?php
session_start();
require './PHP/conexion.php';


// Verificar si hay una escuela logueada

if (!isset($_SESSION['escuela_id'])) {
    header("Location: login_escuela.php");
    exit();
}

$escuela_id = $_SESSION['escuela_id'];
$error_message = $success_message = '';


// Obtener el ID del usuario logeado
$escuela_id = $_SESSION['escuela_id'];

// Verificar si el usuario pertenece a la administración
$admin = 5; 
if ($_SESSION['rol_id'] != $admin) {
    echo "Acceso denegado. No tienes permiso para ver este archivo.";
    exit();
}

// Consulta para obtener la escuela asociada al usuario logeado
$escuela_query = "SELECT e.* FROM escuelas e INNER JOIN usuarios u ON e.escuela_id = u.escuela_id WHERE e.escuela_id = :escuela_id";
$stmt = $conn->prepare($escuela_query);
$stmt->bindValue(":escuela_id", $escuela_id, PDO::PARAM_INT);
$stmt->execute();
$escuela = $stmt->fetch(PDO::FETCH_ASSOC);


// Manejar la CRUD para la escuela
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update_escuela':
                $update_query = "UPDATE escuelas SET 
                    escuela_nombre = :nombre,
                    escuela_nit = :documento,
                    escuela_descripcion = :descripcion,
                    escuela_telefono = :telefono,
                    escuela_direccion = :direccion,
                    escuela_correo = :correo
                    WHERE escuela_id = :escuela_id";
                $stmt = $conn->prepare($update_query);
                $stmt->execute([
                    ':nombre' => $_POST['escuela_nombre'],
                    ':documento' => $_POST['escuela_nit'],
                    ':descripcion' => $_POST['escuela_descripcion'],
                    ':telefono' => $_POST['escuela_telefono'],
                    ':direccion' => $_POST['escuela_direccion'],
                    ':correo' => $_POST['escuela_correo'],
                    ':escuela_id' => $escuela_id
                ]);
                $success_message = "Información de la escuela actualizada con éxito.";
                $escuela = $escuela; // Actualizar la información de la escuela
                break;
        }
    }
}
// Manejar la CRUD para los cursos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                // Lógica para crear un nuevo curso
                $create_query = "INSERT INTO cursos (curso_nombre, curso_descripcion, curso_precio, curso_cupos, curso_capacidad_maxima, curso_edad_minima, curso_fecha_inicio, curso_fecha_fin, curso_direccion, escuela_id) 
                                 VALUES (:nombre, :descripcion, :precio, :cupos, :capacidad, :edad_minima, :fecha_inicio, :fecha_fin, :direccion, :escuela_id)";
                $stmt = $conn->prepare($create_query);
                $stmt->execute([
                    ':nombre' => $_POST['curso_nombre'],
                    ':descripcion' => $_POST['curso_descripcion'],
                    ':precio' => $_POST['curso_precio'],
                    ':cupos' => $_POST['curso_cupos'],
                    ':capacidad' => $_POST['curso_capacidad_maxima'],
                    ':edad_minima' => $_POST['curso_edad_minima'],
                    ':fecha_inicio' => $_POST['curso_fecha_inicio'],
                    ':fecha_fin' => $_POST['curso_fecha_fin'],
                    ':direccion' => $_POST['curso_direccion'],
                    ':escuela_id' => $escuela['escuela_id']
                ]);
                break;
            case 'update':
                // Lógica para actualizar un curso
                $update_query = "UPDATE cursos SET 
                    curso_nombre = :nombre,
                    curso_descripcion = :descripcion,
                    curso_precio = :precio,
                    curso_cupos = :cupos,
                    curso_capacidad_maxima = :capacidad,
                    curso_edad_minima = :edad_minima,
                    curso_fecha_inicio = :fecha_inicio,
                    curso_fecha_fin = :fecha_fin,
                    curso_direccion = :direccion
                    WHERE curso_id = :id AND escuela_id = :escuela_id";
                $stmt = $conn->prepare($update_query);
                $stmt->execute([
                    ':nombre' => $_POST['curso_nombre'],
                    ':descripcion' => $_POST['curso_descripcion'],
                    ':precio' => $_POST['curso_precio'],
                    ':cupos' => $_POST['curso_cupos'],
                    ':capacidad' => $_POST['curso_capacidad_maxima'],
                    ':edad_minima' => $_POST['curso_edad_minima'],
                    ':fecha_inicio' => $_POST['curso_fecha_inicio'],
                    ':fecha_fin' => $_POST['curso_fecha_fin'],
                    ':direccion' => $_POST['curso_direccion'],
                    ':id' => $_POST['curso_id'],
                    ':escuela_id' => $escuela['escuela_id']
                ]);
                break;
            case 'delete':
                // Lógica para eliminar un curso (cambiar estado a inactivo)
                $delete_query = "UPDATE cursos SET curso_estado = 'inactivo' WHERE curso_id = :id AND escuela_id = :escuela_id";
                $stmt = $conn->prepare($delete_query);
                $stmt->execute([':id' => $_POST['curso_id'], ':escuela_id' => $escuela['escuela_id']]);
                break;
        }
        // Redirigir para evitar reenvíos del formulario
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Consulta para obtener todos los cursos asociados a la escuela
$cursos_query = "SELECT c.* FROM cursos c WHERE c.escuela_id = :escuela_id AND c.curso_estado != 'inactivo'";
$stmt_cursos = $conn->prepare($cursos_query);
$stmt_cursos->bindValue(':escuela_id', $escuela['escuela_id'], PDO::PARAM_INT);
$stmt_cursos->execute();
$cursos = $stmt_cursos->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Cursos - <?= htmlspecialchars($escuela['escuela_nombre']) ?></title>
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
<body class="bg-black text-white">
       <!-- Círculos de gradiente -->
   <div class="fixed w-96 h-96 bg-orange-300 top-0 left-0 rounded-full filter blur-[100px] opacity-30 animate-blob"></div>
   <div class="fixed w-96 h-96 bg-orange-400 top-1/4 right-0 rounded-full filter blur-[100px] opacity-20 animate-blob animation-delay-2000"></div>
   <div class="fixed w-96 h-96 bg-orange-300 bottom-0 left-1/4 rounded-full filter blur-[100px] opacity-30 animate-blob animation-delay-4000"></div>
   <div class="fixed w-96 h-96 bg-orange-400 bottom-1/4 right-1/4 rounded-full filter blur-[100px] opacity-20 animate-blob"></div>
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
        <h1 class="text-4xl font-bold mb-8 text-center text-orange-400">PERFIL DE ESCUELA</h1>
        
        <!-- Información de la Escuela -->
        <div class="card bg-black  shadow-lg shadow-orange-500/50 mb-8">
            <div class="card-body">
                <h2 class="card-title text-2xl text-orange-400 mb-4">Información de la Escuela</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <p><strong>Nombre:</strong> <?= htmlspecialchars($escuela['escuela_nombre']) ?></p>
                    <p><strong>NIT:</strong> <?= htmlspecialchars($escuela['escuela_nit']) ?></p>
                    <p><strong>Teléfono:</strong> <?= htmlspecialchars($escuela['escuela_telefono']) ?></p>
                    <p><strong>Correo:</strong> <?= htmlspecialchars($escuela['escuela_correo']) ?></p>
                    <p><strong>Dirección:</strong> <?= htmlspecialchars($escuela['escuela_direccion']) ?></p>
                    <p><strong>Estado:</strong> <?= htmlspecialchars($escuela['escuela_estado']) ?></p>
                </div>
                <p class="mt-4"><strong>Descripción:</strong> <?= htmlspecialchars($escuela['escuela_descripcion']) ?></p>
                <div class="card-actions justify-end mt-4">
                    <label for="modal-edit-escuela" class="btn text-black bg-orange-400 hover:bg-orange-500">
                        <i class="fas fa-edit mr-2"></i>Editar Información
                    </label>
                </div>
            </div>
        </div>

        <!-- Modal de Edición de Escuela -->
        <input type="checkbox" id="modal-edit-escuela" class="modal-toggle" />
        <div class="modal">
            <div class="modal-box">
                <h3 class="font-bold text-lg text-orange-400">Editar Información de la Escuela</h3>
                <form action="escuela_perfil.php" method="POST" class="space-y-4">
                    <input type="hidden" name="action" value="update_escuela">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Nombre</span>
                        </label>
                        <input type="text" name="escuela_nombre" value="<?= htmlspecialchars($escuela['escuela_nombre']) ?>" class="input input-bordered" required>
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Documento (NIT)</span>
                        </label>
                        <input type="text" name="escuela_nit" value="<?= htmlspecialchars($escuela['escuela_nit']) ?>" class="input input-bordered" required>
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
                        <input type="tel" name="escuela_telefono" value="<?= htmlspecialchars($escuela['escuela_telefono']) ?>" class="input input-bordered" required>
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Dirección</span>
                        </label>
                        <input type="text" name="escuela_direccion" value="<?= htmlspecialchars($escuela['escuela_direccion']) ?>" class="input input-bordered" required>
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Correo</span>
                        </label>
                        <input type="email" name="escuela_correo" value="<?= htmlspecialchars($escuela['escuela_correo']) ?>" class="input input-bordered" required>
                    </div>
                    <div class="modal-action">
                        <button type="submit" class="btn text-black bg-orange-400">Guardar Cambios</button>
                        <label for="modal-edit-escuela" class="btn">Cancelar</label>
                    </div>
                </form>
            </div>
        </div>
    <div class="container mx-auto p-4">
        <h1 class="text-4xl font-bold mb-8 text-center text-orange-400">CURSOS </h1>
        
        <!-- Botón para agregar nuevo curso -->
        <div class="text-center mb-6">
            <label for="modal-create" class="btn text-black bg-orange-400 hover:bg-orange-500">
                <i class="fas fa-plus mr-2"></i>Agregar Nuevo Curso
            </label>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($cursos as $curso): ?>
                <div class="card bg-black  shadow-lg shadow-orange-500/50">
                    <figure><img src="../<?= htmlspecialchars($curso['curso_imagen_url']) ?>" alt="<?= htmlspecialchars($curso['curso_nombre']) ?>" class="w-full h-48 object-cover"/></figure>
                    <div class="card-body">
                        <h2 class="card-title text-orange-400"><?= htmlspecialchars($curso['curso_nombre']) ?></h2>
                        <p><?= htmlspecialchars(substr($curso['curso_descripcion'], 0, 100)) ?>...</p>
                        <div class="card-actions justify-end mt-4">
                            <label for="modal-<?= $curso['curso_id'] ?>" class="btn text-black bg-orange-400 hover:bg-orange-500">
                                <i class="fas fa-info-circle mr-2"></i>Ver Detalles
                            </label>
                            <label for="modal-edit-<?= $curso['curso_id'] ?>" class="btn text-black  bg-green-400 hover:bg-green-500">
                                <i class="fas fa-edit mr-2"></i>Editar
                            </label>
                            <label for="modal-delete-<?= $curso['curso_id'] ?>" class="btn text-black btn-error">
                                <i class="fas fa-trash-alt mr-2"></i>Eliminar
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Modal de Detalles -->
                <input type="checkbox" id="modal-<?= $curso['curso_id'] ?>" class="modal-toggle" />
                <div class="modal">
                    <div class="modal-box">
                        <h3 class="font-bold text-lg text-orange-400"><?= htmlspecialchars($curso['curso_nombre']) ?></h3>
                        <p class="py-4"><?= htmlspecialchars($curso['curso_descripcion']) ?></p>
                        <p><strong>Precio:</strong> $<?= number_format($curso['curso_precio'], 2) ?></p>
                        <p><strong>Cupos:</strong> <?= htmlspecialchars($curso['curso_cupos']) ?></p>
                        <p><strong>Capacidad Máxima:</strong> <?= htmlspecialchars($curso['curso_capacidad_maxima']) ?></p>
                        <p><strong>Edad Mínima:</strong> <?= htmlspecialchars($curso['curso_edad_minima']) ?> años</p>
                        <p><strong>Fecha de Inicio:</strong> <?= htmlspecialchars($curso['curso_fecha_inicio']) ?></p>
                        <p><strong>Fecha de Fin:</strong> <?= htmlspecialchars($curso['curso_fecha_fin']) ?></p>
                        <p><strong>Dirección:</strong> <?= htmlspecialchars($curso['curso_direccion']) ?></p>
                        <div class="modal-action">
                            <label for="modal-<?= $curso['curso_id'] ?>" class="btn">Cerrar</label>
                        </div>
                    </div>
                </div>

                <!-- Modal de Edición -->
                <input type="checkbox" id="modal-edit-<?= $curso['curso_id'] ?>" class="modal-toggle" />
                <div class="modal">
                    <div class="modal-box">
                        <h3 class="font-bold text-lg text-orange-400">Editar Curso</h3>
                        <form action="escuela_perfil.php" method="POST" class="space-y-4">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="curso_id" value="<?= $curso['curso_id'] ?>">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Nombre</span>
                                </label>
                                <input type="text" name="curso_nombre" value="<?= htmlspecialchars($curso['curso_nombre']) ?>" class="input input-bordered" required>
                            </div>
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Descripción</span>
                                </label>
                                <textarea name="curso_descripcion" class="textarea textarea-bordered" required><?= htmlspecialchars($curso['curso_descripcion']) ?></textarea>
                            </div>
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Precio</span>
                                </label>
                                <input type="number" name="curso_precio" value="<?= htmlspecialchars($curso['curso_precio']) ?>" class="input input-bordered" step="0.01" required>
                            </div>
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Cupos</span>
                                </label>
                                <input type="number" name="curso_cupos" value="<?= htmlspecialchars($curso['curso_cupos']) ?>" class="input input-bordered" required>
                            </div>
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Capacidad Máxima</span>
                                </label>
                                <input type="number" name="curso_capacidad_maxima" value="<?= htmlspecialchars($curso['curso_capacidad_maxima']) ?>" class="input input-bordered" required>
                            </div>
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Edad Mínima</span>
                                </label>
                                <input type="number" name="curso_edad_minima" value="<?= htmlspecialchars($curso['curso_edad_minima']) ?>" class="input input-bordered" required>
                            </div>
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Fecha de Inicio</span>
                                </label>
                                <input type="date" name="curso_fecha_inicio" value="<?= htmlspecialchars($curso['curso_fecha_inicio']) ?>" class="input input-bordered" required>
                            </div>
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Fecha de Fin</span>
                                </label>
                                <input type="date" name="curso_fecha_fin" value="<?= htmlspecialchars($curso['curso_fecha_fin']) ?>" class="input input-bordered" required>
                               </div>
                                <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Dirección</span>
                                </label>
                                <input type="text" name="curso_direccion" value="<?= htmlspecialchars($curso['curso_direccion']) ?>" class="input input-bordered" required>
                            </div>
                            <div class="modal-action">
                                <button type="submit" class="btn text-black bg-orange-400">Guardar Cambios</button>
                                <label for="modal-edit-<?= $curso['curso_id'] ?>" class="btn">Cancelar</label>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Modal de Eliminación -->
                <input type="checkbox" id="modal-delete-<?= $curso['curso_id'] ?>" class="modal-toggle" />  
                <div class="modal">
                    <div class="modal-box">
                        <h3 class="font-bold text-lg text-red-500">Confirmar Eliminación</h3>
                        <p class="py-4">¿Estás seguro de que deseas eliminar el curso "<?= htmlspecialchars($curso['curso_nombre']) ?>"? Esta acción no se puede deshacer.</p>
                        <form action="escuela_perfil.php" method="POST">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="curso_id" value="<?= $curso['curso_id'] ?>">
                            <div class="modal-action">
                                <button type="submit" class="btn btn-error">Sí, Eliminar</button>
                                <label for="modal-delete-<?= $curso['curso_id'] ?>" class="btn">Cancelar</label>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Modal para Crear Nuevo Curso -->
    <input type="checkbox" id="modal-create" class="modal-toggle" />
    <div class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg text-orange-400">Crear Nuevo Curso</h3>
            <form action="escuela_perfil.php" method="POST" class="space-y-4">
                <input type="hidden" name="action" value="create">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Nombre</span>
                    </label>
                    <input type="text" name="curso_nombre" class="input input-bordered" required>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Descripción</span>
                    </label>
                    <textarea name="curso_descripcion" class="textarea textarea-bordered" required></textarea>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Precio</span>
                    </label>
                    <input type="number" name="curso_precio" class="input input-bordered" step="0.01" required>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Cupos</span>
                    </label>
                    <input type="number" name="curso_cupos" class="input input-bordered" required>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Capacidad Máxima</span>
                    </label>
                    <input type="number" name="curso_capacidad_maxima" class="input input-bordered" required>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Edad Mínima</span>
                    </label>
                    <input type="number" name="curso_edad_minima" class="input input-bordered" required>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Fecha de Inicio</span>
                    </label>
                    <input type="date" name="curso_fecha_inicio" class="input input-bordered" required>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Fecha de Fin</span>
                    </label>
                    <input type="date" name="curso_fecha_fin" class="input input-bordered" required>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Dirección</span>
                    </label>
                    <input type="text" name="curso_direccion" class="input input-bordered" required>
                </div>
                <div class="modal-action">
                    <button type="submit" class="btn text-black bg-orange-400">Crear Curso</button>
                    <label for="modal-create" class="btn">Cancelar</label>
                </div>
            </form>
        </div>
    </div>

</body>
</html>