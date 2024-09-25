<!DOCTYPE html>
<html data-theme="black" lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu Perfil - Classment Academy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@3.9.4/dist/full.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

</head>
<body class="bg-black relative min-h-screen overflow-y-auto">
    <!-- Barra de navegación -->
    <div class="container mx-auto p-4">
      <div class="navbar bg-transparent flex items-center justify-center">
        <div class="navbar-start">
        <a href="index.php" class="btn btn-ghost">
        <img src="../IMG/logo.png" alt="logo" class="w-f h-full" data-aos="zoom-in">
        <h1 class="text-5xl font-bold" data-aos="zoom-in"> Classment Academy</h1>
      </a>
        </div>
    </div>

    <div class="flex items-center justify-center">
    <div class="w-full max-w-5xl p-8">
      <div class="bg-black p-10 rounded-lg backdrop-blur-lg shadow-lg space-y-6">   
      <div class="flex flex-row items-center ">
        <div class="w-1/2 px-10">
          <form action="PHP/mail.php" method="POST">
            <h2 class="text-3xl font-bold tracking-wide leading-normal text-orange-300 aos-init aos-animate">Por favor, ingresa tu correo electrónico asociado a tu cuenta.</h2>
            <div class="form-control mt-6">
              <label class="label">
                <span class="label-text">Correo Electronico</span>
              </label>
              <input type="email" placeholder="Ingresa tu correo asociado a tu cuenta" class="input input-bordered" name="email" required />
            </div>
            <div class="mt-6">
              <button class="w-full btn btn-lg bg-orange-500 shadow-lg shadow-orange-500/50" type="submit">Recuperar contraseña</button>
            </div>
          </form>
        </div>
        <div class="w-1/2  flex justify-end">
          <img
          src="../IMG/design/fisoculturistas.png"
          class="max-w-5xl " data-aos="zoom-in" />
        </div>
      </div>

    </div>
  </div>
</div>
  </section>
        <!-- Tarjeta de perfil principal -->
       
        <script>
        AOS.init({
            duration: 1000,
            once: true,
        });
</script>
