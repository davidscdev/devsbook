<?php

class Post{
    //Declaração dos atributos do Post.
    public $id;
    public $idUser;
    public $type; //text | photo
    public $body;
    public $createdAt;
}

interface PostDAO{
    public function insert(Post $p);
}