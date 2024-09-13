<?php
        session_start(); 
        require "./PHP/conexion.php";

        $stmt = $conn->prepare("
        SELECT c.curso_id, c.curso_nombre, c.curso_descripcion, c.curso_imagen_url, c.curso_precio, 
               e.escuela_nombre, e.escuela_id
        FROM cursos c
        INNER JOIN escuelas e ON c.escuela_id = e.escuela_id
        WHERE c.curso_estado = 'activo'
        LIMIT 5
    ");
    $stmt->execute();
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>        
<!DOCTYPE html>
<html data-theme="black" lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <link rel="shortcut icon" href="../IMG/logo.png" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <  <style>
    .gradient-circle {
      position: absolute;
      border-radius: 50%;
      filter: blur(50px);
    }

    .hero-section {
      min-height: 100vh;
      padding-top: 80px; /* Ajustado para dar espacio al navbar fijo */
    }

    .navbar {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 50;
    }

    body {
      min-height: 100vh;
      overflow-y: auto; /* Permite el scroll vertical */
    }

    main {
      position: relative;
      z-index: 10;
    }
  </style>
</head>

<body class="bg-black relative">
  <!-- Navbar -->
  <div class="navbar bg-transparent py-4"> <!-- Navbar transparente -->
  <div class="navbar-start flex justify-center">
    <div class="dropdown">
      <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-5 w-5"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor">
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M4 6h16M4 12h8m-8 6h16" />
        </svg>
      </div>
      <ul
        tabindex="0"
        class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
        <li><a>Inicio</a></li>
        <li>
          <a>Servicios</a>
          <ul class="p-2">
            <li><a>Escuelas</a></li>
            <li><a>Cursos</a></li>
          </ul>
        </li>
        <li><a>Nosotros</a></li>
      </ul>
    </div>
    <a href="index.php" 
    class="btn btn-ghost ">
      <img src="../IMG/logo.png" alt="logo" 
      class="w-f h-full" data-aos="zoom-in">
      <h1 class="text-2xl font-bold" data-aos="zoom-in">Classment Academy</h1>
    </a>
  </div>
  <div class="navbar-center hidden lg:flex" data-aos="zoom-in">
    <ul class="menu menu-horizontal px-1" data-aos="zoom-in">
      <li><a>Inicio</a></li>
      <li>
        <details>
          <summary>Servicios</summary>
          <ul class="p-2">
            <li><a>Escuelas</a></li>
            <li><a>Cursos</a></li>
          </ul>
        </details>
      </li>
      <li><a>Nosotros</a></li>
    </ul>
  </div>
  <div class="navbar-end">
    <a class="btn-ghost ">
    <?php if(isset($_SESSION['usuario_documento'])): ?>
        <a href="perfil_usuario.php" class="flex items-center space-x-2 text-xl pr-32" data-aos="zoom-in">
          <span>Mi Perfil</span>
          <i class="fas fa-user-circle text-3xl"></i>
        </a>
        <?php else: ?>
        <a href="login.php" class="flex items-center space-x-2 text-xl pr-32" data-aos="zoom-in">
          <span>Ingresa</span>
          <i class="fas fa-sign-in-alt text-3xl"></i>
        </a>
        <?php endif; ?>
    </a>
    

  </div>
</div>
      
      </div>
    </div>
  </div>
<main>
  <!-- Sección Hero -->
  <section class="hero-section flex items-center justify-center relative pl-24" data-aos="fade-up">
    <!-- Círculos de gradiente -->
    <div class="gradient-circle w-32 h-32 bg-orange-300 top-10 left-10 opacity-50"></div>
    <div class="gradient-circle w-64 h-64 bg-orange-400 top-10 right-10 opacity-30"></div>
    <div class="gradient-circle w-64 h-64 bg-orange-300 bottom-10 left-10 opacity-30"></div>
    <div class="gradient-circle w-64 h-64 bg-orange-300 bottom-10 right-1/4 opacity-30"></div>

    <div class="hero-content flex-col lg:flex-row-reverse pr-48">
    <img
      src="../IMG/design/fisoculturista.png"
      class="max-w-sm pr-10 pl-24" data-aos="zoom-in" />
      <div>
      <h1 class="text-1xl font-bold tracking-wide leading-normal text-orange-300" data-aos="zoom-in">Tu mejor aliado para aprender</h1>
      <h1 class="text-6xl tracking-wide leading-snug font-extrabold" data-aos="zoom-in">NADA ES IMPOSIBLE SI NO LO INTENTAS
      </h1>
      <p class="py-6 tracking-wide" data-aos="zoom-in">
        Sacale el mayor provecho a tu tiempo y tus capacidades fisicas, aprende y mejora
         tus habilidades blandas, elimina las excusas y las distracciones.

      </p>
      <a href="login.php"><button class="btn rounded-lg bg-orange-400"data-aos="zoom-in">Empieza ahora!</button></a>
    </div>
  </div>
</div>
  </section>
     <!-- Course Carousel Section -->
     
     <div class="carousel w-full" data-aos="fade-up">
  <?php foreach ($courses as $index => $course): ?>
    <div id="course<?= $index ?>" class="carousel-item relative w-full flex justify-center items-center">
      <div class="card w-98 bg-base-100 shadow-xl">
        <figure><img src="<?= htmlspecialchars($course['curso_imagen_url']) ?>" alt="<?= htmlspecialchars($course['curso_nombre']) ?>" class="w-full h-48 object-cover" /></figure>
        <div class="card-body">
          <h2 class="card-title text-orange-400"><?= htmlspecialchars($course['curso_nombre']) ?></h2>
          <p><?= htmlspecialchars(substr($course['curso_descripcion'], 0, 100)) ?>...</p>
          <p class="text-sm">Escuela: <?= htmlspecialchars($course['escuela_nombre']) ?></p>
          <p class="text-lg font-bold">$<?= number_format($course['curso_precio'], 2) ?></p>
          <div class="card-actions justify-end">
            <a href="curso_detalle.php?id=<?= $course['curso_id'] ?>" class="btn btn-primary bg-orange-400 hover:bg-orange-500 border-none">Ver Curso</a>
          </div>
        </div>
      </div>
      <div class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">
        <a href="#course<?= ($index - 1 + count($courses)) % count($courses) ?>" class="btn btn-circle bg-orange-400 hover:bg-orange-500 border-none">❮</a> 
        <a href="#course<?= ($index + 1) % count($courses) ?>" class="btn btn-circle bg-orange-400 hover:bg-orange-500 border-none">❯</a>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<script>
  function autoAdvance() {
    const slides = document.querySelectorAll('.carousel-item');
    const totalSlides = slides.length;
    let currentSlide = parseInt(window.location.hash.replace('#course', '')) || 0;
    let nextSlide = (currentSlide + 1) % totalSlides;
    window.location.hash = `#course${nextSlide}`;
  }

  // Iniciar el auto-avance cuando la página esté completamente cargada
  window.addEventListener('load', function() {
    setInterval(autoAdvance, 5000);
  });

  // Detener el auto-avance cuando el usuario interactúe con el carrusel
  document.querySelector('.carousel').addEventListener('click', function() {
    clearInterval(autoAdvanceInterval);
  });

  // Reiniciar el auto-avance después de 10 segundos de inactividad
  let autoAdvanceInterval;
  function restartAutoAdvance() {
    clearInterval(autoAdvanceInterval);
    autoAdvanceInterval = setInterval(autoAdvance, 5000);
  }

  document.querySelector('.carousel').addEventListener('mouseleave', restartAutoAdvance);
  document.addEventListener('touchend', restartAutoAdvance);
</script>


  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init({
      duration: 1000
    });
  </script>
</body>

</html>

