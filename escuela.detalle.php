<?php
    try {
        $escuela_id = $_GET['id'] ?? 0;
        if ($escuela_id <= 0) {
            throw new Exception("No se encontró la escuela solicitada");
        }

        $escuela_query = "SELECT * FROM escuelas WHERE escuela_id = :escuela_id";
        $stmt = $conn->prepare($escuela_query);
        $stmt->bindValue(':escuela_id', $escuela_id, PDO::PARAM_INT);
        $stmt->execute();
        $escuela = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$escuela) {
            throw new Exception("No se encontró la escuela solicitada");
        }

    } catch (Exception $e) {
        // Manejar el error, por ejemplo, redirigir a la página principal
        header("Location: index.php");
        exit();
    }
?>
    <!DOCTYPE html>
    <html lang="es" data-theme="dark">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo htmlspecialchars($escuela['escuela_nombre']); ?> | Classment Academy</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://cdn.jsdelivr.net/npm/daisyui@3.9.4/dist/full.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="shortcut icon" href="IMG/logo.png" type="image/x-icon">
    </head>
    <body class="bg-gray-800">

