        <?php
            session_start(); 
        // IMPORTAR CONEXIÓN
        require "./PHP/conexion.php";

        // EJECUTAR CONSULTA
        $sql = "SELECT e.*, c.*, pc.usuario_documento AS profesor_documento,
                    u.usuario_nombre AS profesor_nombre, u.usuario_apellido AS profesor_apellido
                FROM escuelas e
                INNER JOIN cursos c ON e.escuela_id = c.escuela_id
                LEFT JOIN profesores_cursos pc ON c.curso_id = pc.curso_id
                LEFT JOIN usuarios u ON pc.usuario_documento = u.usuario_documento";
        $result = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

        // AGRUPAR ESCUELAS CON CURSOS
        $escuelas = [];

        foreach ($result as $row) {
            $escuela_id = $row['escuela_id'];
            if (!array_key_exists($escuela_id, $escuelas)) {
                $escuelas[$escuela_id] = array(
                    'escuela_id' => $row['escuela_id'],
                    'escuela_nombre' => $row['escuela_nombre'],
                    'escuela_descripcion' => $row['escuela_descripcion'],
                    'escuela_telefono' => $row['escuela_telefono'],
                    'escuela_direccion' => $row['escuela_direccion'],
                    'escuela_imagen_url' => $row['escuela_imagen_url'],
                    'escuela_fecha_creacion' => $row['escuela_fecha_creacion'],
                    'cursos' => array(),
                );
            }

            $escuelas[$escuela_id]['cursos'][] = array(
                'curso_id' => $row['curso_id'],
                'curso_nombre' => $row['curso_nombre'],
                'curso_descripcion' => $row['curso_descripcion'],
                'curso_precio' => $row['curso_precio'],
                'curso_cupos' => $row['curso_cupos'],
                'curso_capacidad_maxima' => $row['curso_capacidad_maxima'],
                'curso_edad_minima' => $row['curso_edad_minima'],
                'curso_imagen_url' => $row['curso_imagen_url'],
                'curso_fecha_inicio' => $row['curso_fecha_inicio'],
                'curso_fecha_fin' => $row['curso_fecha_fin'],
                'curso_estado' => $row['curso_estado'],
                'curso_direccion' => $row['curso_direccion'],
                'profesor_nombre' => $row['profesor_nombre'] . ' ' . $row['profesor_apellido'],
            );
        }
        ?>

        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Inicio | Classment Academy</title>
            <script src="https://cdn.tailwindcss.com"></script>
            <script src="https://kit.fontawesome.com/eb36e646d1.js" crossorigin="anonymous"></script>    
            <link rel="shortcut icon" href="IMG/logo_mini.png" type="image/x-icon">
        </head>
        <body>
    <header class="w-full bg-gray-100 p-2">
        <div class="w-full max-w-[1200px] flex justify-between items-center mx-auto">
            <!-- Sección de búsqueda -->
            <a href="index.php" class="h-[40px] w-auto">
                <img src="IMG/logo.png" class="object-cover h-full w-full">
            </a>

            <!-- Sección de cuenta -->
            <div>
                <?php if(isset($_SESSION['usuario_documento'])): ?>
                    <a href="perfil_usuario.php" class="flex items-center space-x-2 duration-300 hover:text-yellow-400 hover:scale-[1.05]">
                        <i class="fas fa-user-circle text-2xl"></i>
                        <span>Mi Perfil</span>
                    </a>
                <?php else: ?>
                    <a href="login.php" class="flex items-center space-x-2 duration-300 hover:text-yellow-400 hover:scale-[1.05]">
                        <i class="fas fa-sign-in-alt text-2xl"></i>
                        <span>Ingresa</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>
        
            <main>
                <!-- Sección de inicio -->
                <div class="mx-auto max-w-[900px] px-5 mt-16">
                    <div class="flex items-center justify-between">
                        <div class="w-1/2 mr-8">
                            <p class="font-sans text-5xl font-bold tracking-wide text-yellow-400 mb-6">¿Tiempo libre?</p>
                            <p class="font-sans text-3xl font-bold tracking-wide text-black mb-8">
                                Multiples habilidades, todas reunidas
                                en un solo lugar, busca la que más
                                eleve tu potencial!
                            </p>
                            <div class="w-full max-w-md flex items-center border border-gray-300 rounded-lg overflow-hidden shadow-lg mb-8">
                                <div class="flex items-center px-3 bg-white">
                                    <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                                </div>
                                <input type="text" id="searchInput" placeholder="Ejemplo: Defensa Personal" class="px-3 py-4 w-full outline-none text-gray-700">
                                <button onclick="searchProducts()" class="px-4 py-4 bg-yellow-500 text-white font-semibold">Buscar</button>
                            </div>
                        </div>
                        <div class="w-1/2 ml-16">
                            <img src="IMG/banner.jpg" class="object-cover h-full w-full rounded-lg" alt="Banner">
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN DE CURSOS -->
                <section>
                    <div class="w-full max-w-[1200px] mx-auto my-5 relative">
                        <span class="relative flex justify-center my-10">
                            <div class="absolute inset-x-0 top-1/2 h-px -translate-y-1/2 bg-transparent bg-gradient-to-r from-transparent via-gray-500 to-transparent opacity-75"></div>
                            <span class="relative z-10 bg-white px-6 text-3xl font-bold tracking-tight"> CURSOS </span>
                        </span>
                        <!-- SECCIÓN POR CADA ESCUELA -->
                        <?php foreach ($escuelas as $escuela): ?>
            <div class="my-5">                        
                <h2 class="text-2xl font-bold mb-4"><?= $escuela['escuela_nombre'] ?></h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- CARTA DE CADA CURSO -->
                    <?php foreach ($escuela['cursos'] as $curso): ?>
                        <article class="bg-white rounded-lg shadow-lg overflow-hidden">
                            <div class="w-full h-48">
                                <img src="<?= $curso['curso_imagen_url'] ?>" alt="<?= $curso['curso_nombre'] ?>" class="object-cover h-full w-full">
                            </div>
                            <div class="p-4">
                                <h3 class="text-xl font-semibold"><?= $curso['curso_nombre'] ?></h3>
                                <p class="text-gray-700"><?= $curso['curso_descripcion'] ?></p>
                                <p class="text-sm text-gray-600 mt-2">Profesor: <?= $curso['profesor_nombre'] ?></p>
                                <p class="text-sm text-gray-600">Precio: $<?= number_format($curso['curso_precio'], 2) ?></p>
                                <p class="text-sm text-gray-600">Cupos disponibles: <?= $curso['curso_cupos'] ?>/<?= $curso['curso_capacidad_maxima'] ?></p>
                                <p class="text-sm text-gray-600">Fecha de inicio: <?= date('d/m/Y', strtotime($curso['curso_fecha_inicio'])) ?></p>
                                <a href="curso.php?id=<?= $curso['curso_id'] ?>">
                                    <button class="mt-2 bg-yellow-700 text-white px-4 py-2 rounded hover:bg-blue-800 transition duration-300">
                                        Ver
                                    </button>
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
                    </div>
                </section>
            </main>
            <section id="sobre-nosotros" class="bg-white py-16">
    <div class="w-full max-w-[1200px] mx-auto px-4">
        <h2 class="text-4xl font-bold text-center mb-12 text-yellow-400">Sobre Nosotros</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h3 class="text-2xl font-semibold mb-4 text-yellow-600">Objetivo General</h3>
                <p class="text-gray-700 mb-6">Desarrollar un sistema de información para las instituciones ubicadas en las localidades de Bosa y Ciudad Bolívar, ofreciendo una mejor gestión de horarios, inscripciones y registro de operaciones.</p>
                <div class="flex items-center justify-center">
                    <i class="fas fa-bullseye text-5xl text-yellow-500"></i>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h3 class="text-2xl font-semibold mb-4 text-yellow-600">Justificación</h3>
                <ul class="text-gray-700 space-y-4">
                    <li><i class="fas fa-check-circle text-green-500 mr-2"></i>Brindamos visibilidad a escuelas pequeñas, generando equidad y nuevas oportunidades.</li>
                    <li><i class="fas fa-brain text-blue-500 mr-2"></i>Fomentamos el desarrollo de habilidades sociales, emocionales y cognitivas.</li>
                    <li><i class="fas fa-users text-purple-500 mr-2"></i>Facilitamos la interacción entre comunidades para descubrir actividades y colaborar.</li>
                </ul>
            </div>
        </div>
        
        <div class="mt-12">
            <h3 class="text-2xl font-semibold mb-6 text-center text-yellow-600">Creadores del Proyecto</h3>
            <div class="flex flex-wrap justify-center gap-8">
                <div class="bg-white rounded-lg shadow-lg p-6 text-center w-64">
                    <img src="IMG/nosotros.png" alt="Daniel Josué Peña Puentes" class="rounded-full mx-auto mb-4">
                    <h4 class="font-semibold text-lg">Daniel Josué Peña Puentes</h4>
                    <p class="text-gray-600">Desarrollador</p>
                </div>
                <div class="bg-white rounded-lg shadow-lg p-6 text-center w-64">
                    <img src="IMG/nosotros.png" alt="Kevin Alejandro Parra Cifuentes" class="rounded-full mx-auto mb-4">
                    <h4 class="font-semibold text-lg">Kevin Alejandro Parra Cifuentes</h4>
                    <p class="text-gray-600">Desarrollador</p>
                </div>
                <div class="bg-white rounded-lg shadow-lg p-6 text-center w-64">
                    <img src="IMG/nosotros.png" alt="Daniel Sneither Bernal Gómez" class="rounded-full mx-auto mb-4">
                    <h4 class="font-semibold text-lg">Daniel Sneither Bernal Gómez</h4>
                    <p class="text-gray-600">Desarrollador</p>
                </div>
            </div>
        </div>
    </div>
</section>
            <!-- Footer -->
            <footer class="bg-zinc-800 text-zinc-50 p-2">
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
        </body>
        </html>
    </div>