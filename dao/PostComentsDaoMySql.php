<?php

require_once 'models/PostComent.php';
require_once 'dao/UserDaoMysql.php';

class PostComentsDaoMySql implements PostComentsDAO{

    private $pdo;

    public function __construct(PDO $driver){
        $this->pdo = $driver;
    }
    

    public function getComents($idPost){
        $arrayResult = [];

        $sql = $this->pdo->prepare("SELECT * FROM postscoments
        WHERE id_post = :idPost");

        $sql->bindValue(':idPost', $idPost);
        $sql->execute();

        if($sql->rowCount()>0){

            $userInfo = new UserDaoMysql($this->pdo);

            $data = $sql->fetchAll(PDO::FETCH_ASSOC);

            foreach ($data as $coment) {
                $comentItem = new PostComent();
                $comentItem->id = $coment['id'];
                $comentItem->idPost = $coment['id_post'];
                $comentItem->idUser = $coment['id_user'];
                $comentItem->body = $coment['body'];
                $comentItem->createdAt = $coment['created_at'];
                //Busca usuário para complementar as informações do sistema.
                $comentItem->user = $userInfo->findById($coment['id_user']);

                $arrayResult[] = $comentItem;

            }
        }

        return $arrayResult;
    }

    public function addComents( PostComent $pc){
        
        $sql = $this->pdo->prepare("INSERT INTO postscoments
            (id_post, id_user, created_at, body) VALUES (:idPost, :idUser, :createdAt, :body)");

        $sql->bindValue(':idPost', $pc->idPost);
        $sql->bindValue(':idUser', $pc->idUser);
        $sql->bindValue(':createdAt', $pc->createdAt);
        $sql->bindValue(':body', $pc->body);

        $sql->execute();
    }
}