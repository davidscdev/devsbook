<?php

require_once 'models/UserRelations.php';


class UserRelationsDaoMysql implements UserRelationsDAO {
    private $pdo;

    public function __construct(PDO $driver) {
        $this->pdo = $driver;
    }
    
    public function insert(UserRelations $ur){

    }

    public function getRelationsFromId($id){
        $users = [$id];

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
}