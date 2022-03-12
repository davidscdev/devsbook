<?php

class UserRelations{
    //Declaração dos atributos do Relations.
    public $id;
    public $userFrom;
    public $userTo;
}

interface UserRelationsDAO{
    public function insert(UserRelations $ur);
    public function getFollowing($id);
    public function getFollower($id);
}