<?php

class PostLikes{
    //Declaração dos atributos do Post.
    public $id;
    public $idPost;
    public $idUSer; //text | photo
    public $createdAt;
}

interface PostLikesDAO {
    public function getLikeCount($idPost); //Conta a quantidade de likes do post.
    public function isLiked($idPost, $idUser); // Verifica se o usuário deu like no post
    public function likeToggle($idPost, $idUser); //Ativa ou desativa o like na publicação. 

}