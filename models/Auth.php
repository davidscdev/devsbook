<?php

class Auth{

    private $pdo;
    private $base;

    public function __construct(PDO $pdo, $base){
        $this->pdo = $pdo;
        $this->base = $base;
    }

    public function checkToken(){
        //Verifica se a seção foi iniciada e se existe um token válido para esse usuário;.
        if (!empty($_SESSION['token'])) {
            $token = $_SESSION['token'];
        }

        //Caso não exista token válido, direciona o usuário para o login e finaliza a execução.
        header("Location: ".$this->base."/login.php");
        exit;

    }
}