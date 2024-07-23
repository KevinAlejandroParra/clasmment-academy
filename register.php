<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>registro_ca</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>


<body>
<section class="bg-gray-50 min-h-screen flex items-center justify-center">
  <!-- login container -->
  <div class="bg-gray-100 flex rounded-2xl shadow-lg max-w-3xl p-5 items-center">
    <!-- form -->
    <div class="md:w-1/2 px-8 md:px-16">
      <h2 class="font-bold text-2xl text-[##FFD700]">Registrate</h2>
      <p class="text-xs mt-4 text-[#000000]">Sumerjete en esta aventura</p>

    <form action="PHP/register.php" method ="post" >

    <input type="text" name="usuario_nombre" placeholder="nombres"> 
    <input type="text" name="usuario_apellido"placeholder="apellidos">
    <input type="email" name="usuario_correo"placeholder="correo electronico">
    <input type="password" name="usuario_password"placeholder="clave">
    

    <select name="usuario_tipo_documento"> 
        <option value="CC"> Cedula de cuidadania</option>    
        <option value="CE"> Cedula de Extranjeria</option>
        <option value="TI"> Tarjeta de identidad</option>    
        <option value="PPN"> PPN</option>    
        <option value="NIT"> NIT</option>    
        <option value="SSN"> SSN</option>    
        <option value="EIN"> EIN</option> 
    </select>           

    <input type="number" name="usuario_documento"placeholder="documento">
    <input type="date" name="usuario_nacimiento">
     
    <button type="submit"> ENVIAR</button>

    </form>

    </div>

    </div>
    </section>
</body>
</html>