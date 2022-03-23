<?php

class PostComent{
    //Declaração dos atributos do Post.
    public $id;
    public $idPost;
    public $idUser;
    public $body;
    public $createdAt;
}

interface PostComentsDAO{
    public function getComents($idPost);
    public function addComents( PostComent $c);
}