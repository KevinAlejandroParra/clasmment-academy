<?php
require "./PHP/conexion.php";

// Obtener el ID del curso de la URL
$curso_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Consulta para obtener los detalles del curso
$sql = "SELECT c.*, e.escuela_nombre, e.escuela_direccion, e.escuela_telefono,
               u.usuario_nombre, u.usuario_apellido, u.usuario_imagen_url
        FROM cursos c
        INNER JOIN escuelas e ON c.escuela_id = e.escuela_id
        LEFT JOIN profesores_cursos pc ON c.curso_id = pc.curso_id
        LEFT JOIN usuarios u ON pc.usuario_documento = u.usuario_documento
        WHERE c.curso_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $curso_id);
$stmt->execute();
$result = $stmt->get_result();
$curso = $result->fetch_assoc();

// Si no se encuentra el curso, redirigir a la página principal
if (!$curso) {
    header("Location: index.php");
    exit();
}

// Consulta para obtener los horarios del curso
$sql_horarios = "SELECT * FROM horarios WHERE curso_id = ?";
$stmt_horarios = $conn->prepare($sql_horarios);
$stmt_horarios->bind_param("i", $curso_id);
$stmt_horarios->execute();
$result_horarios = $stmt_horarios->get_result();
$horarios = $result_horarios->fetch_all(MYSQLI_ASSOC);

// Función para obtener el número de estudiantes matriculados
function getEstudiantesMatriculados($conn, $curso_id) {
    $sql = "SELECT COUNT(*) as total FROM matriculas WHERE curso_id = ? AND matricula_estado = 'activo'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $curso_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['total'];
}

$estudiantes_matriculados = getEstudiantesMatriculados($conn, $curso_id);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($curso['curso_nombre']); ?> | Classment Academy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/eb36e646d1.js" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="IMG/logo.png" type="image/x-icon">
</head>
<body class="bg-gray-100 font-sans">
    <!-- header -->
    <header class="w-full bg-gray-100 p-2">
        <div class="w-full max-w-[1200px] flex justify-between items-center mx-auto">
            <!-- Sección de búsqueda -->
            <a href="index.php" class="h-[40px] w-auto">
                <img src="IMG/logo.png" class="object-cover h-full w-full ">
            </a>

            <!-- Sección de cuenta -->
            <div>
                <a href="login.php">
                    <p class="duration-300 hover:text-yellow-400 hover:scale-[1.05]">
                        <i class="fa-solid fa-right-to-bracket mr-2"></i>
                        Ingresa
                    </p>
                </a>
            </div>
        </div>
    </header>

    <main class="container mx-auto mt-8 px-4 max-w-6xl">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="relative h-64 sm:h-80 md:h-96">
                <img src="<?php echo htmlspecialchars($curso['curso_imagen_url']); ?>" alt="<?php echo htmlspecialchars($curso['curso_nombre']); ?>" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                    <h1 class="text-white text-3xl md:text-4xl font-bold text-center px-4"><?php echo htmlspecialchars($curso['curso_nombre']); ?></h1>
                </div>
            </div>
            
            <div class="p-6">
                <div class="flex flex-wrap justify-between items-center mb-6">
                    <div class="w-full sm:w-auto mb-4 sm:mb-0">
                        <p class="text-gray-600"><i class="fas fa-school mr-2"></i><?php echo htmlspecialchars($curso['escuela_nombre']); ?></p>
                        <p class="text-gray-600"><i class="fas fa-user-tie mr-2"></i>Prof. <?php echo htmlspecialchars($curso['usuario_nombre'] . ' ' . $curso['usuario_apellido']); ?></p>
                    </div>
                    <div class="w-full sm:w-auto flex justify-between sm:justify-end items-center space-x-4">
                        <p class="text-2xl font-bold text-green-600">$<?php echo number_format($curso['curso_precio'], 2); ?></p>
                        <button class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-full transition duration-300">
                            Inscribirse
                        </button>
                    </div>
                </div>
                
                <div class="border-t border-gray-200 pt-6">
                    <h2 class="text-2xl font-semibold mb-4">Descripción del Curso</h2>
                    <p class="text-gray-700 mb-6"><?php echo nl2br(htmlspecialchars($curso['curso_descripcion'])); ?></p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-xl font-semibold mb-3">Detalles del Curso</h3>
                            <ul class="space-y-2">
                                <li><i class="fas fa-calendar-alt mr-2 text-yellow-500"></i>Inicio: <?php echo date('d/m/Y', strtotime($curso['curso_fecha_inicio'])); ?></li>
                                <li><i class="fas fa-calendar-check mr-2 text-yellow-500"></i>Fin: <?php echo date('d/m/Y', strtotime($curso['curso_fecha_fin'])); ?></li>
                                <li><i class="fas fa-users mr-2 text-yellow-500"></i>Cupos: <?php echo $estudiantes_matriculados; ?>/<?php echo $curso['curso_capacidad_maxima']; ?></li>
                                <li><i class="fas fa-child mr-2 text-yellow-500"></i>Edad mínima: <?php echo $curso['curso_edad_minima']; ?> años</li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold mb-3">Ubicación</h3>
                            <p class="text-gray-700"><i class="fas fa-map-marker-alt mr-2 text-yellow-500"></i><?php echo htmlspecialchars($curso['curso_direccion']); ?></p>
                            <p class="text-gray-700 mt-2"><i class="fas fa-phone mr-2 text-yellow-500"></i><?php echo htmlspecialchars($curso['escuela_telefono']); ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8">
                    <h3 class="text-xl font-semibold mb-4">Horarios</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        <?php foreach ($horarios as $horario): ?>
                            <div class="bg-gray-100 p-3 rounded-lg">
                                <p class="font-semibold"><?php echo $horario['horario_dia_semana']; ?></p>
                                <p><?php echo date('H:i', strtotime($horario['horario_hora_inicio'])); ?> - <?php echo date('H:i', strtotime($horario['horario_hora_fin'])); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="text-xl font-semibold mb-4">Profesor</h3>
                    <div class="flex items-center space-x-4">
                        <img src="<?php echo htmlspecialchars($curso['usuario_imagen_url']); ?>" alt="<?php echo htmlspecialchars($curso['usuario_nombre'] . ' ' . $curso['usuario_apellido']); ?>" class="w-16 h-16 rounded-full object-cover">
                        <div>
                            <p class="font-semibold"><?php echo htmlspecialchars($curso['usuario_nombre'] . ' ' . $curso['usuario_apellido']); ?></p>
                            <p class="text-gray-600">Profesor de <?php echo htmlspecialchars($curso['curso_nombre']); ?></p>
                        </div>
                    </div>
                </div>



                <div class="mt-8">
                    <h3 class="text-xl font-semibold mb-4">Preguntas Frecuentes</h3>
                    <div class="space-y-4">
                        <details class="bg-gray-100 p-4 rounded-lg">
                            <summary class="font-semibold cursor-pointer">¿Necesito conocimientos previos?</summary>
                            <p class="mt-2">Este curso está diseñado para todos los niveles. No se requieren conocimientos previos específicos.</p>
                        </details>
                        <details class="bg-gray-100 p-4 rounded-lg">
                            <summary class="font-semibold cursor-pointer">¿Qué materiales necesito?</summary>
                            <p class="mt-2">La lista de materiales se proporcionará al inicio del curso. Algunos materiales básicos pueden ser necesarios desde la primera clase.</p>
                        </details>
                        <details class="bg-gray-100 p-4 rounded-lg">
                            <summary class="font-semibold cursor-pointer">¿Hay certificado al finalizar?</summary>
                            <p class="mt-2">Sí, al completar satisfactoriamente el curso, recibirás un certificado de Classment Academy.</p>
                        </details>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-gray-800 text-white mt-12 py-8">
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
                            <p>Suscríbete para recibir noticias:</p>
                            <form>
                                <input type="email" placeholder="Email" class="px-2 py-1 rounded-md">
                                <button class="font-semibold border-2 border-white rounded-md  px-2 py-1">Enviar</button>
                            </form>
                        </div>
                    </div>
                    <!-- Copyright -->
                    <div class="bg-zinc-950 w-full p-2 flex items-center justify-center rounded-full font-semibold shadow-lg">
                        <p>Copyright &copy; 2024 classment academy</p>
                    </div>
                </div>
    </footer>

    <script>
        // pendiente javascript
    </script>
</body>
</html>