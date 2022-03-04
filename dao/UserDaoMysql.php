<?php

require_once 'models/User.php';


class UerDaoMysql implements UserDAO {
    private $pdo;

    public function __construct(PD $driver) {
        $this->pdo = $driver;
    }

    public function findByToken($token) {
        
    }
    
}