<?php
require "conexion.php";

$token = isset($_GET['token']) ? $_GET['token'] : null;
$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = isset($_POST['new_password']) ? $_POST['new_password'] : null;
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : null;
    $token = isset($_POST['token']) ? $_POST['token'] : null;

    if ($new_password && $confirm_password && $token) {
        if ($new_password === $confirm_password) {
            // Verificar si el token es válido y no ha expirado
            $sql = "SELECT usuario_documento FROM recuperacion_cuentas WHERE token = ? AND fecha_expiracion > NOW()";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $usuario_documento = $row['usuario_documento'];

                // Actualizar la contraseña
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_sql = "UPDATE usuarios SET usuario_password = ? WHERE usuario_documento = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("ss", $hashed_password, $usuario_documento);
                
                if ($update_stmt->execute()) {
                    // Eliminar el token usado
                    $delete_sql = "DELETE FROM recuperacion_cuentas WHERE token = ?";
                    $delete_stmt = $conn->prepare($delete_sql);
                    $delete_stmt->bind_param("s", $token);
                    $delete_stmt->execute();

                    $success = "Tu contraseña ha sido actualizada exitosamente. Ahora puedes iniciar sesión con tu nueva contraseña.";
                } else {
                    $error = "Ocurrió un error al actualizar la contraseña. Por favor, inténtalo de nuevo.";
                }
            } else {
                $error = "El enlace para restablecer la contraseña ha expirado o no es válido.";
            }
        } else {
            $error = "Las contraseñas no coinciden.";
        }
    } else {
        $error = "Por favor, completa todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="mb-4">Restablecer Contraseña</h2>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php else: ?>
                    <form method="POST" action="">
                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Nueva Contraseña</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirmar Nueva Contraseña</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Restablecer Contraseña</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>