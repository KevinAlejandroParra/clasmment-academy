<?php
require '../../../PUBLIC/Config/conexion.php';

// Obtener el ID del curso de la URL
$curso_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// obtener los detalles del curso
$curso_details_query = "SELECT c.*, e.escuela_nombre, e.escuela_direccion, e.escuela_telefono,
                               u.usuario_nombre, u.usuario_apellido, u.usuario_imagen_url
                        FROM cursos c
                        INNER JOIN escuelas e ON c.escuela_id = e.escuela_id
                        LEFT JOIN profesores_cursos pc ON c.curso_id = pc.curso_id
                        LEFT JOIN usuarios u ON pc.usuario_documento = u.usuario_documento
                        WHERE c.curso_id = :curso_id";

    $stmt = $conn->prepare($curso_details_query);
    $stmt->bindValue(':curso_id', $curso_id, PDO::PARAM_INT);
    $stmt->execute();
    $curso_details = $stmt->fetch(PDO::FETCH_ASSOC);

// obtener los horarios del curso
    $horarios_query = "SELECT * FROM horarios WHERE curso_id = :curso_id";
    $stmt = $conn->prepare($horarios_query);
    $stmt->bindValue(':curso_id', $curso_id, PDO::PARAM_INT);
    $stmt->execute();
    $horarios_details = $stmt->fetchAll(PDO::FETCH_ASSOC);

//  obtener el número de estudiantes matriculados
    $sql = "SELECT COUNT(*) AS total FROM matriculas WHERE curso_id = :curso_id AND matricula_estado = 'activo'";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':curso_id', $curso_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $estudiantes_matricula = $result['total'];


try {
    $curso = $curso_details;
    if (!$curso) {
        throw new Exception("Curso no encontrado");
    }
    $horarios = $horarios_details;
    $estudiantes_matriculados = $estudiantes_matricula;
} catch (Exception $e) {
    // Manejar el error, por ejemplo, redirigir a la página principal
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($curso['curso_nombre']); ?> | Classment Academy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@3.9.4/dist/full.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="../IMG/logo.png" type="image/x-icon">
</head>
<body class="bg-black font-sans relative min-h-screen overflow-y-auto">
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
            <li><a href="index.php">Inicio</a></li>
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


    <main class="container mx-auto mt-8 px-4 max-w-6xl shadow-lg shadow-orange-500/50">
        <div class="card bg-black shadow-xl">
            <figure class="relative h-64 sm:h-80 md:h-96">
                <img src="<?php echo htmlspecialchars($curso['curso_imagen_url']); ?>" alt="<?php echo htmlspecialchars($curso['curso_nombre']); ?>" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                    <h1 class="text-orange-400 text-3xl md:text-4xl font-bold text-center px-4"><?php echo htmlspecialchars($curso['curso_nombre']); ?></h1>
                </div>
            </figure>
            
            <div class="card-body">
                <div class="flex flex-wrap justify-between items-center mb-6">
                    <div class="w-full sm:w-auto mb-4 sm:mb-0">
                        <p class="text-white"><i class="fas fa-school mr-2 text-orange-400"></i><?php echo htmlspecialchars($curso['escuela_nombre']); ?></p>
                        <p class="text-white"><i class="fas fa-user-tie mr-2 text-orange-400"></i>Prof. <?php echo htmlspecialchars($curso['usuario_nombre'] . ' ' . $curso['usuario_apellido']); ?></p>
                    </div>
                    <div class="w-full sm:w-auto flex justify-between sm:justify-end items-center space-x-4">
                        <p class="text-2xl font-bold text-white">$<?php echo number_format($curso['curso_precio'], 2); ?></p>
                        <button class="btn  text-white bg-orange-400 hover:bg-orange-500">
                            <i class="fas fa-user-plus mr-2 text-white"></i>Inscribirse
                        </button>
                    </div>
                </div>
                
                <div class="divider"></div>
                
                <h2 class="text-2xl font-semibold mb-4 text-orange-400">Descripción del Curso</h2>
                <p class="text-white mb-6"><?php echo nl2br(htmlspecialchars($curso['curso_descripcion'])); ?></p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-xl font-semibold mb-3 text-orange-400">Detalles del Curso</h3>
                        <ul class="space-y-2">
                            <li><i class="fas fa-calendar-alt mr-2 text-orange-400"></i>Inicio: <?php echo date('d/m/Y', strtotime($curso['curso_fecha_inicio'])); ?></li>
                            <li><i class="fas fa-calendar-check mr-2 text-orange-400"></i>Fin: <?php echo date('d/m/Y', strtotime($curso['curso_fecha_fin'])); ?></li>
                            <li><i class="fas fa-users mr-2 text-orange-400"></i>Cupos: <?php echo $estudiantes_matriculados; ?>/<?php echo $curso['curso_capacidad_maxima']; ?></li>
                            <li><i class="fas fa-child mr-2 text-orange-400"></i>Edad mínima: <?php echo $curso['curso_edad_minima']; ?> años</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold mb-3 text-orange-400">Ubicación</h3>
                        <p class="text-white"><i class="fas fa-map-marker-alt mr-2 text-orange-400"></i><?php echo htmlspecialchars($curso['curso_direccion']); ?></p>
                        <p class="text-white mt-2"><i class="fas fa-phone mr-2 text-orange-400"></i><?php echo htmlspecialchars($curso['escuela_telefono']); ?></p>
                    </div>
                </div>
                
                <div class="mt-8">
                    <h3 class="text-xl font-semibold mb-4 text-orange-400">Horarios</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        <?php foreach ($horarios as $horario): ?>
                            <div class="bg-base-200 p-3 rounded-lg">
                                <p class="font-semibold text-orange-400"><?php echo $horario['horario_dia_semana']; ?></p>
                                <p class="text-white"><?php echo date('H:i', strtotime($horario['horario_hora_inicio'])); ?> - <?php echo date('H:i', strtotime($horario['horario_hora_fin'])); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="text-xl font-semibold mb-4 text-orange-400">Profesor</h3>
                    <div class="flex items-center space-x-4">
                        <div class="avatar">
                            <div class="w-16 rounded-full">
                                <img src="<?php echo htmlspecialchars($curso['usuario_imagen_url']); ?>" alt="<?php echo htmlspecialchars($curso['usuario_nombre'] . ' ' . $curso['usuario_apellido']); ?>">
                            </div>
                        </div>
                        <div>
                            <p class="font-semibold text-orange-400"><?php echo htmlspecialchars($curso['usuario_nombre'] . ' ' . $curso['usuario_apellido']); ?></p>
                            <p class="text-white">Profesor de <?php echo htmlspecialchars($curso['curso_nombre']); ?></p>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="text-xl font-semibold mb-4 text-orange-400">Preguntas Frecuentes</h3>
                    <div class="space-y-4">
                        <div class="collapse collapse-plus bg-base-200">
                            <input type="radio" name="my-accordion-3" checked="checked" /> 
                            <div class="collapse-title text-xl font-medium">
                                ¿Necesito conocimientos previos?
                            </div>
                            <div class="collapse-content"> 
                                <p class="text-white">Este curso está diseñado para todos los niveles. No se requieren conocimientos previos específicos.</p>
                            </div>
                        </div>
                        <div class="collapse collapse-plus bg-base-200">
                            <input type="radio" name="my-accordion-3" /> 
                            <div class="collapse-title text-xl font-medium">
                                ¿Qué materiales necesito?
                            </div>
                            <div class="collapse-content"> 
                                <p class="text-white">La lista de materiales se proporcionará al inicio del curso. Algunos materiales básicos pueden ser necesarios desde la primera clase.</p>
                            </div>
                        </div>
                        <div class="collapse collapse-plus bg-base-200">
                            <input type="radio" name="my-accordion-3" /> 
                            <div class="collapse-title text-xl font-medium">
                                ¿Hay certificado al finalizar?
                            </div>
                            <div class="collapse-content"> 
                                <p class="text-white">Sí, al completar satisfactoriamente el curso, recibirás un certificado de Classment Academy.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
</div>
</main>
            <!-- Footer -->
            <footer class="bg-ghost text-zinc-50 p-2 pt-4">
                <div class="w-full max-w-[1200px] mx-auto flex flex-col gap-8 items-center justify-center py-6 text-center">
                    <div class="w-full flex flex-col md:flex-row justify-between items-center">
                        <div class="footer-contact">
                            <h3 class="font-bold text-xl tracking-tight">Contacto</h3>
                            <p>Teléfono: +57 321 2376552</p>
                            <p>Correo: classmentacademy@gmail.com</p>
                        </div>
                    
                        <div class="footer-links">
                            <h3 class="font-bold text-xl tracking-tight">Legal</h3>
                            <ul>
                                <li><a href="#">Política de privacidad</a></li>
                                <li><a href="#">Términos de uso</a></li>
                            </ul>
                        </div>
                        <div class="flex flex-col justify-center items-center space-y-4">
                            <h3 class="font-bold text-xl tracking-tight">Unete a nuestra comunidad</h3>
                            <p>Sacale el mayor potencial a tus habilidades!</p>

                        </div>
                    </div>
                    <!-- Copyright -->
                    <div class="bg-zinc-950 w-full p-2 flex items-center justify-center rounded-full font-semibold shadow-lg">
                        <p>Copyright &copy; 2024 classment academy</p>
                    </div>
                </div>
            </footer>
</body>
</html>