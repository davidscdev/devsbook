<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/UserDaoMysql.php';
require_once 'dao/UserRelationsDaoMysql.php';
require_once 'models/UserRelations.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();

$id = filter_input(INPUT_GET, 'id');

if ($id) {
    $checkUser = new UserDaoMysql($pdo);
    $useRelations = new UserRelationsDaoMysql($pdo);

    if ($checkUser->findById($id)) {

        $ru = new UserRelations();
        $ru->userFrom = $userInfo->id;
        $ru->userTo = $id;

        if ($useRelations->isFollowing($userInfo->id, $id)) {
            //Deixar de seguir
            $useRelations->delete($ru);
        }else{
            $useRelations->insert($ru);
        }

        header('Location:'.$base.'/profile.php?id='.$id);
        exit;
        
        
    }
    
}

header('Location:'.$base);
exit;