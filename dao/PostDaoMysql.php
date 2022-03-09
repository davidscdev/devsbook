<?php
require_once 'models/Post.php';

class PostDaoMysql implements PostDAO {
    private $pdo;

    public function __construct(PDO $driver) {
        $this->pdo = $driver;
    }

    public function insert(Post $p){
        $sql = $this->pdo->prepare("INSERT INTO posts (id_user, type, created_at, body) 
                                                VALUES (:id_user, :type, :created_at, :body)"
        );

        $sql->bindValue(':id_user', $p->idUser);
        $sql->bindValue(':type', $p->type);
        $sql->bindValue(':created_at', $p->createdAt);
        $sql->bindValue(':body', $p->body);
        

        if($sql->execute()){
            return true;
        }else{
            echo("Erro ao adicionar novo registro: ");
            print_r($sql->errorInfo());
            exit;
        }

        return true;
    }
}


?>