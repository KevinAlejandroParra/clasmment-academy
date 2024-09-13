<?php

require 'PHP/conexion.php';
session_start();

if(!isset($_SESSION['escuela_id'])){
    header("Location: login_escuela.php");
    exit();
}

$escuelas = $_SESSION['escuela_id'];
$error_message = $succes_massage = '';

try{
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $ecuelas_query = "SELECT 
    escuelas.*,
    cursos.*,
    usuarios.usuario_nombre AS profesor_nombre,
    usuarios.usuario_apellido AS profesor_apellido
FROM 
    escuelas
INNER JOIN 
    cursos ON escuelas.escuela_id = cursos.escuela_id
INNER JOIN 
    profesores_cursos ON cursos.curso_id = profesores_cursos.curso_id
INNER JOIN 
    usuarios ON profesores_cursos.usuario_documento = usuarios.usuario_documento
WHERE 
    usuarios.rol_id = 3";

    $stmt = $conn->prepare($ecuelas_query);
    $stmt->bindValue(":estado", "activo", PDO::PARAM_STR);
    $smtt->bindValue(":escuela_id", $escuela, PDO::PARAM_STR);
    $stmt->execute();
    $escuela = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$escuela){
        throw new Exception("No se encontró la escuela.");
    }
    $roles_query = "SELECT * FROM roles";
    $roles_stmt = $conn->query($roles_query);
    $roles = $roles_stmt->fetchAll(PDO::FETCH_ASSOC);

    $porfesor_cursos_query = "SELECT * FROM profesor_cursos";
    $porfesor_cursos_stmt = $conn->prepare($porfesor_cursos_query);
    $porfesor_cursos_stmt->execute();
    $porfesor_cursos = $porfesor_cursos_stmt->fetchAll(PDO::FETCH_ASSOC);

    $usuario_query = "SELECT * FROM usuarios WHERE rol_id = 3";
    $stmt = $conn->prepare($usuario_query);
    $stmt->execute();
    $profesores = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    
    // Formulario 
    if($_SERVER["REQUEST_METHOD" === "POST"]) {
        if(isset($_POST['update'])) {
            //foto de perfil
            if(isset($_FILES['escuela_imagen_url']) && $_FILES['escuela_imagen_url']['error'] == 0) {
                $escuela_imagen_url = '/IMG/escuelas/' . time() . '_' . $_FILES['escuela_imagen_url']['name'];
                move_uploaded_file($_FILES['escuela_imagen_url']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $escuela_imagen_url);
            } else {
                $escuela_imagen_url = $escuela['escuela_imagen_url'];
            }

            //Actualizar datos de la escuela
            
            $update_query = "UPDATE escuelas SET
                 escuela_nombre = :nombre,
                 escuela_descripcion = :descripcion,
                 escuela_telefono = :telefono,
                 escuela_direccion = :direccion,
                 escuela_imagen_url = :imagen_url
                 WHERE escuela_id = :id";

            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bindValue(':nombre', $_POST['escuela_nombre'], PDO::PARAM_STR);
            $update_stmt->bindValue(':descripcion', $_POST['escuela_descripcion'], PDO::PARAM_STR);
            $update_stmt->bindValue(':telefono', $_POST['escuela_telefono'], PDO::PARAM_STR);
            $update_stmt->bindValue(':direccion', $_POST['escuela_direccion'], PDO::PARAM_STR);
            $update_stmt->bindValue(':imagen_url', $escuela_imagen_url, PDO::PARAM_STR);
            $update_stmt->bindValue(':id', $escuela['escuela_id'], PDO::PARAM_INT);
            $update_stmt->execute();
            
            $success_massage = "Escuela actualizada correctamente";

            if($update_stmt->execute()) {
                $succes_message = "Perfil actualizado exitosamente.";
                $stmt ->execute();
                $escuela = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $error_message = "Error al actualizar el perfil.";
        } 
                 
    } elseif (isset($_POST['delete'])) {
        //Borrar cuenta de la escuela 

        $delete_query = "UPDATE escuelas SET escuela_lei = 'inactivo' WHERE escuela_id = :id";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bindValue(':id', $escuela['escuela_id'], PDO::PARAM_INT);

        if ($delete_stmt->execute()) {
            session_destroy();
            header("Location: login.php");
            exit();
    } else {
        $error_message = "Error al borrar la escuela.";
    }
  }  
}
} catch(PDOException $e) {
    $error_message = "Error en la base de datos: " . $e->getMessage();
} catch (Exception $e) {
    $error_message = $e->getMessage();
}


?>