<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Deportivo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-cover bg-center min-h-screen backdrop-blur-xl " style="background-image: url('IMG/login.jpg');">
<div class="flex items-center justify-center min-h-screen">
<section class="w-full max-w-md bg-white/70 p-6 mx-auto rounded-xl shadow-md space-y-4 backdrop-blur-xl">
  <div class="text-center">
    <h2 class="font-bold text-3xl text-black">Login</h2>
    <p class="text-sm mt-2 text-gray-600">Por favor, ingrese sus credenciales para iniciar sesión</p>
  </div>

  <form action="login.php" method="post" class="space-y-4">
    <div class="flex flex-col">
      <label for="usuario_correo" class="text-sm font-semibold text-gray-600">Correo Electrónico</label>
      <div class="relative">
        <input type="email" id="usuario_correo" name="usuario_correo" placeholder="Correo Electrónico" class="w-full px-4 py-2 mt-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FFD700] transition duration-200 pr-10">
        <i class="fa fa-envelope absolute top-1/2 right-3 transform -translate-y-1/2 text-[#FFD700]"></i>
      </div>
    </div>

    <div class="flex flex-col">
      <label for="usuario_password" class="text-sm font-semibold text-gray-600">Clave</label>
      <div class="relative">
        <input type="password" id="usuario_password" name="usuario_password" placeholder="Clave" class="w-full px-4 py-2 mt-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FFD700] transition duration-200 pr-10">
        <i class="fa fa-lock absolute top-1/2 right-3 transform -translate-y-1/2 text-[#FFD700]"></i>
      </div>
    </div>

    <button type="submit" class="w-full bg-[#FFD700] hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
      Enviar
    </button>
  </form>
  
  <div class="text-center mt-4">
    <p class="text-sm text-gray-600">¿No tienes una cuenta? <a href="register.php" class="text-blue-500 hover:text-yellow-600 transition duration-200">Regístrate</a></p>
  </div>
</section>
</div>
</body>
</html>

