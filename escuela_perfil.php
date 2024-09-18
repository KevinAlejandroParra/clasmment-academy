<?php
require 'PHP/conexion.php';
session_start();

if (!isset($_SESSION['usuario_documento']) || $_SESSION['rol_id'] != 5) {
    header("Location: login.php");
    exit();
}

$usuario_documento = $_SESSION['usuario_documento'];
$error_message = $success_message = '';

try {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener información de la escuela
    $escuela_query = "SELECT e.* FROM escuelas e
                      INNER JOIN usuarios u ON e.escuela_id = u.escuela_id
                      WHERE u.usuario_documento = :usuario_documento";
    $escuela_stmt = $conn->prepare($escuela_query);
    $escuela_stmt->execute([':usuario_documento' => $usuario_documento]);
    $escuela = $escuela_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$escuela) {
        throw new Exception("No se encontró la escuela asociada al usuario.");
    }

    $escuela_id = $escuela['escuela_id'];

    // Procesar la actualización de la escuela
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['actualizar_escuela'])) {
        $update_query = "UPDATE escuelas SET 
                         escuela_nombre = :nombre, 
                         escuela_descripcion = :descripcion, 
                         escuela_telefono = :telefono, 
                         escuela_direccion = :direccion, 
                         escuela_correo = :correo
                         WHERE escuela_id = :escuela_id";
        $update_stmt = $conn->prepare($update_query);
        $resultado = $update_stmt->execute([
            ':nombre' => $_POST['nombre'],
            ':descripcion' => $_POST['descripcion'],
            ':telefono' => $_POST['telefono'],
            ':direccion' => $_POST['direccion'],
            ':correo' => $_POST['correo'],
            ':escuela_id' => $escuela_id
        ]);

        if ($resultado) {
            $success_message = "Información de la escuela actualizada con éxito.";
            $escuela_stmt->execute([':usuario_documento' => $usuario_documento]);
            $escuela = $escuela_stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $error_message = "Error al actualizar la información de la escuela.";
        }
    }

    // Procesar la actualización del curso
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['actualizar_curso'])) {
        $update_curso_query = "UPDATE cursos SET 
                               curso_nombre = :nombre, 
                               curso_descripcion = :descripcion, 
                               curso_precio = :precio
                               WHERE curso_id = :curso_id AND escuela_id = :escuela_id";
        $update_curso_stmt = $conn->prepare($update_curso_query);
        $resultado_curso = $update_curso_stmt->execute([
            ':nombre' => $_POST['curso_nombre'],
            ':descripcion' => $_POST['curso_descripcion'],
            ':precio' => $_POST['curso_precio'],
            ':curso_id' => $_POST['curso_id'],
            ':escuela_id' => $escuela_id
        ]);

        if ($resultado_curso) {
            $success_message = "Información del curso actualizada con éxito.";
        } else {
            $error_message = "Error al actualizar la información del curso.";
        }
    }

    // Obtener cursos de la escuela con información de profesores
    $cursos_query = "SELECT c.*, 
                     COUNT(DISTINCT m.matricula_id) as num_clientes,
                     GROUP_CONCAT(DISTINCT CONCAT(u.usuario_nombre, ' ', u.usuario_apellido) SEPARATOR ', ') as profesores
                     FROM cursos c
                     LEFT JOIN matriculas m ON c.curso_id = m.curso_id
                     LEFT JOIN profesores_cursos pc ON c.curso_id = pc.curso_id
                     LEFT JOIN usuarios u ON pc.usuario_documento = u.usuario_documento
                     WHERE c.escuela_id = :escuela_id
                     GROUP BY c.curso_id";
    $cursos_stmt = $conn->prepare($cursos_query);
    $cursos_stmt->execute([':escuela_id' => $escuela_id]);
    $cursos = $cursos_stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error_message = "Error de base de datos: " . $e->getMessage();
} catch (Exception $e) {
    $error_message = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Escuela</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@3.9.4/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-black text-white">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold mb-8 text-center text-orange-400">Perfil de Escuela</h1>
        
        <?php if ($success_message): ?>
            <div class="alert alert-success shadow-lg mb-8">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span><?php echo $success_message; ?></span>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="alert alert-error shadow-lg mb-8">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span><?php echo $error_message; ?></span>
                </div>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-gray-900 shadow-lg shadow-orange-400/20 rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-4 text-orange-400">Información de la Escuela</h2>
                <p><strong class="text-orange-400">Nombre:</strong> <?php echo htmlspecialchars($escuela['escuela_nombre']); ?></p>
                <p><strong class="text-orange-400">Descripción:</strong> <?php echo htmlspecialchars($escuela['escuela_descripcion']); ?></p>
                <p><strong class="text-orange-400">Teléfono:</strong> <?php echo htmlspecialchars($escuela['escuela_telefono']); ?></p>
                <p><strong class="text-orange-400">Dirección:</strong> <?php echo htmlspecialchars($escuela['escuela_direccion']); ?></p>
                <p><strong class="text-orange-400">Correo:</strong> <?php echo htmlspecialchars($escuela['escuela_correo']); ?></p>
                <button class="btn bg-orange-400 hover:bg-orange-500 text-white mt-4" onclick="document.getElementById('modal-editar-escuela').showModal()">Editar Información</button>
            </div>

            <div class="bg-gray-900 shadow-lg shadow-orange-400/20 rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-4 text-orange-400">Cursos de la Escuela</h2>
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead>
                            <tr>
                                <th class="text-orange-400">Nombre del Curso</th>
                                <th class="text-orange-400">Profesores</th>
                                <th class="text-orange-400">Número de Clientes</th>
                                <th class="text-orange-400">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cursos as $curso): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($curso['curso_nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($curso['profesores']); ?></td>
                                    <td><?php echo $curso['num_clientes']; ?></td>
                                    <td>
                                        <button class="btn btn-sm bg-blue-500 hover:bg-blue-600 text-white" onclick="document.getElementById('modal-editar-curso-<?php echo $curso['curso_id']; ?>').showModal()">Editar</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar información de la escuela -->
    <dialog id="modal-editar-escuela" class="modal">
        <form method="POST" class="modal-box bg-gray-900">
            <h3 class="font-bold text-lg text-orange-400 mb-4">Editar Información de la Escuela</h3>
            <input type="hidden" name="actualizar_escuela" value="1">
            <div class="form-control">
                <label class="label">
                    <span class="label-text text-white">Nombre</span>
                </label>
                <input type="text" name="nombre" value="<?php echo htmlspecialchars($escuela['escuela_nombre']); ?>" class="input input-bordered bg-gray-800 text-white" required>
            </div>
            <div class="form-control">
                <label class="label">
                    <span class="label-text text-white">Descripción</span>
                </label>
                <textarea name="descripcion" class="textarea textarea-bordered bg-gray-800 text-white" required><?php echo htmlspecialchars($escuela['escuela_descripcion']); ?></textarea>
            </div>
            <div class="form-control">
                <label class="label">
                    <span class="label-text text-white">Teléfono</span>
                </label>
                <input type="tel" name="telefono" value="<?php echo htmlspecialchars($escuela['escuela_telefono']); ?>" class="input input-bordered bg-gray-800 text-white" required>
            </div>
            <div class="form-control">
                <label class="label">
                    <span class="label-text text-white">Dirección</span>
                </label>
                <input type="text" name="direccion" value="<?php echo htmlspecialchars($escuela['escuela_direccion']); ?>" class="input input-bordered bg-gray-800 text-white" required>
            </div>
            <div class="form-control">
                <label class="label">
                    <span class="label-text text-white">Correo</span>
                </label>
                <input type="email" name="correo" value="<?php echo htmlspecialchars($escuela['escuela_correo']); ?>" class="input input-bordered bg-gray-800 text-white" required>
            </div>
            <div class="modal-action">
                <button type="submit" class="btn bg-orange-400 hover:bg-orange-500 text-white">Guardar Cambios</button>
                <button type="button" class="btn bg-gray-600 hover:bg-gray-700 text-white" onclick="this.closest('dialog').close()">Cancelar</button>
            </div>
        </form>
    </dialog>

    <!-- Modales para editar cursos -->
    <?php foreach ($cursos as $curso): ?>
        <dialog id="modal-editar-curso-<?php echo $curso['curso_id']; ?>" class="modal">
            <form method="POST" class="modal-box bg-gray-900">
                <h3 class="font-bold text-lg text-orange-400 mb-4">Editar Curso</h3>
                <input type="hidden" name="actualizar_curso" value="1">
                <input type="hidden" name="curso_id" value="<?php echo $curso['curso_id']; ?>">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text text-white">Nombre del Curso</span>
                    </label>
                    <input type="text" name="curso_nombre" value="<?php echo htmlspecialchars($curso['curso_nombre']); ?>" class="input input-bordered bg-gray-800 text-white" required>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text text-white">Descripción</span>
                    </label>
                    <textarea name="curso_descripcion" class="textarea textarea-bordered bg-gray-800 text-white" required><?php echo htmlspecialchars($curso['curso_descripcion']); ?></textarea>
                </div>
                    <div class="form-control">
                    <label class="label">
                        <span class="label-text text-white">Precio</span>
                    </label>
                    <input type="number" name="curso_precio" value="<?php echo htmlspecialchars($curso['curso_precio']); ?>" class="input input-bordered bg-gray-800 text-white" required>
                </div>
                <div class="modal-action">
                    <button type="submit" class="btn bg-orange-400 hover:bg-orange-500 text-white">Guardar Cambios</button>
                    <button type="button" class="btn bg-gray-600 hover:bg-gray-700 text-white" onclick="this.closest('dialog').close()">Cancelar</button>
                </div>
            </form>
        </dialog>
    <?php endforeach; ?>


</body>
</html>