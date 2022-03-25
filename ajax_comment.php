<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/PostComentsDaoMySql.php';


$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();

$id = filter_input(INPUT_POST, "id");
$txt = filter_input(INPUT_POST, "txt");

$array = [];

if ($id && $txt) {
    $postCommentDao = new PostComentsDaoMySql($pdo);

    $newComment = new PostComent();
    $newComment->idPost = $id;
    $newComment->idUser = $userInfo->id;
    $newComment->body = $txt;
    $newComment->createdAt = date('Y-m-d H:i:s');

    $postCommentDao->addComents($newComment);

    $array = [
        'error' => '',
        'link' => $base.'/perfil.php?id='.$userInfo->id,
        'avatar' => $base.'/media/avatars/'.$userInfo->avatar,
        'name' => $userInfo->name,
        'body' => $txt,
    ];
}

header('Content-Type: application/json');
echo json_encode($array);
exit;
