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


    public function loginFuncionarios($email, $password, $rol){
        $query = "SELECT f.*, r.nombreRol as rol FROM funcionario f
                  INNER JOIN funcionariorol fr ON f.idFuncionario = fr.idFuncionario
                  INNER JOIN rol r ON fr.idRol = r.idRol
                  WHERE f.correo = :correo AND r.nombreRol = :rol LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt ->execute(['correo' => $email, 'rol' => $rol]);
        $user = $stmt->fetch();

        if($user && $password === $user['password']){
            return $user;
        }
        return false;
    }
}