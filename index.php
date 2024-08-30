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
    .gradient-circle {
      position: absolute;
      border-radius: 50%;
      filter: blur(50px);
    }

    .hero-section {
      min-height: 70vh;
      padding-top: 120px; /* Añadir espacio para el navbar */
    }

    .navbar {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 50; /* Asegura que el navbar esté por encima de otros elementos */
      background: rgba(0, 0, 0, 0.5); /* Transparente con fondo negro */
    }
  </style>
</head>

<body class="bg-black min-h-screen relative overflow-hidden">
  <!-- Navbar -->
  <div class="navbar bg-transparent py-4"> <!-- Navbar transparente -->
    <div class="navbar-start">
      <div class="dropdown">
        <div tabindex="0" role="button" class="btn btn-ghost btn-circle text-xl" data-aos="zoom-in">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
          </svg>
        </div>
        <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow"
          data-aos="zoom-in">
          <li><a class="text-lg">Inicio</a></li>
          <li><a class="text-lg">Perfil</a></li>
          <li><a class="text-lg">Contacto</a></li>
        </ul>
      </div>
    </div>
    <div class="navbar-center" data-aos="zoom-in">
      <a class="btn btn-ghost text-3xl font-bold">CLASSMENT ACADEMY</a>
    </div>
    <div class="navbar-end">
      <div>
        <?php if(isset($_SESSION['usuario_documento'])): ?>
        <a href="perfil_usuario.php" class="flex items-center space-x-2 text-xl" data-aos="zoom-in">
          <span>Mi Perfil</span>
          <i class="fas fa-user-circle text-3xl"></i>
        </a>
        <?php else: ?>
        <a href="login.php" class="flex items-center space-x-2 text-xl" data-aos="zoom-in">
          <span>Ingresa</span>
          <i class="fas fa-sign-in-alt text-3xl"></i>
        </a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Sección Hero -->
  <section class="hero-section flex items-center justify-center relative">
    <!-- Círculos de gradiente -->
    <div class="gradient-circle w-32 h-32 bg-green-400 top-10 left-10 opacity-50"></div>
    <div class="gradient-circle w-64 h-64 bg-purple-400 top-10 right-10 opacity-30"></div>
    <div class="gradient-circle w-64 h-64 bg-pink-400 bottom-10 left-10 opacity-30"></div>
    <div class="gradient-circle w-64 h-64 bg-orange-400 bottom-10 right-10 opacity-30"></div>
    <div class="gradient-circle w-64 h-64 bg-blue-400 bottom-10 right-1/4 opacity-30"></div>

    <div class="container mx-auto px-4 z-10">
      <div class="text-center mb-12">
        <h1 class="text-4xl md:text-6xl font-bold text-white mb-4 "data-aos="zoom-in-down">Despierta el talento<br>que llevas dentro</h1>
        <p class="text-xl text-gray-300 mb-8 "data-aos="zoom-in-down">Sumérgete en esta gran aventura y sácale el mayor provecho a tu instinto
          deportivo.</p>
        <button class="btn bg-gradient-to-br from-purple-700 to-blue-700 btn-lg "data-aos="zoom-in-down">ÚNETE</button>
      </div>

      <div class="flex flex-wrap justify-center gap-8 "data-aos="fade-up">
        <div class="w-40 h-56 md:w-48 md:h-64 bg-gradient-to-br from-red-500 to-orange-500 rounded-full overflow-hidden">
          <img src="../IMG/design/corredora.png" alt="Deportista 1" class="w-full h-full object-cover">
        </div>
        <div class="w-40 h-56 md:w-48 md:h-64 bg-gradient-to-br from-pink-500 to-purple-500 rounded-full overflow-hidden">
          <img src="../IMG/design/karateka.png" alt="Deportista 2" class="w-full h-auto object-cover">
        </div>
        <div class="w-40 h-56 md:w-48 md:h-64 bg-gradient-to-br from-purple-500 to-blue-500 rounded-full overflow-hidden">
          <img src="../IMG/design/fisoculturista.png" alt="Deportista 3" class="w-full h-50px object-cover">
        </div>
        <div class="w-40 h-56 md:w-48 md:h-64 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-full overflow-hidden">
          <img src="../IMG/design/yoga.png" alt="Deportista 4" class="w-full h-60px object-cover">
        </div>
        <div class="w-40 h-56 md:w-48 md:h-64 bg-gradient-to-br from-cyan-500 to-green-500 rounded-full overflow-hidden">
          <img src="../IMG/design/futbolista.png" alt="Deportista 5" class="w-full h-full object-cover">
        </div>
      </div>
    </div>
  </section>


  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init({
      duration: 1000
    });
  </script>
</body>

</html>

