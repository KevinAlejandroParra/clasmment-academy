<?php
session_start();
require "PHP/conexion.php";

if($_SERVER["REQUEST_METHOD"] === "POST"){

    //valores requeridos para el inicio de sesión
    $user = $_POST["escuela_correo"];
    $pass = $_POST["escuela_password"];

    //consulta sql
    $escuela_query = "SELECT * FROM `escuelas` WHERE escuela_correo = :correo AND escuela_estado = :estado";
    $stmt = $conn->prepare($escuela_query);
    $stmt->bindValue(":correo",$user, PDO::PARAM_STR);
    $stmt->bindValue(":estado", "activo", PDO::PARAM_STR);
    $stmt->execute();

    $escuela = $stmt->fetch(PDO::FETCH_ASSOC);

    //verificar la contraseña encriptada 
    if($escuela){
        if(password_verify($pass,$escuela['escuela_password'])){
          
            //guardar información de la escuela al iniciar
            $_SESSION['escuela_id'] = $escuela['escuela_id'];
            $_SESSION['escuela_nombre'] = $escuela['escuela_nombre'];
            $_SESSION['escuela_documento'] = $escuela['escuela_documento'];
            $_SESSION['escuela_descripcion'] = $escuela['escuela_descripcion'];
            $_SESSION['escuela_telefono'] = $escuela['escuela_telefono'];
            $_SESSION['escuela_direccion'] = $escuela['escuela_direccion'];
            $_SESSION['escuela_correo'] = $escuela['escuela_correo'];
            $_SESSION['escuela_password'] = $escuela['escuela_password'];
            $_SESSION['escuela_imagen_url'] = $escuela['escuela_imagen_url'];
            $_SESSION['escuela_fecha_creacion'] = $escuela['escuela_fecha_creacion'];

            //realizado correctamente
            echo "Inicio de sesión exitoso";
            //redirigir al index
            header("Location: index.php");
            exit;
        } else {
            echo "Correo electrónico o contraseña de su escuela incorrectos";
        }
    } else {
        echo "Correo electrónico o contraseña de su escuela incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Escuela</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="shortcut icon" href="IMG/logo_mini.png" type="image/x-icon">
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
<div class="bg-black min-h-screen relative overflow-hidden">
    <!-- Círculos de gradiente -->
    <div class="gradient-circle w-44 h-32 bg-orange-300 top-10 left-10 opacity-40"></div>
    <div class="gradient-circle w-64 h-64 bg-blue-300 top-10 right-10 opacity-30"></div>
    <div class="gradient-circle w-64 h-56 bg-blue-400 bottom-10 left-10 opacity-30"></div>
    <div class="gradient-circle w-64 h-64 bg-orange-300 bottom-10 right-1/4 opacity-30"></div>

<body class="bg-black min-h-screen relative overflow-hidden" >
  <!-- Contenedor de burbujas -->
  <div class="bubble-container">
    <div class="bubble" style="left: 10%; animation-duration: 12s; animation-delay: 2s;"></div>
    <div class="bubble" style="left: 30%; animation-duration: 10s; animation-delay: 0s;"></div>
    <div class="bubble" style="left: 50%; animation-duration: 15s; animation-delay: 4s;"></div>
    <div class="bubble" style="left: 70%; animation-duration: 8s; animation-delay: 6s;"></div>
    <div class="bubble" style="left: 90%; animation-duration: 14s; animation-delay: 1s;"></div>
  </div>

  <!--Contenedor de login-->
  <div class="flex items-center justify-center min-h-screen relative z-10">
    <div class="w-full max-w-lg p-8">
        <div class="bg-black shadow-2xl shadow-orange-400/60 border-solid border-t-2 border-l-2 border-orange-400 p-10 rounded-lg backdrop-blur-lg shadow-lg space-y-6">
            <div class="text-center">
                <h2 class="font-bold text-3xl text-gray-200">Bienvenido de nuevo</h2>
                <p class="text-sm mt-2 text-orange-400">Inicia Sesión para administrar tu escuela</p>
            </div>

            <!--espacio para el próximo div y evitar errores-->
            <form action="login_escuela.php" method="post" class="space-y-6">
                <div class="space-y-2"> <label for="escuela_correo" class="text-sm font-semibold text-orange-400">Correo Electrónico</label>
                  <div class="relative">
                     <input type="email" id="escuela_correo" name="escuela_correo" placeholder="Correo Electrónico de la escuela"
                     class="w-full px-4 py-3 bg-gray-900/70 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 text-white" required>
                     <i class="fa fa-envelope absolute top-1/2 right-3 transform -translate-y-1/2 text-yellow-500"></i>                    
                  </div>
                </div>

                <div class="space-y-2">
                    <label for="escuela_password" class="text-sm font-semibold text-orange-400">Contraseña</label>
                      <div class="relative">
                         <input type="password" id="escuela_password" name="escuela_password" placeholder="Contraseña" 
                         class="w-full px-4 py-3 bg-gray-900/70 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 text-white" required>
                         <i class="fa fa-lock absolute top-1/2 right-3 transform -translate-y-1/2 text-yellow-500"></i>
                      </div>
                </div>

                <a href="PHP/recovery.php" clas="text-sm mt-2 text-orange-400">¿Olvidaste tu contraseña?</a>

                  <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-4 rounded-lg transition duration-300">                    
                    Iniciar Sesión
                  </button>

            </form>
            <div class="text-center">
                <p class="text-sm text-gray-400">¿Aún no has registrado tu escuela? <a href="register_escuela.php" class="text-orange-400 hover:text-orange-300">Regísrate aquí</a></p>
            </div>

        </div>
    </div>
  </div>
<body>
    
</body>
</html>