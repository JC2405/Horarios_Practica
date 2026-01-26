<?php 

include_once "conexion.php";


class login {

    private $conn;

    public function __construct(){
        $this->conn = Conexion ::Conectar();
    }

    public function loginAprendiz($email, $password) {
        $query = "SELECT * FROM aprendiz WHERE correo = :correo LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt ->execute(['correo' => $email]);
        $user = $stmt->fetch();


        if($user && $password === $user['password']){
            return $user;
        }

        return false;
    }


    public function loginFuncionarios($email, $password){
        $query = "SELECT * FROM funcionario WHERE correo = :correo LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt ->execute(['correo' => $email]);
        $user = $stmt->fetch();

        if($user && $password === $user['password']){
            return $user;
        }
        return false;
    }
}