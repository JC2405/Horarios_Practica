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
$rol = $_POST['rol'] ?? ''; 

if (empty($email) || empty($password) || empty($rol)) {
    echo json_encode(['success' => false, 'message' => 'Por favor, complete todos los campos']);
    exit;
}

try {
    $usuario = new login();
    $result = false;

    
    if ($rol === 'aprendiz') {
        $result = $usuario->loginAprendiz($email, $password);

        if ($result) {
            $_SESSION['user_id'] = $result['idAprendiz'];
            $_SESSION['user_name'] = $result['nombre'];
            $_SESSION['rol'] = 'aprendiz';
            echo json_encode(['success' => true, 'message' => 'Login exitoso', 'rol' => 'aprendiz']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Correo o contraseña incorrectos']);
        }

    } elseif ($rol === 'funcionario') {
        $result = $usuario->loginFuncionarios($email, $password, 'coordinador');
        $actualRol = 'coordinador';
        if (!$result) {
            $result = $usuario->loginFuncionarios($email, $password, 'instructor');
            $actualRol = 'instructor';
        }

                

        if ($result) {
            $_SESSION['user_id'] = $result['idFuncionario'];
            $_SESSION['user_name'] = $result['nombre'];
            $_SESSION['rol'] = $actualRol;
            echo json_encode(['success' => true, 'message' => 'Login exitoso', 'rol' => $actualRol]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Correo o contraseña incorrectos']);
        }

    } else {
        echo json_encode(['success' => false, 'message' => 'Rol inválido']);
        exit;
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error interno en el servidor']);
}