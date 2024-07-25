<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Deportivo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-cover bg-center min-h-screen backdrop-blur-xl " style="background-image: url('IMG/lucha_4k.jpg');">
<div class="flex items-center justify-center min-h-screen py-10">
<section class="w-full max-w-2xl bg-white/50 p-6 mx-auto rounded-xl shadow-md space-y-4 backdrop-blur-xl">
  <div class="text-center">
    <h2 class="font-bold text-3xl text-black">Regístrate</h2>
  </div>

  <form action="PHP/register.php" method="post" class="space-y-4">
    <div class="flex flex-col">
      <label for="usuario_nombre" class="text-sm font-semibold text-gray-600">Nombre</label>
      <div class="relative">
        <input type="text" id="usuario_nombre" name="usuario_nombre" placeholder="Nombres" class="w-full px-4 py-2 mt-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FFD700] transition duration-200 pr-10">
        <i class="fa fa-user absolute top-1/2 right-3 transform -translate-y-1/2 text-[#FFD700]"></i>
      </div>
    </div>

    <div class="flex flex-col">
      <label for="usuario_apellido" class="text-sm font-semibold text-gray-600">Apellido</label>
      <div class="relative">
        <input type="text" id="usuario_apellido" name="usuario_apellido" placeholder="Apellidos" class="w-full px-4 py-2 mt-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FFD700] transition duration-200 pr-10">
        <i class="fa fa-user-tag absolute top-1/2 right-3 transform -translate-y-1/2 text-[#FFD700]"></i>
      </div>
    </div>

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

    <div class="flex flex-col">
      <label for="usuario_tipo_documento" class="text-sm font-semibold text-gray-600">Tipo de Documento</label>
      <select id="usuario_tipo_documento" name="usuario_tipo_documento" class="w-full px-4 py-2 mt-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FFD700] transition duration-200">
        <option value="CC">Cédula de Ciudadanía</option>    
        <option value="CE">Cédula de Extranjería</option>
        <option value="TI">Tarjeta de Identidad</option>    
        <option value="PPN">Pasaporte</option>    
        <option value="NIT">NIT</option>    
        <option value="SSN">SSN</option>    
        <option value="EIN">EIN</option> 
      </select>
    </div>

    <div class="flex flex-col">
      <label for="usuario_documento" class="text-sm font-semibold text-gray-600">Número de Documento</label>
      <div class="relative">
        <i class="fa fa-id-card absolute top-1/2 right-3 transform -translate-y-1/2 text-[#FFD700]"></i>
        <input type="number" id="usuario_documento" name="usuario_documento" placeholder="Documento" class="w-full px-4 py-2 mt-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FFD700] transition duration-200 pr-10">
      </div>
    </div>

    <div class="flex flex-col">
      <label for="usuario_nacimiento" class="text-sm font-semibold text-gray-600">Fecha de Nacimiento</label>
      <div class="relative">
        <input type="date" id="usuario_nacimiento" name="usuario_nacimiento" class="w-full px-4 py-2 mt-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FFD700] transition duration-200 pr-10">
        <i class="fa fa-calendar-alt absolute top-1/2 right-3 transform -translate-y-1/2 text-[#FFD700]"></i>
      </div>
    </div>

    <button type="submit" class="w-full bg-[#FFD700] hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
      Enviar
    </button>
  </form>
  
  <div class="text-center mt-4">
    <p class="text-sm text-gray-600">¿Ya tienes una cuenta? <a href="login.php" class="text-black hover:text-blue-500 transition duration-200">Inicia sesión</a></p>
  </div>
</section>
</div>
</body>
</html>
