<!DOCTYPE html>
<html lang="es">
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

    body {
      margin: 0;
      height: 100vh;
      background-color: #1e3c72;
      overflow: hidden;
    }

    .bubble-container {
      position: absolute;
      width: 100%;
      height: 100%;
      pointer-events: none; /* Para que las burbujas no interfieran con los elementos interactivos */
    }

    .bubble {
      position: absolute;
      bottom: -100px;
      width: 40px;
      height: 40px;
      background: rgba(255, 255, 255, 0.3);
      border-radius: 50%;
      animation: rise 10s infinite ease-in-out;
    }

    @keyframes rise {
      0% { transform: translateY(0); }
      100% { transform: translateY(-1200px); }
    }
  </style>
</head>
<!--circulos de gradiente-->
<div class="bg-black min-h-screen relative overflow-hidden">
    <!-- Círculos de gradiente -->
    <div class="gradient-circle w-44 h-32 bg-orange-300 top-10 left-10 opacity-40"></div>
    <div class="gradient-circle w-64 h-64 bg-blue-300 top-10 right-10 opacity-30"></div>
    <div class="gradient-circle w-64 h-56 bg-blue-400 bottom-10 left-10 opacity-30"></div>
    <div class="gradient-circle w-64 h-64 bg-orange-300 bottom-10 right-1/4 opacity-30"></div>
<body class="bg-black min-h-screen relative overflow-hidden" style="background-image: url('path/to/your/background-image.jpg');">
<!--contenedor de los circulos-->
<div class="bubble-container">
    <div class="bubble" style="left: 10%; animation-duration: 12s; animation-delay: 2s;"></div>
    <div class="bubble" style="left: 30%; animation-duration: 10s; animation-delay: 0s;"></div>
    <div class="bubble" style="left: 50%; animation-duration: 15s; animation-delay: 4s;"></div>
    <div class="bubble" style="left: 70%; animation-duration: 8s; animation-delay: 6s;"></div>
    <div class="bubble" style="left: 90%; animation-duration: 14s; animation-delay: 1s;"></div>
</div>
<!--contenedor del registro centrado-->
<div class="flex items -center justify-center min-h-screen relative z-10">
    <div class="w-full max-w-2xl p-8">
        <div class="bg-gradient-to-b from-gray-600/70 to-black/70 p-10 rounded-lg backdrop-blur-lg shadow-lg space-y-6">
            <div class="text-center">
                <h2 class="font-bold text-xl text-white">Registra Tu Escuela</h2>
            </div>
            <form action="PHP/register_escuela.php" method="post" class="space-y-4">
    <div class="flex space-x-2">
        <div class="flex flex-col w-1/2">
            <label for="escuela_id" class="text-sm font-semibold text-white">NIT de su escuela</label>
            <input type="text" id="escuela_id" name="escuela_id" placeholder="NIT Escuela" class="w-full px-4 py-2 bg-gray-900/70 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 text-white" required>
        </div>
        <div class="flex flex-col w-1/2">
            <label for="escuela_nombre" class="text-sm font-semibold text-white">Nombre de su escuela</label>
            <input type="text" id="escuela_nombre" name="escuela_nombre" placeholder="Nombre de la Escuela" class="w-full px-4 py-2 bg-gray-900/70 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 text-white" required>
        </div>
    </div>

    <div class="flex space-x-2">
        <div class="flex flex-col w-1/2">
            <label for="escuela_documento" class="text-sm font-semibold text-white">Documento de su escuela</label>
            <select id="escuela_documento" name="escuela_documento" placeholder="Documento de la Escuela" class="w-full px-4 py-2 bg-gray-900/70 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 text-white" required>
                <option value="NIT">NIT</option>
            </select>    
        </div>
        <div class="flex flex-col w-1/2">
            <label for="escuela_descripcion" class="text-sm font-semibold text-white">Descripcion de su escuela</label>
            <input type="text" id="escuela_descripcion" name="escuela_descripcion" placeholder="Descripción de la Escuela" class="w-full px-4 py-2 bg-gray-900/70 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 text-white" required>
        </div>
    </div>

    <div class="flex space-x-2">
        <div class="flex flex-col w-1/2">
            <label for="escuela_telefono" class="text-sm font-semibold text-white">Telefono de su escuela</label>
            <input type="tel" id="escuela_telefono" name="escuela_telefono" placeholder="Número de contacto" class="w-full px-4 py-2 bg-gray-900/70 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 text-white" required>
        </div>
        <div class="flex flex-col w-1/2">
            <label for="escuela_direccion" class="text-sm font-semibold text-white">Dirección de su escuela</label>
            <input type="text" id="escuela_direccion" name="escuela_direccion" placeholder="Direccion de su Escuela" class="w-full px-4 py-2 bg-gray-900/70 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 text-white" required>
        </div>
    </div>

    <div class="flex space-x-2">
        <div class="flex flex-col w-1/2">
            <label for="escuela_correo" class="text-sm font-semibold text-white">Correo de su escuela</label>
            <input type="email" id="escuela_correo" name="escuela_correo" placeholder="Correo de su Escuela" class="w-full px-4 py-2 bg-gray-900/70 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 text-white" required>
        </div>
        <div class="flex flex-col w-1/2">
            <label for="escuela_password" class="text-sm font-semibold text-white">Contraseña de su escuela</label>
            <input type="password" id="escuela_password" name="escuela_password" placeholder="Ingrese una contraseña segura" class="w-full px-4 py-2 bg-gray-900/70 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 text-white" required>
        </div>
    </div>

    <div class="flex flex-col">
        <label for="escuela_fecha_creacion" class="text-sm font-semibold text-white">Fecha de creación de su escuela</label>
        <input type="date" id="escuela_fecha_creacion" name="escuela_fecha_creacion" class="w-full px-4 py-2 bg-gray-900/70 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 text-white" required>
    </div>

    <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
        Enviar
    </button>

    <div class="text-center">
        <p class="text-xl text-white">¿Ya tienes una cuenta? <a href="login_escuela.php" class="text-orange-400 hover:text-orange-300"></a></p>
    </div>
         </form>
        </div>
    </div>
</div>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 1000
    });
</script>
</body>
</html>