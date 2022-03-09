<?php
require 'config.php';
require 'models/Auth.php';
require 'dao/PostDaoMysql.php';

//Verfica se o usuário está logado e retorna as info do mesmo.
$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();



$body = filter_input(INPUT_POST, 'body');

//Se recebeu a info bopdy
if ($body) {
    $postDao = new PostDaoMysql($pdo); //Instancia PostDaoMysql

    $newPost = new Post(); //Instancia um objeto Post

    //Preenche as info de Post
    $newPost->idUser = $userInfo->id;
    $newPost->type = 'text';
    $newPost->createdAt = date('Y-m-d H:i:s');
    $newPost->body = $body;

    //Executa o insert usando o objeto Post
    $postDao->insert($newPost);
}

//redireciona o usuuário pra Home
header('Location: '.$base);
exit;
