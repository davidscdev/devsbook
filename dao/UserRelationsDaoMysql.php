<?php

require_once 'models/UserRelations.php';


class UserRelationsDaoMysql implements UserRelationsDAO {
    private $pdo;

    public function __construct(PDO $driver) {
        $this->pdo = $driver;
    }
    
    public function insert(UserRelations $ur){

    }

    /*
    * Busca os perfis que o usuário segue 
    */
    public function getFollowing($id){
        $users = [];

        $sql = $this->pdo->prepare('SELECT user_to FROM userrelations WHERE user_from = :user_from');

        $sql->bindValue(':user_from', $id);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $data = $sql->fetchAll();

            foreach ($data as $value) {
                $users[] = $value['user_to'];
            }
        }

        return $users;

    }

    /*
    * Busca os seguidores do usuário 
    */
    public function getFollower($id){
        $users = [];

        $sql = $this->pdo->prepare('SELECT user_from FROM userrelations WHERE user_to = :user_to');

        $sql->bindValue(':user_to', $id);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $data = $sql->fetchAll();

            foreach ($data as $value) {
                $users[] = $value['user_from'];
            }
        }

        return $users;


    }

    /*
    * Verifica se o usuário é seguido ou não */
    public function isFollowing($id1, $id2){
        $sql = $this->pdo->prepare("SELECT * FROM userrelations WHERE user_from = :user_from AND user_to = :user_to");

        $sql->bindValue(':user_from', $id1);
        $sql->bindValue(':user_to', $id2);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            return true;
        }else{
            return false;
        }

    }
}