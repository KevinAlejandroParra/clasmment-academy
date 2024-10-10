<?php
require '../../PUBLIC/Config/conexion.php';


$adminExcluir = 4;
$escuelas_query = "SELECT escuela_id, escuela_nombre, escuela_imagen_url, escuela_descripcion, escuela_direccion, escuela_telefono, escuela_correo, escuela_fecha_creacion FROM escuelas WHERE escuela_id <> :id AND escuela_estado = :estado";
$stmt = $conn->prepare($escuelas_query);
$stmt->bindValue(':id', $adminExcluir, PDO::PARAM_INT);
$stmt->bindValue(":estado", "activo", PDO::PARAM_STR);
$stmt->execute();
$schools = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <link rel="shortcut icon" href="../../PUBLIC/Img/design/ca.jpg" type="image/x-icon">

</head>
<body class="bg-black relative min-h-screen overflow-y-auto">
   <!-- Círculos de gradiente -->
   <div class="fixed w-96 h-96 bg-orange-300 top-0 left-0 rounded-full filter blur-[100px] opacity-30 animate-blob"></div>
   <div class="fixed w-96 h-96 bg-orange-400 top-1/4 right-0 rounded-full filter blur-[100px] opacity-20 animate-blob animation-delay-2000"></div>
   <div class="fixed w-96 h-96 bg-orange-300 bottom-0 left-1/4 rounded-full filter blur-[100px] opacity-30 animate-blob animation-delay-4000"></div>
   <div class="fixed w-96 h-96 bg-orange-400 bottom-1/4 right-1/4 rounded-full filter blur-[100px] opacity-20 animate-blob"></div>
     <!-- Barra de navegación -->
     <div class="container mx-auto p-4">
      <div class="navbar bg-transparent flex justify-between">
        <div class="navbar-start">
          <a href="../../PUBLIC/index.php" class="btn btn-ghost">
            <img src="../../PUBLIC/index.php" alt="logo" class="w-f h-full" data-aos="zoom-in">
            <h1 class="text-4xl font-bold" data-aos="zoom-in">Classment Academy</h1>
          </a>
        </div>
        <div class="navbar-end hidden lg:flex pl-10pr-32" data-aos="zoom-in">
          <ul class="menu menu-horizontal px-1" data-aos="zoom-in">
            <li><a href="../../PUBLIC/index.php">Inicio</a></li>
            <li>
              <details>
                <summary>Servicios</summary>
                <ul class="p-2 bg-base-100">
                  <li><a href="../../PUBLIC/index.php#title-schools">Escuelas</a></li>
                  <li><a href="cursos.php">Cursos</a></li>
                </ul>
              </details>
            </li>
            <li><a href="nosotros.php">Nosotros</a></li>
          </ul>
        </div>
      </div>
    </div>
    
    <div class="container mx-auto p-4">
        <h1 class="text-4xl font-bold mb-8 text-center text-orange-400">Escuelas Aliadas</h1>
        <div class="text-center mb-8">
    <a href="../Views/Crud/register_escuela.php" class="btn bg-orange-400 hover:bg-orange-500 text-white">
        <i class="fas fa-plus mr-2"></i>Crear Nueva Escuela
    </a>
</div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($schools as $escuela): ?>
                <div class="card bg-black  shadow-lg shadow-orange-500/50">
                    <figure><img src="<?= htmlspecialchars($escuela['escuela_imagen_url']) ?>" alt="<?= htmlspecialchars($escuela['escuela_nombre']) ?>" class="w-full h-48 object-cover"/></figure>
                    <div class="card-body">
                        <h2 class="card-title text-orange-400"><?= htmlspecialchars($escuela['escuela_nombre']) ?></h2>
                        <p><?= htmlspecialchars(substr($escuela['escuela_descripcion'], 0, 100)) ?>...</p>
                        <div class="card-actions justify-end mt-4">
                         <button class="btn bg-orange-400 text-white"><a href="../Views/Crud/escuela_detalle.php?id=<?= $escuela['escuela_id'] ?>">Entrar</a></button>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>