<?php
session_start();
require_once "../modelo/loginModelo.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método de solicitud no válido']);
    exit;
}

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$rol = $_POST['rol'] ?? ''; // coordinador | instructor | aprendiz

if (empty($email) || empty($password) || empty($rol)) {
    echo json_encode(['success' => false, 'message' => 'Por favor, complete todos los campos']);
    exit;
}

try {
    $usuario = new login();
    $result = false;

    // Según el rol, decide qué tabla validar
    if ($rol === 'aprendiz') {
        $result = $usuario->loginAprendiz($email, $password);

        if ($result) {
            $_SESSION['user_id'] = $result['idAprendiz'];
            $_SESSION['user_name'] = $result['nombre'];
            $_SESSION['rol'] = 'aprendiz';
        }

    } elseif ($rol === 'coordinador' || $rol === 'instructor') {
        // ambos vienen de "funcionario" en tu modelo (si es tu caso)
        $result = $usuario->loginFuncionarios($email, $password);

        // Opcional: validar que en BD tenga el rol correcto (si existe un campo rol/cargo)
        // Ej: si en funcionario tienes columna "rol" con 'coordinador' o 'instructor'
        if ($result) {
            if (isset($result['rol']) && $result['rol'] !== $rol) {
                echo json_encode(['success' => false, 'message' => 'Este usuario no tiene el rol seleccionado']);
                exit;
            }

            $_SESSION['user_id'] = $result['idFuncionario'];
            $_SESSION['user_name'] = $result['nombre'];
            $_SESSION['rol'] = $rol; // coordinador o instructor
        }

    } else {
        echo json_encode(['success' => false, 'message' => 'Rol inválido']);
        exit;
    }

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Login exitoso']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Correo o contraseña incorrectos']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error interno en el servidor']);
}