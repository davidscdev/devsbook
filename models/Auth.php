<?php

require __DIR__ . '/../dao/UserDaoMysql.php';

class Auth{

    private $pdo;
    private $base;
    private $userPdo;

    public function __construct(PDO $pdo, $base){
        $this->pdo = $pdo;
        $this->base = $base;
        //Inserido o PDO de Usuário pois o mesmo é necessário em todos os métodos.
        $this->userPdo = new UserDaoMysql($this->pdo);
    }

    public function checkToken(){
        //Verifica se a seção foi iniciada e se existe um token válido para esse usuário;.
        if (!empty($_SESSION['token'])) {
            $token = $_SESSION['token'];

            $user = $this->userPdo->findByToken($token);

            if ($user) {
                return $user;
            }
        }

        //Caso não exista token válido, direciona o usuário para o login e finaliza a execução.
        header("Location: ".$this->base."/login.php");
        exit;

    }

    public function validateLogin($email, $password){
        $user = $this->userPdo->findByEmail($email);
        if ($user) {
            if (password_verify($password, $user->password)) {
                $token = md5(time().rand(0,9999));

                $_SESSION['token'] = $token;
                $user->token = $token;

                $this->userPdo->update($user);

                return true;
            }
        }

        return false;
    }

    public function emailExist($email){
        return $this->userPdo->findByEmail($email) ? true : false;
    }

    public function registerUser($name, $email, $password, $birthdate){
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $token = md5(time().rand(0,9999));

        $newUser = new User();
        $newUser->name = $name;
        $newUser->email = $email;
        $newUser->password = $hash;
        $newUser->birthdate = $birthdate;
        $newUser->token = $token;

        $this->userPdo->insert($newUser);

        $_SESSION['token'] = $token;
    }
}