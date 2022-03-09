<?php

require_once 'models/UserRelations.php';


class UserRelationsDaoMysql implements UserRelationsDAO {
    private $pdo;

    public function __construct(PDO $driver) {
        $this->pdo = $driver;
    }

}