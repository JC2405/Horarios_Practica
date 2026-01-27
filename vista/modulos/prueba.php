<?php

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f0f0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
        }
        .logout {
            float: right;
            background-color: #f44336;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
        }
        .logout:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="../logout.php" class="logout">Cerrar sesión</a>
        <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
        <p>Esta es tu página de dashboard. Aquí puedes agregar más contenido según tus necesidades.</p>
    </div>
</body>
</html>