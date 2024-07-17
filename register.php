<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>registro_ca</title>
</head>
<body>
    <form action="PHP/register.php" method ="post" >

    <input type="text" name="usuario_nombre" placeholder="nombres"> 
    <input type="text" name="usuario_apellido"placeholder="apellidos">
    <input type="email" name="usuario_correo"placeholder="correo electronico">
    

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
</body>
</html>