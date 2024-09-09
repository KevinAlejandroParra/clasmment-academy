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
  </style>
</head>

<body class="bg-black min-h-screen relative overflow-hidden">
 
  <!-- Sección Hero -->
  <section class="flex items-center justify-center relative pl-24" data-aos="fade-up">
    <!-- Círculos de gradiente -->
    <div class="gradient-circle w-32 h-32 bg-orange-300 top-10 left-10 opacity-50"></div>
    <div class="gradient-circle w-64 h-64 bg-orange-400 top-10 right-10 opacity-30"></div>
    <div class="gradient-circle w-64 h-64 bg-orange-300 bottom-10 left-10 opacity-30"></div>
    <div class="gradient-circle w-64 h-64 bg-orange-300 bottom-10 right-1/4 opacity-30"></div>

    <a href="index.php"><button class="absolute left-12 top-12 btn bg-black-900 text-white font-bold  rounded-lg transition duration-200"><i class="fa-solid fa-arrow-left" style="color: #ffffff;"></i>Regresar</button> </a>
    <div class="w-full max-w-2xl p-8">
        <div class="bg-blur p-8 rounded-xl shadow-2xl space-y-6">
            <div class="text-center">
                <h2 class="font-bold text-xl text-white">Regístrate</h2>
            </div>
            <form action="PHP/register.php" method="post" class="space-y-4">
                <div class="flex space-x-2">
                    <div class="flex flex-col w-1/2">
                        <label for="usuario_nombre" class="text-sm font-semibold text-white">Nombres</label>
                        <input type="text" id="usuario_nombre" name="usuario_nombre" placeholder="Nombres" class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 text-white" required>
                    </div>
                    <div class="flex flex-col w-1/2">
                        <label for="usuario_apellido" class="text-sm font-semibold text-white">Apellidos</label>
                        <input type="text" id="usuario_apellido" name="usuario_apellido" placeholder="Apellidos" class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 text-white" required>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <div class="flex flex-col w-1/2">
                        <label for="usuario_correo" class="text-sm font-semibold text-white">Correo Electrónico</label>
                        <input type="email" id="usuario_correo" name="usuario_correo" placeholder="Correo Electrónico" class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 text-white" required>
                    </div>
                    <div class="flex flex-col w-1/2">
                        <label for="usuario_password" class="text-sm font-semibold text-white">Clave</label>
                        <input type="password" id="usuario_password" name="usuario_password" placeholder="Clave" class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 text-white" required>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <div class="flex flex-col w-1/2">
                        <label for="usuario_tipo_documento" class="text-sm font-semibold text-white">Tipo de Documento</label>
                        <select id="usuario_tipo_documento" name="usuario_tipo_documento" class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 text-white" required>
                            <option value="CC">Cédula de Ciudadanía</option>
                            <option value="CE">Cédula de Extranjería</option>
                            <option value="TI">Tarjeta de Identidad</option>
                            <option value="PPN">Pasaporte</option>
                            <option value="NIT">NIT</option>
                            <option value="SSN">SSN</option>
                            <option value="EIN">EIN</option>
                        </select>
                    </div>
                    <div class="flex flex-col w-1/2">
                        <label for="usuario_documento" class="text-sm font-semibold text-white">Número de Documento</label>
                        <input type="text" id="usuario_documento" name="usuario_documento" placeholder="Documento" class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 text-white" required>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <div class="flex flex-col w-1/2">
                        <label for="usuario_nacimiento" class="text-sm font-semibold text-white">Fecha de Nacimiento</label>
                        <input type="date" id="usuario_nacimiento" name="usuario_nacimiento" class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 text-white" required>
                    </div>
                    <div class="flex flex-col w-1/2">
                        <label for="usuario_telefono" class="text-sm font-semibold text-white">Teléfono</label>
                        <input type="tel" id="usuario_telefono" name="usuario_telefono" placeholder="Teléfono" class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 text-white">
                    </div>
                </div>
                <div class="flex flex-col space-y-4">
        <label for="usuario_direccion" class="text-sm font-semibold text-white">Dirección</label>
        <input type="text" id="usuario_direccion" name="usuario_direccion" placeholder="Dirección" class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 text-white" required>
    </div>
    
    <div class="flex space-x-4">
        <div class="flex flex-col w-1/2">
            <label for="rol_id" class="text-sm font-semibold text-white">Selecciona un rol</label>
            <select name="rol_id" id="rol_id" class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 text-white" required>
                <option value="1">Invitado</option>
                <option value="2">Alumno</option>
                <option value="3">Profesor</option>
                <option value="4">Coordinador</option>
            </select>
        </div>
        <div class="flex flex-col w-1/2">
            <label for="escuela_id" class="text-sm font-semibold text-white">Selecciona una escuela</label>
            <select name="escuela_id" id="escuela_id" class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 text-white" required>
                <option value="4">no aplica</option>
                <option value="1">Go Fit</option>
                <option value="2">Soy Fitness</option>
                <option value="3">Taekwondo</option>
            </select>
        </div>
    </div>
    
    <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-black font-bold py-2 px-4 rounded-lg transition duration-300">
        Enviar
    </button>


<div class="text-center">
    <p class="text-xl text-white">¿Ya tienes una cuenta? <a href="login.php" class="text-yellow-500 hover:text-yellow-400 transition duration-300">Inicia sesión</a></p>
</div>

</form>

 </section>
 <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init({
      duration: 1000
    });
  </script>
</body>

</html>