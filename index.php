<?php

// IMPORTAR CONEXIÓN
require "./PHP/conexion.php";


// EJECUTAR CONSULTA
$sql = "SELECT * FROM `escuelas` INNER JOIN `cursos` ON escuelas.escuela_id = cursos.escuela_id";
$result = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

// AGRUPAR ESCUELAS CON CURSOS
$escuelas = [];

foreach ( $result as $escuela) {
    $escuela_id = $escuela['escuela_id'];
    if (!array_key_exists($escuela_id, $escuelas)) {
        $escuelas[$escuela_id] = array(
            'escuela_id' => $escuela['escuela_id'],
            'escuela_nombre' => $escuela['escuela_nombre'],
            'escuela_descripcion' => $escuela['escuela_descripcion'],
            'escuela_numero' => $escuela['escuela_numero'],
            'escuela_ubicacion' => $escuela['escuela_ubicacion'],
            'escuela_imagen_url' => $escuela['escuela_imagen_url'],
            'cursos' => array(),
        );
    }

    $escuelas[$escuela_id]['cursos'][] = array(
        'curso_id' => $escuela['curso_id'],
        'curso_nombre' => $escuela['curso_nombre'],
        'curso_descripcion' => $escuela['curso_descripcion'],
        'curso_precio' => $escuela['curso_precio'],
        'curso_profesor' => $escuela['curso_profesor'],
        'curso_imagen_url' => $escuela['curso_imagen_url'],
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
    <link rel="shortcut icon" href="IMG/logo.png" type="image/x-icon">
</head>
<body>
    <header class="w-full bg-gray-100 p-2">
        <div class="w-full max-w-[1200px] flex justify-between items-center mx-auto">

            <!-- Sección de búsqueda -->
            <a href="login.php" class="h-[40px] w-auto">
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

        <!-- AQUÍ DEBE IR LA PARTE DE CURSOS -->
        <section>
            <div class="w-full max-w-[1200px] mx-auto my-5 relative">
                <span class="relative flex justify-center my-10">
                    <div class="absolute inset-x-0 top-1/2 h-px -translate-y-1/2 bg-transparent bg-gradient-to-r from-transparent via-gray-500 to-transparent opacity-75" ></div>
                    <span class="relative z-10 bg-white px-6 text-3xl font-bold tracking-tight"> CURSOS </span>
                </span>
                <!-- SECCIÓN POR CADA ESCUELA -->
                <?php foreach ( $escuelas as $escuela ): ?>
                    <div class="my-5">                        
                        <h2 class="text-2xl font-bold mb-4 "> <?= $escuela['escuela_nombre'] ?> </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- CARTA DE CADA CURSO -->
                            <?php foreach ($escuela['cursos'] as $curso ): ?>
                                <article class="bg-white rounded-lg shadow-lg overflow-hidden">
                                    <div class="w-full h-48">
                                        <img src="<?= $curso['curso_imagen_url'] ?>" alt="<?= $curso['curso_nombre'] ?>" class="object-cover h-full w-full">
                                    </div>
                                    <div class="p-4">
                                        <h3 class="text-xl font-semibold"> <?= $curso['curso_nombre'] ?> </h3>
                                        <p class="text-gray-700"><?= $curso['curso_descripcion'] ?></p>
                                        <a href="curso.php?id=<?= $curso['curso_id'] ?>">
                                            <button class="mt-2 bg-yellow-700 text-white px-4 py-2 rounded hover:bg-blue-800 transition duration-300">
                                                Adquirir
                                            </button>
                                        </a>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php  endforeach; ?>
            </div>
        </section>
    </main>

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
