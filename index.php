<?php
        session_start(); 
        require "./PHP/conexion.php";

        $stmt = $conn->prepare("
        SELECT c.curso_id, c.curso_nombre, c.curso_descripcion, c.curso_imagen_url, c.curso_precio, 
               e.escuela_nombre, e.escuela_id
        FROM cursos c
        INNER JOIN escuelas e ON c.escuela_id = e.escuela_id
        WHERE c.curso_estado = 'activo'");
        $stmt->execute();
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener escuelas y excluir la escuela de administracion (escuela_id = 4)
    $adminExcluir = 4;
    $escuelas_query = "SELECT escuela_id, escuela_nombre, escuela_imagen_url, escuela_descripcion, escuela_direccion, escuela_telefono, escuela_correo FROM escuelas WHERE escuela_id <> :id AND escuela_estado = :estado";
    $stmt = $conn->prepare($escuelas_query);
    $stmt->bindValue(':id', $adminExcluir, PDO::PARAM_INT);
    $stmt->bindValue(":estado", "activo", PDO::PARAM_STR);
    $stmt->execute();
    $schools = $stmt->fetchAll(PDO::FETCH_ASSOC);
        

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

  <style>
    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    .animate-blob {
        animation: blob 7s infinite;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    .animation-delay-4000 {
        animation-delay: 4s;
    }
    .carousel-item {
        transition: opacity 0.5s ease-in-out;
    }

  </style>
</head>

<body class="bg-black relative min-h-screen overflow-y-auto">
   <!-- Círculos de gradiente -->
   <div class="fixed w-96 h-96 bg-orange-300 top-0 left-0 rounded-full filter blur-[100px] opacity-30 animate-blob"></div>
   <div class="fixed w-96 h-96 bg-orange-400 top-1/4 right-0 rounded-full filter blur-[100px] opacity-20 animate-blob animation-delay-2000"></div>
   <div class="fixed w-96 h-96 bg-orange-300 bottom-0 left-1/4 rounded-full filter blur-[100px] opacity-30 animate-blob animation-delay-4000"></div>
   <div class="fixed w-96 h-96 bg-orange-400 bottom-1/4 right-1/4 rounded-full filter blur-[100px] opacity-20 animate-blob"></div>

  <!-- Navbar -->
  <div class="navbar bg-transparent py-4 backdrop-blur-md bg-opacity-50 fixed top-0 left-0 right-0 z-50 shadow-lg">
    <div class="navbar-start flex justify-center">
      <div class="dropdown">
        <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
          </svg>
        </div>
        <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
          <li><a href="index.php">Inicio</a></li>
          <li>
            <a>Servicios</a>
            <ul class="p-2">
              <li><a href="index.php#title-schools">Escuelas</a></li>
              <li><a href="index.php#title-courses">Cursos</a></li>
              </ul>
          </li>
          <li><a href="index.php#nosotros">Nosotros</a></li>
        </ul>
      </div>
      <a href="index.php" class="btn btn-ghost">
        <img src="../IMG/logo.png" alt="logo" class="w-f h-full" data-aos="zoom-in">
        <h1 class="text-4xl font-bold" data-aos="zoom-in">Classment Academy</h1>
      </a>
    </div>
    <div class="navbar-center hidden lg:flex" data-aos="zoom-in">
      <ul class="menu menu-horizontal px-1" data-aos="zoom-in">
      <li><a href="index.php">Inicio</a></li>
      <li>
          <details>
            <summary>Servicios</summary>
            <ul class="p-2">
            <li><a href="index.php#title-schools">Escuelas</a></li>
            <li><a href="index.php#title-courses">Cursos</a></li>
            </ul>
          </details>
        </li>
        <li><a href="index.php#nosotros">Nosotros</a></li>
      </ul>
    </div>
    <div class="navbar-end">
      <a class="btn-ghost">
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

  <main class="pt-24">
  <!-- Sección Hero -->
  <section class="hero-section flex items-center justify-center relative pb-8 pl-36 " id="hero-section" data-aos="fade-up">
    <div class="hero-content flex-col lg:flex-row-reverse pr-48">
    <video
      src="../IMG/design/people.webm"
      class="max-w-sm  pl-32" data-aos="zoom-in" 
      autoplay muted loop
      ></video>
      <div>
      <h1 class="text-2xl font-bold tracking-wide leading-normal text-orange-300" data-aos="zoom-in">Tu mejor aliado para aprender</h1>
      <h1 class="text-6xl tracking-wide leading-snug font-extrabold" data-aos="zoom-in">NADA ES IMPOSIBLE SI NO LO INTENTAS
      </h1>
      <p class="py-6 tracking-wide" data-aos="zoom-in">
        Sacale el mayor provecho a tu tiempo y tus capacidades fisicas, aprende y mejora
         tus habilidades blandas, elimina las excusas y las distracciones.

      </p>
      <div class="flex space-x-4" data-aos="zoom-in">
      <a href="login.php"><button class="btn rounded-lg bg-orange-500 shadow-lg shadow-orange-500/50"data-aos="zoom-in">Empieza ahora!</button></a>
      <a href="login_escuela.php"><button class="btn rounded-lg bg-orange-500 shadow-lg shadow-orange-500/50"data-aos="zoom-in">Soy escuela</button></a>
      <?php if(isset($_SESSION['usuario_documento']) && $_SESSION['usuario_documento'] == '1025531531'): ?>
        <a href="ADMIN/escuelas.php"><button class="btn rounded-lg bg-orange-500 shadow-lg shadow-orange-500/50"data-aos="zoom-in">Gestionar Escuelas</button></a>
      <?php endif; ?>
      </div>

    </div>
  </div>
</div>
  </section>


  <!-- Sección del Carrusel de Cursos -->
  <div class="relative overflow-hidden py-16 bg-ghost" id="title-courses" data-aos="fade-up">
    <div class="container mx-auto px-4">
      <h2 class="text-3xl font-bold text-center text-white mb-8">Cursos Destacados</h2>
      <div class="carousel w-full relative">
        <div class="overflow-hidden">
          <div class="flex transition-transform duration-300 ease-in-out" id="carousel-container-courses">
            <div class="flex" id="carousel-inner-courses">
              <?php foreach ($courses as $index => $course): ?>
                <div class="carousel-item flex-none w-full flex justify-center items-center px-4">
                  <div class="flex space-x-8 items-center">
                    <!-- Curso anterior -->
                    <div class="card w-64 bg-base-100 shadow-xl opacity-50 transform scale-90 transition-all duration-300 rounded-lg">
                      <figure><img src="<?= htmlspecialchars($courses[($index - 1 + count($courses)) % count($courses)]['curso_imagen_url']) ?>" alt="Curso" class="w-full h-32 object-cover rounded-t-lg" /></figure>
                      <div class="card-body p-4">
                        <h3 class="card-title text-sm"><?= htmlspecialchars($courses[($index - 1 + count($courses)) % count($courses)]['curso_nombre']) ?></h3>
                      </div>
                    </div>
                    
                    <!-- Curso actual -->
                    <div class="card w-80 bg-base-100 rounded-lg z-10">
                      <figure><img src="<?= htmlspecialchars($course['curso_imagen_url']) ?>" alt="<?= htmlspecialchars($course['curso_nombre']) ?>" class="w-full h-48 object-cover rounded-t-lg" /></figure>
                      <div class="card-body">
                        <h2 class="card-title text-orange-400"><?= htmlspecialchars($course['curso_nombre']) ?></h2>
                        <p class="text-sm"><?= htmlspecialchars(substr($course['curso_descripcion'], 0, 100)) ?>...</p>
                        <p class="text-xs">Escuela: <?= htmlspecialchars($course['escuela_nombre']) ?></p>
                        <p class="text-lg font-bold">$<?= number_format($course['curso_precio'], 2) ?></p>
                        <div class="card-actions justify-end">
                          <a href="curso_detalle.php?id=<?= $course['curso_id'] ?>" class="btn btn-sm btn-primary bg-orange-400 hover:bg-orange-500 border-none rounded-lg">Ver Curso</a>
                        </div>
                      </div>
                    </div>
                    
                    <!-- Curso siguiente -->
                    <div class="card w-64 bg-base-100 shadow-xl opacity-50 transform scale-90 transition-all duration-300 rounded-lg">
                      <figure><img src="<?= htmlspecialchars($courses[($index + 1) % count($courses)]['curso_imagen_url']) ?>" alt="Curso" class="w-full h-32 object-cover rounded-t-lg" /></figure>
                      <div class="card-body p-4">
                        <h3 class="card-title text-sm"><?= htmlspecialchars($courses[($index + 1) % count($courses)]['curso_nombre']) ?></h3>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
        
        <!-- Controles del carrusel -->
        <div class="absolute inset-y-0 left-0 flex items-center">
          <button id="prevBtn-courses" class="btn btn-circle bg-orange-400 hover:bg-orange-500 border-none ml-2 rounded-lg">❮</button>
        </div>
        <div class="absolute inset-y-0 right-0 flex items-center">
          <button id="nextBtn-courses" class="btn btn-circle bg-orange-400 hover:bg-orange-500 border-none mr-2 rounded-lg">❯</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Sección del Carrusel de Escuelas -->
  <div class="relative overflow-hidden py-16 bg-ghost" id="title-schools" data-aos="fade-up">
    <div class="container mx-auto px-4">
      <h2 class="text-3xl font-bold text-center text-white mb-8">Escuelas aliadas</h2>
      <div class="carousel w-full relative">
        <div class="overflow-hidden">
          <div class="flex transition-transform duration-300 ease-in-out" id="carousel-container-schools">
            <div class="flex" id="carousel-inner-schools">
              <?php foreach ($schools as $index => $school): ?>
                <div class="carousel-item flex-none w-full flex justify-center items-center px-4">
                  <div class="flex space-x-8 items-center">
                    <!-- Escuela anterior -->
                    <div class="card w-64 bg-base-100 opacity-50 rounded-lg shadow-xl">
                      <figure><img src="<?= htmlspecialchars($schools[($index - 1 + count($schools)) % count($schools)]['escuela_imagen_url']) ?>" alt="escuela" class="w-full h-32 object-cover rounded-t-lg" /></figure>
                      <div class="card-body p-4">
                        <h3 class="card-title text-sm"><?= htmlspecialchars($schools[($index - 1 + count($schools)) % count($schools)]['escuela_nombre']) ?></h3>
                      </div>
                    </div>
                    
                    <!-- Escuela actual -->
                    <div class="card w-80 bg-base-100 z-10 rounded-lg shadow-xl">
                      <figure><img src="<?= htmlspecialchars($school['escuela_imagen_url']) ?>" alt="<?= htmlspecialchars($school['escuela_nombre']) ?>" class="w-full h-48 object-cover rounded-t-lg" /></figure>
                      <div class="card-body">
                        <h2 class="card-title text-orange-400"><?= htmlspecialchars($school['escuela_nombre']) ?></h2>
                        <p class="text-sm"><?= htmlspecialchars(substr($school['escuela_descripcion'], 0, 100)) ?>...</p>
                        <p class="text-xs">Contacto: <?= htmlspecialchars($school['escuela_telefono']) ?></p>
                        <p class="text-lg font-bold"><?= htmlspecialchars($school['escuela_direccion']) ?></p>
                        <div class="card-actions justify-end">
                          <a href="escuela_detalle.php?id=<?= $school['escuela_id'] ?>" class="btn btn-sm rounded-lg btn-primary bg-orange-400 hover:bg-orange-500 border-none">Ver Escuela</a>
                        </div>
                      </div>
                    </div>
                    
                    <!-- Escuela siguiente -->
                    <div class="card w-64 bg-base-100 shadow-xl opacity-50 rounded-lg">
                      <figure><img src="<?= htmlspecialchars($schools[($index + 1) % count($schools)]['escuela_imagen_url']) ?>" alt="escuela" class="w-full h-32 object-cover rounded-t-lg" /></figure>
                      <div class="card-body p-4">
                        <h3 class="card-title text-sm"><?= htmlspecialchars($schools[($index + 1) % count($schools)]['escuela_nombre']) ?></h3>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
        
        <!-- Controles del carrusel -->
        <div class="absolute inset-y-0 left-0 flex items-center">
          <button id="prevBtn-schools" class="btn btn-circle bg-orange-400 hover:bg-orange-500 border-none ml-2 rounded-lg">❮</button>
        </div>
        <div class="absolute inset-y-0 right-0 flex items-center">
          <button id="nextBtn-schools" class="btn btn-circle bg-orange-400 hover:bg-orange-500 border-none mr-2 rounded-lg">❯</button>
        </div>
      </div>
    </div>
  </div>
 </section>
<section>
  <div class="flex justify-center p-8 "data-aos="fade-up" >
    <div class="grid grid-col-1 gap-4 pr-6">
        <img class="rounded-lg shadow-lg shadow-orange-500/50"
          src="IMG/design/escuelanew.png"
          alt="escuela"
        />
      </div>
      <div class="flex flex-col justify-center text-white z-10">
        <div class="text-4xl tracking-wide leading-tight font-extrabold">Eleva tu emprendimiento a un 200%</div>
        <div class="text-4xl tracking-wide leading-tight font-bold">Unete a nuestra comunidad</div>
        <div class="flex mt-6 space-x-4">
          <button class="w-1/2 bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">
          <a href="register_escuela.php"> Registrar mi escuela </a>
          </button>
          <button class="w-1/2 bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">
          <a href="aliados.php">Ver escuelas </a>
          </button>
        </div>
      </div>
    </div>
  </div>

    <div class="w-full max-w-[1200px] mx-auto p-8 px-4" id="nosotros">
        <h2 class="text-4xl font-bold text-center mb-12 text-white">Sobre Nosotros</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <div class="bg-black rounded-lg shadow-lg p-8 bg-black">
                <h3 class="text-2xl font-semibold mb-4 text-yellow-600">Objetivo General</h3>
                <p class="text-white mb-6">Desarrollar un sistema de información para las instituciones ubicadas en las localidades de Bosa y Ciudad Bolívar, ofreciendo una mejor gestión de horarios, inscripciones y registro de operaciones.</p>
                <div class="flex items-center justify-center">
                    <i class="fas fa-bullseye text-5xl text-yellow-500"></i>
                </div>
            </div>
            
            <div class="bg-black rounded-lg shadow-lg p-8 bg-black ">
                <h3 class="text-2xl font-semibold mb-4 text-orange-400">Justificación</h3>
                <ul class="text-white space-y-4">
                    <li><i class="fas fa-check-circle text-green-500 mr-2"></i>Brindamos visibilidad a escuelas pequeñas, generando equidad y nuevas oportunidades.</li>
                    <li><i class="fas fa-brain text-blue-500 mr-2"></i>Fomentamos el desarrollo de habilidades sociales, emocionales y cognitivas.</li>
                    <li><i class="fas fa-users text-purple-500 mr-2"></i>Facilitamos la interacción entre comunidades para descubrir actividades y colaborar.</li>
                </ul>
            </div>
        </div>
        
        <div class="mt-12 pb-24">
            <h3 class="text-2xl font-semibold mb-6 text-center text-orange-400">Creadores del Proyecto</h3>
            <div class="flex flex-wrap justify-center gap-8">
                <div class="bg-black rounded-lg shadow-lg p-6 text-center w-64">
                    <img src="IMG/nosotros.png" alt="Daniel Josué Peña Puentes" class="rounded-full mx-auto mb-4">
                    <h4 class="font-semibold text-lg">Daniel Josué Peña Puentes</h4>
                    <p class="text-withe ">Desarrollador</p>
                </div>
                <div class="bg-black rounded-lg shadow-lg p-6 text-center w-64">
                    <img src="IMG/nosotros.png" alt="Kevin Alejandro Parra Cifuentes" class="rounded-full mx-auto mb-4">
                    <h4 class="font-semibold text-lg">Kevin Alejandro Parra Cifuentes</h4>
                    <p class="text-withe ">Desarrollador</p>
                </div>
                <div class="bg-black rounded-lg shadow-lg p-6 text-center w-64">
                    <img src="IMG/nosotros.png" alt="Daniel Sneither Bernal Gómez" class="rounded-full mx-auto mb-4">
                    <h4 class="font-semibold text-lg">Daniel Sneither Bernal Gómez</h4>
                    <p class="text-withe ">Desarrollador</p>
                </div>
            </div>
        </div>
    </div>
            <!-- Footer -->
            <footer class="bg-ghost text-zinc-50 p-2">
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
        

  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init({
      duration: 1000
    });
  
   // Función para inicializar un carrusel
   function initializeCarousel(containerId, innerId, prevBtnId, nextBtnId) {
      const container = document.getElementById(containerId);
      const inner = document.getElementById(innerId);
      const items = inner.querySelectorAll('.carousel-item');
      const totalItems = items.length;
      let currentIndex = 0;

      function updateCarousel() {
        const itemWidth = items[0].offsetWidth;
        const newTransform = -currentIndex * itemWidth;
        inner.style.transform = `translateX(${newTransform}px)`;
        items.forEach((item, index) => {
          item.style.opacity = index === currentIndex ? '1' : '0.5';
        });
      }

      function nextSlide() {
        currentIndex = (currentIndex + 1) % totalItems;
        updateCarousel();
      }

      function prevSlide() {
        currentIndex = (currentIndex - 1 + totalItems) % totalItems;
        updateCarousel();
      }

      function adjustCarouselLayout() {
        const containerWidth = container.offsetWidth;
        const itemWidth = containerWidth;
        items.forEach(item => item.style.width = `${itemWidth}px`);
        inner.style.width = `${itemWidth * totalItems}px`;
        updateCarousel();
      }

      document.getElementById(nextBtnId).addEventListener('click', nextSlide);
      document.getElementById(prevBtnId).addEventListener('click', prevSlide);

      // Auto-avance
      let autoAdvanceInterval = setInterval(nextSlide, 5000);

      // Detener auto-avance en interacción
      container.addEventListener('mouseenter', () => clearInterval(autoAdvanceInterval));

      // Reiniciar auto-avance después de inactividad
      container.addEventListener('mouseleave', () => {
        clearInterval(autoAdvanceInterval);
        autoAdvanceInterval = setInterval(nextSlide, 5000);
      });

      // Ajustar el carrusel al cargar y al cambiar el tamaño de la ventana
      window.addEventListener('load', adjustCarouselLayout);
      window.addEventListener('resize', adjustCarouselLayout);
    }

    // Inicializar ambos carruseles
    document.addEventListener('DOMContentLoaded', function() {
      initializeCarousel('carousel-container-courses', 'carousel-inner-courses', 'prevBtn-courses', 'nextBtn-courses');
      initializeCarousel('carousel-container-schools', 'carousel-inner-schools', 'prevBtn-schools', 'nextBtn-schools');

      // Prevenir el desplazamiento automático al carrusel
      if (window.location.hash) {
        setTimeout(function() {
          window.scrollTo(0, 0);
        }, 1);
      }
    });
  </script>
</body>
</html>
   