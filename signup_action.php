<?php
require 'config.php';
require 'models/Auth.php';

$name = filter_input(INPUT_POST, 'name');
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = filter_input(INPUT_POST, 'password');
$birthdate = filter_input(INPUT_POST, 'birthdate');

//var_dump($email, $password);exit;

if ($name && $email && $password && $birthdate) {
    $auth = new Auth($pdo, $base);

    //Valida se todas as partes da data foram digitadas.
    $birthdate = explode('/',$birthdate);
    if(count($birthdate) != 3){
        $_SESSION['msg'] = 'Formato de data inválido!';
        header("Location: ".$base."/signup.php");
        exit;        
    }

    //Valida se a data informada é uma data válida em seu formato (dd/mm/aaaa).
    $birthdate = $birthdate[2] . '-' . $birthdate[1] . '-' . $birthdate[0];
    if (strtotime($birthdate) == false) {
        $_SESSION['msg'] = 'Formato de data inválido!';
        header("Location: ".$base."/signup.php");
        exit;        
    }

    //Verifica se o email já está cadastrado.
    if($auth->emailExist($email)){
        $_SESSION['msg'] = 'OPS, esse e-mail já está em uso!';
        header("Location: ".$base."/signup.php");
        exit;        
    }

    $auth->registerUser($name, $email, $password, $birthdate);
    header("Location: ".$base);
    exit;        

}

$_SESSION['msg'] = 'Verifique os dados. Tá faltando algo!!';
header("Location: ".$base."/signup.php");
exit;

?>