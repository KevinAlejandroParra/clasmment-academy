<?php
session_start();
require "../../../PUBLIC/Config/conexion.php";

// Verificar si se ha proporcionado un ID de escuela
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de escuela no válido");
}

$escuela_id = $_GET['id'];

try {
    // Preparar la consulta para obtener los detalles de la escuela
    $stmt = $conn->prepare("
        SELECT escuela_id, escuela_nombre, escuela_descripcion, escuela_telefono, 
               escuela_direccion, escuela_correo, escuela_imagen_url
        FROM escuelas
        WHERE escuela_id = :id AND escuela_estado = 'activo'
    ");
    $stmt->bindParam(':id', $escuela_id, PDO::PARAM_INT);
    $stmt->execute();
    $escuela = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$escuela) {
        die("Escuela no encontrada");
    }

    // Preparar la consulta para obtener los cursos de la escuela
    $stmt = $conn->prepare("
        SELECT curso_id, curso_nombre, curso_descripcion, curso_precio, curso_imagen_url
        FROM cursos
        WHERE escuela_id = :id AND curso_estado = 'activo'
    ");
    $stmt->bindParam(':id', $escuela_id, PDO::PARAM_INT);
    $stmt->execute();
    $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    die("Error en la consulta: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html data-theme="black" lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($escuela['escuela_nombre']) ?> - Detalles</title>
    <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <link rel="shortcut icon" href="../IMG/logo.png" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
          <a href="../../../PUBLIC/index.php" class="btn btn-ghost">
        <img src="../../../PUBLIC/IMG/logo.png" alt="logo" class="w-f h-full" data-aos="zoom-in">
            <h1 class="text-4xl font-bold" data-aos="zoom-in">Classment Academy</h1>
          </a>
        </div>
        <div class="navbar-end hidden lg:flex pl-10pr-32" data-aos="zoom-in">
          <ul class="menu menu-horizontal px-1" data-aos="zoom-in">
            <li><a href="../../../PUBLIC/index.php">Inicio</a></li>
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

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold mb-6" data-aos="fade-up"><?= htmlspecialchars($escuela['escuela_nombre']) ?></h1>
        
        <div class="grid md:grid-cols-2 gap-8 mb-8">
            <div data-aos="fade-right">
                <img src="<?= htmlspecialchars($escuela['escuela_imagen_url']) ?>" alt="<?= htmlspecialchars($escuela['escuela_nombre']) ?>" class="w-full h-64 object-cover rounded-lg shadow-lg">
            </div>
            <div data-aos="fade-left">
                <h2 class="text-2xl font-semibold mb-4">Detalles de la Escuela</h2>
                <p class="mb-2"><strong>Descripción:</strong> <?= nl2br(htmlspecialchars($escuela['escuela_descripcion'])) ?></p>
                <p class="mb-2"><strong>Teléfono:</strong> <?= htmlspecialchars($escuela['escuela_telefono']) ?></p>
                <p class="mb-2"><strong>Dirección:</strong> <?= htmlspecialchars($escuela['escuela_direccion']) ?></p>
                <p class="mb-2"><strong>Correo:</strong> <?= htmlspecialchars($escuela['escuela_correo']) ?></p>
            </div>
        </div>

        <h2 class="text-3xl font-bold mb-6" data-aos="fade-up">Cursos Ofrecidos</h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($cursos as $curso): ?>
                <div class="card bg-base-100 shadow-xl" data-aos="zoom-in">
                    <figure><img src="<?= htmlspecialchars($curso['curso_imagen_url']) ?>" alt="<?= htmlspecialchars($curso['curso_nombre']) ?>" class="w-full h-48 object-cover" /></figure>
                    <div class="card-body">
                        <h2 class="card-title"><?= htmlspecialchars($curso['curso_nombre']) ?></h2>
                        <p><?= htmlspecialchars(substr($curso['curso_descripcion'], 0, 100)) ?>...</p>
                        <p class="text-lg font-bold">$<?= number_format($curso['curso_precio'], 2) ?></p>
                        <div class="card-actions justify-end">
                            <a href="curso_detalle.php?id=<?= $curso['curso_id'] ?>" class="btn text-white bg-orange-400 hover:bg-orange-500">Ver Detalles</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>
</html>