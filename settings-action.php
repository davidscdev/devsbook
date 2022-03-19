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

/*************************************************
*   Trata o envio do Avatar
*************************************************/
//Testa se o avatar foi enviado e se não contém erro no envio
if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0 && !empty($_FILES['avatar']['tmp_name'])) {
    $newAvatar = $_FILES['avatar'];
    //Testa se o tipo de imagem enviado é um jpeg, jpg ou png
    if (in_array($newAvatar['type'], ['image/jpeg','image/jpg','image/png'])) {
        $widthAvatar = 200;
        $heightAvatar = 200;

        list($widthOrig, $heightOrig) = getimagesize($newAvatar['tmp_name']);
        $ratio = $widthOrig / $heightOrig;

        $newWidth = $widthAvatar;
        $newHeight = $newWidth / $ratio;

        if($newHeight < $heightAvatar){
            $newHeight = $heightAvatar;
            $newWidth = $newHeight * $ratio;
        }

        // Centraliza a imagem (horizontal e verticalmente)
        $x = $widthAvatar - $newWidth;
        $y = $heightAvatar - $newHeight;
        $x = $x<0 ? $x/2 : $x;
        $y = $y<0 ? $y/2 : $y;

        $finalImage = imagecreatetruecolor($widthAvatar, $heightAvatar);
        switch ($newAvatar['type']) {
            case 'image/jpeg':
            case 'image/jpg':
                $image = imagecreatefromjpeg($newAvatar['tmp_name']);
            break;
            case 'image/png':
                $image = imagecreatefrompng($newAvatar['tmp_name']);
            break;
        }

        imagecopyresampled(
            $finalImage, $image,
            $x, $y, 0, 0,
            $newWidth, $newHeight, $widthOrig, $heightOrig);

        $avatarName = md5(time().rand(0,999)).'.jpg';

        imagejpeg($finalImage, './media/avatars/'.$avatarName, 100);

        $userInfo->avatar = $avatarName;
    }
}

/*************************************************
*   Trata o envio do Capa
*************************************************/
//Testa se a capa foi enviado e se não contém erro no envio
if (isset($_FILES['cover']) && $_FILES['cover']['error'] == 0 && !empty($_FILES['cover']['tmp_name'])) {
    $newCover = $_FILES['cover'];
    //Testa se o tipo de imagem enviado é um jpeg, jpg ou png
    if (in_array($newCover['type'], ['image/jpeg','image/jpg','image/png'])) {
        $widthCover = 850;
        $heightCover = 310;

        list($widthOrig, $heightOrig) = getimagesize($newCover['tmp_name']);
        $ratio = $widthOrig / $heightOrig;

        $newWidth = $widthCover;
        $newHeight = $newWidth / $ratio;

        if($newHeight < $heightCover){
            $newHeight = $heightCover;
            $newWidth = $newHeight * $ratio;
        }

        // Centraliza a imagem (horizontal e verticalmente)
        $x = $widthCover - $newWidth;
        $y = $heightCover - $newHeight;
        $x = $x<0 ? $x/2 : $x;
        $y = $y<0 ? $y/2 : $y;

        $finalImage = imagecreatetruecolor($widthCover, $heightCover);
        switch ($newCover['type']) {
            case 'image/jpeg':
            case 'image/jpg':
                $image = imagecreatefromjpeg($newCover['tmp_name']);
            break;
            case 'image/png':
                $image = imagecreatefrompng($newCover['tmp_name']);
            break;
        }

        imagecopyresampled(
            $finalImage, $image,
            $x, $y, 0, 0,
            $newWidth, $newHeight, $widthOrig, $heightOrig);

        $coverName = md5(time().rand(0,999)).'.jpg';

        imagejpeg($finalImage, './media/covers/'.$coverName, 100);

        $userInfo->cover = $coverName;
    }
}

$user->update($userInfo);
    


header('Location:'.$base.'/settings.php');
exit;