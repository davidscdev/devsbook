<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/UserDaoMysql.php';


$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();

$user = new UserDaoMysql($pdo);


$name = filter_input(INPUT_POST,'name');
$email = filter_input(INPUT_POST,'email', FILTER_VALIDATE_EMAIL);
$birthdate = filter_input(INPUT_POST,'birthdate');
$work = filter_input(INPUT_POST,'work');
$city = filter_input(INPUT_POST,'city');
$password = filter_input(INPUT_POST,'password');
$password_confirm = filter_input(INPUT_POST,'password_confirm');

//Verifica se o nome e a senha foram preenchidos
if ($name && $email) {
    
    $userInfo->name = $name;
    $userInfo->work = $work;
    $userInfo->city = $city;
    
    //Verifica se houve mudança no e-mail
    if ($userInfo->email != $email) {
    
        //Verifica se o e-mail informado é já está em uso
        if ($use->findByEmail($email) === false) {
            $userInfo->email = $email;
        }else{
            $_SESSION['msg'] = 'Email já se encontra em uso!';
            header('Location:'.$base.'/settings.php');
            exit;
        }
    }
     
    //Valida se todas as partes da data foram digitadas.
        $birthdate = explode('/',$birthdate);
        

        if(count($birthdate) != 3){
            $_SESSION['msg'] = 'Formato de data inválido!';
            header('Location:'.$base.'/settings.php');
            exit;        
        }

        //Valida se a data informada é uma data válida em seu formato (dd/mm/aaaa).
        $birthdate = $birthdate[2] . '-' . $birthdate[1] . '-' . $birthdate[0];
        if (strtotime($birthdate) == false) {
            $_SESSION['msg'] = 'Formato de data inválido!';
            header('Location:'.$base.'/settings.php');
            exit;        
        }

        $userInfo->birthdate = $birthdate;

        if (!empty($password)) {
            if ($password === $password_confirm) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $userInfo->password = $hash;
            }else{
                $_SESSION['msg'] = 'As senhas não são iguais!';
                header('Location:'.$base.'/settings.php');
                exit;
            }
        }    
}
$userInfo->email = $email;
$user->update($userInfo);
    


header('Location:'.$base.'/settings.php');
exit;