c

                echo "Inicio de sesión exitoso";
                // Redirigir a la página principal o dashboard
                header("Location: index.php");
                exit;
            } else {
                echo "Correo electrónico o contraseña incorrectos";
            }
        } else {
            echo "Correo electrónico o contraseña incorrectos";
        }
  
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Classment Academy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="shortcut icon" href="IMG/logo_mini.png" type="image/x-icon">
</head>




<a href="index.php" class="absolute top-4 left-4 bg-yellow-500 hover:bg-yellow-600 text-black font-bold py-2 px-4 rounded-lg transition duration-200 flex items-center space-x-2 shadow-lg">
            <i class="fas fa-arrow-left"></i>
            <span>Volver</span>
        </a>
<body class="bg-black">
    <div class="w-full min-h-screen bg-cover bg-center flex items-center justify-center px-4" style="background-image: url('IMG/back_login.png');">
        <div class="w-full max-w-6xl flex flex-col md:flex-row items-center justify-between space-y-8 md:space-y-0 md:space-x-8">
        </div>
            Login Form Section 
            <div class="w-full md:w-1/2">
                <div class="bg-blur p-8 rounded-xl shadow-2xl space-y-6">
                    <div class="text-center">
                        <h2 class="font-bold text-3xl text-white">Login</h2>
                        <p class="text-sm mt-2 text-gray-300">Ingrese sus credenciales para iniciar sesión</p>
                    </div>
                    
                   

                    <form action="login.php" method="post" class="space-y-6">
                        <div class="space-y-2">
                            <label for="usuario_correo" class="text-sm font-semibold text-white">Correo Electrónico</label>
                            <div class="relative">
                                <input type="email" id="usuario_correo" name="usuario_correo" placeholder="Correo Electrónico" class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 text-white" required>
                                <i class="fa fa-envelope absolute top-1/2 right-3 transform -translate-y-1/2 text-yellow-500"></i>
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <label for="usuario_password" class="text-sm font-semibold text-white">Contraseña</label>
                            <div class="relative">
                                <input type="password" id="usuario_password" name="usuario_password" placeholder="Contraseña" class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 text-white" required>
                                <i class="fa fa-lock absolute top-1/2 right-3 transform -translate-y-1/2 text-yellow-500"></i>
                            </div>
                        </div>
                        
                        <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-black font-bold py-2 px-4 rounded-lg transition duration-300">
                            Iniciar Sesión
                        </button>
                    </form>
                    
                    <div class="text-center">
                        <p class="text-sm text-gray-300">¿No tienes una cuenta? <a href="register.php" class="text-yellow-500 hover:text-yellow-400 transition duration-300">Regístrate</a></p>
                        <a href="/PHP/recovery.php" class="text-white hover:text-yellow-400"> ¿Olvidaste tu contraseña?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 