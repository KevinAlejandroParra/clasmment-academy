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
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


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
<body class="bg-black min-h-screen relative overflow-hidden">
<!--contenedor de los circulos-->
<div class="bubble-container">
    <div class="bubble" style="left: 10%; animation-duration: 12s; animation-delay: 2s;"></div>
    <div class="bubble" style="left: 30%; animation-duration: 10s; animation-delay: 0s;"></div>
    <div class="bubble" style="left: 50%; animation-duration: 15s; animation-delay: 4s;"></div>
    <div class="bubble" style="left: 70%; animation-duration: 8s; animation-delay: 6s;"></div>
    <div class="bubble" style="left: 90%; animation-duration: 14s; animation-delay: 1s;"></div>
</div>
<!-- boton de retoceso -->
<a href="login_escuela.php" class="fixed top-4 left-4 bg-orange-500 hover:bg-orange-800 shadow-lg shadow-orange-500/50 text-black font-bold py-2 px-4 rounded-lg transition duration-200 flex items-center space-x-2 shadow-lg z-50">
            <i class="fas fa-arrow-left"></i>
            <span>Volver</span>
        </a>  
<!--contenedor del registro centrado-->
<div class="flex items-center justify-center min-h-screen relative z-10">
    <div class="w-full max-w-2xl p-8">
        <div class="bg-black shadow-2xl shadow-orange-400/60 border-solid border-t-2 border-l-2 border-orange-400 p-10 rounded-lg backdrop-blur-lg shadow-lg space-y-6">
            <div class="text-center">
                <h2 class="font-bold text-xl text-white">Registra Tu Escuela</h2>
            </div>
            <form id="registerForm" action="PHP/register_escuela.php" method="post" class="space-y-4">
    <div class="flex space-x-2">
        <div class="flex flex-col w-1/2">
            <label for="escuela_nit" class="text-sm font-semibold text-orange-400">Nit de su escuela</label>
            <input type="text" id="escuela_nit" name="escuela_nit" placeholder="xxxxxxxxx-x" class="w-full px-4 py-2 bg-gray-900/70 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 text-white" required>
        </div>
        <div class="flex flex-col w-1/2">
            <label for="escuela_nombre" class="text-sm font-semibold text-orange-400">Nombre de su escuela</label>
            <input type="text" id="escuela_nombre" name="escuela_nombre" placeholder="nombre o abreviatura" class="w-full px-4 py-2 bg-gray-900/70 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 text-white" required>
        </div>
    </div>
    <div class="flex space-x-2">
        <div class="flex flex-col w-1/2">
            <label for="escuela_descripcion" class="text-sm font-semibold text-orange-400">Descripcion de su escuela</label>
            <input type="text" id="escuela_descripcion" name="escuela_descripcion" placeholder="Breve descripción" class="w-full px-4 py-2 bg-gray-900/70 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 text-white" required>
        </div>

        <div class="flex flex-col w-1/2">
            <label for="escuela_telefono" class="text-sm font-semibold text-orange-400">Telefono de su escuela</label>
            <input type="tel" id="escuela_telefono" name="escuela_telefono" placeholder="Número de contacto" class="w-full px-4 py-2 bg-gray-900/70 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 text-white" required>
        </div>
    </div>
        <div class="flex space-x-2">
        <div class="flex flex-col w-1/2">
            <label for="escuela_direccion" class="text-sm font-semibold text-orange-400">Dirección de su escuela</label>
            <input type="text" id="escuela_direccion" name="escuela_direccion" placeholder="Direccion especifica" class="w-full px-4 py-2 bg-gray-900/70 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 text-white" required>
        </div>
        <div class="flex flex-col w-1/2">
            <label for="escuela_correo" class="text-sm font-semibold text-orange-400">Correo de su escuela</label>
            <input type="email" id="escuela_correo" name="escuela_correo" placeholder="Correo activo" class="w-full px-4 py-2 bg-gray-900/70 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 text-white" required>
        </div>
    </div>
    <div class="flex space-x-2">
        <div class="flex flex-col w-1/2">
            <label for="escuela_password" class="text-sm font-semibold text-orange-400">Contraseña de su escuela</label>
            <input type="password" id="escuela_password" name="escuela_password" placeholder="Ingrese una contraseña segura" class="w-full px-4 py-2 bg-gray-900/70 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 text-white" required>
        </div>

    <div class="flex flex-col w-1/2">
        <label for="escuela_fecha_creacion" class="text-sm font-semibold text-orange-400">Fecha de creación de su escuela</label>
        <input type="date" id="escuela_fecha_creacion" name="escuela_fecha_creacion" class="w-full px-4 py-2 bg-gray-900/70 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 text-white" required>
    </div>
    </div>
    
              <!-- Términos y Condiciones-->
              <div class="py-2 flex items-center">
            <div class="">
               <input type="checkbox" id="termsAndConditions" class="" required/>
            </div>
            <div class="ml-3 text-base">
                <label for="termsAndConditions" class="text-base text-slate-600">
                  He leído y autorizo el uso de mis datos en los
                </label>
                <a href="#" id="openTermsModal" class="text-base text-orange-400 hover:text-orange-300 no-underline hover:underline">
                  Términos y Condiciones
                </a>
            </div>
          </div>
    <!-- Modal Terminos y Condiciones -->
    <dialog id="my_modal_1" class="modal">
                <div class="modal_box bg-base-300 border-orange-500 p-4 max-w-2xl mx-auto relative shadow-lg">
                <h3 class="py-2 px-2 text-lg text-orange-500 font-bold">Términos y Condiciones</h3>
                <div class="border-t-4 border-orange-500"></div>
                <p class="py-4 px-2 text-sm text-gray-300">Autorizo ​​de manera previa, expresa e informada, como titular de los datos personales comunicados a [Classment Academy] , para el tratamiento de mis datos personales con el fin de: (i) cumplir y hacer cumplir las obligaciones derivadas de la relación entre la Compañía y el titular de los datos, como la gestión de horarios, inscripciones, registro de operaciones, servicio de atención, entre otros; a través de los datos de contacto proporcionados, que incluyen, pero no se limitan a, correo electrónico, número celular, entre otros; (ii) comunicar información relevante sobre los servicios y mejoras que ofrece la Compañía, utilizando medios físicos, digitales y tecnologías de la información como correos electrónicos, redes sociales, mensajes de texto (SMS y/o MMS) y aplicaciones móviles; (iii) evaluar la satisfacción de los usuarios y analizar hábitos de uso para mejorar la gestión del sistema de información; (iv) compartir la información necesaria con terceros encargados de brindar soporte o mejoras en el sistema, bajo los principios de confidencialidad y seguridad de la información.</p>
                <p class="py-4 px-2 text-sm text-gray-300">El titular de los datos tiene derecho a: (i) conocer, actualizar y rectificar sus datos personales en caso de que sean inexactos, incompletos o estén desactualizados; (ii) solicitar prueba de esta autorización; (iii) ser informado sobre el tratamiento que se le ha dado a sus datos; (iv) presentar quejas ante la Superintendencia de Industria y Comercio en caso de incumplimiento; (v) revocar esta autorización y solicitar la eliminación de sus datos de acuerdo con lo establecido en la Ley 1581 de 2012; (vi) acceder de manera gratuita a los datos proporcionados. Estos derechos podrán ser ejercidos a través de los siguientes canales: [classmentacademy@gmail.com].</p>
                <div class="modal-action">
                    <button class="py-2 px-5 bg-black text-white font-semibold rounded-full shadow-md focus:outline-none btn " id="closeModal">Cerrar</button>
                </div>
                </div>
            </dialog>
    <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
        Enviar
    </button>

    <div class="text-center">
        <p class="text-sm text-gray-400">¿Ya tienes una cuenta? <a href="login_escuela.php" class="text-sm text-orange-400">Inicia sesión</a></p>
        

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
    // Script para abrir el modal al hacer clic en el enlace
    document.getElementById('openTermsModal').addEventListener('click', function (event) {
      event.preventDefault(); // Previene que el enlace navegue
      document.getElementById('my_modal_1').showModal(); // Muestra el modal
    });
     // Script para abrir el modal
     document.getElementById('openTermsModal').addEventListener('click', function (event) {
      event.preventDefault(); // Previene la navegación
      document.getElementById('my_modal_1').showModal(); // Muestra el modal
    });

    // Script para cerrar el modal
    document.getElementById('closeModal').addEventListener('click', function () {
      document.getElementById('my_modal_1').close(); // Cierra el modal
    });
    // alertas
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('PHP/register_escuela.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: '¡Éxito!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonColor: '#F97316',
                    background: '#000000',
                    color: '#F97316'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'index.php';
                    }
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: data.message,
                    icon: 'error',
                    confirmButtonColor: '#F97316',
                    background: '#0D1117',
                    color: '#F97316'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error',
                text: 'Ocurrió un error al procesar la solicitud',
                icon: 'error',
                confirmButtonColor: '#F97316',
                background: '#0D1117',
                color: '#F97316'
            });
        });
    });
    
</script>
</body>
</html>