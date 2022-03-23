<?php

require_once 'models/PostComent.php';

class PostComentsDaoMySql implements PostComentsDAO{

    private $pdo;

    public function __construct(PDO $driver){
        $this->pdo = $driver;
    }
    

    public function getComents($idPost){
        $array = [];

        return $array;
    }

    public function addComents( PostComent $c){

    }
}