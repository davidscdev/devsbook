<?php

require_once 'models/PostLikes.php';

class PostLikeDaoMysql implements PostLikesDAO{
    private $pdo;

    public function __construct(PDO $driver){
        $this->pdo = $driver;
    }
    
   //Conta a quantidade de likes do post.
    public function getLikeCount($idPost){
        
        $sql = $this->pdo->prepare("SELECT COUNT(*) as c 
                                    FROM postslikes
                                    WHERE id_post = :idPost");

        $sql->bindValue(':idPost', $idPost);
        $sql->execute();

        $data = $sql->fetch();
      
        return $data['c'];
    } 
    
    // Verifica se o usuário deu like no post
    public function isLiked($idPost, $idUser){

        $sql = $this->pdo->prepare("SELECT * 
        FROM postslikes
        WHERE id_post = :idPost AND id_user = :idUser");

        $sql->bindValue(':idPost', $idPost);
        $sql->bindValue(':idUser', $idUser);
        
        $sql->execute();

        if ($sql->rowCount()>0) {
            return true;
        }else{
            return false;
        }
        
    }

   //Ativa ou desativa o like na publicação. 
    public function likeToggle($idPost, $idUSer){
        
    }
}