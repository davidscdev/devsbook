<?php
require_once 'models/Post.php';
require_once 'dao/UserRelationsDaoMysql.php';
require_once 'dao/UserDaoMysql.php';
require_once 'dao/PostLikeDaoMySql.php';
require_once 'dao/PostComentsDaoMySql.php';

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

    public function getUserFeed($idUser){
        $array = [];

        // Pega os posts do usuário ordenados por data
        $sql = $this->pdo->prepare("SELECT * FROM posts
        WHERE id_user = :idUser
        ORDER BY created_at DESC");

        $sql->bindValue(":idUser", $idUser);

        $sql->execute();

        if ($sql->rowCount() > 0) {
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            // 3 - Transforma o resultado em objetos para serem inseridos em bloco.
            $array = $this->_postListToObject($data, $idUser);
        }
        
        return $array;
    }


    
    public function getFeedHome($idUser){
        $array = [];

        // 1 - Lista os usuários que o usuário logado segue.
        $usRel = new UserRelationsDaoMysql($this->pdo);
        $userList = $usRel-> getFollowing($idUser);
        
        //Adiciona o próprio usuário na lista para que ele possa ver os próprios posts.
        $userList[] = $idUser;
        // 2 - Pega os posts ordenados por data
        $sql = $this->pdo->query("SELECT * FROM posts
        WHERE id_user IN (".implode(',',$userList).")
        ORDER BY created_at DESC");


        if ($sql->rowCount() > 0) {
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            // 3 - Transforma o resultado em objetos para serem inseridos em bloco.
            $array = $this->_postListToObject($data, $idUser);
        }
        
        return $array;
    }


    public function getPhotosFrom($idUser) {
            $photos = [];
            
            // Pega os posts do usuário ordenados por data
            $sql = $this->pdo->prepare("SELECT * FROM posts
            WHERE id_user = :idUser AND type = 'photo'
            ORDER BY created_at DESC");

            $sql->bindValue(":idUser", $idUser);

            $sql->execute();

            if ($sql->rowCount() > 0) {
                $data = $sql->fetchAll(PDO::FETCH_ASSOC);
                
                // 3 - Transforma o resultado em objetos para serem inseridos em bloco.
                $photos = $this->_postListToObject($data, $idUser);
            }
        
            return $photos;
    }

    private function _postListToObject($postList, $idUser){
        $posts = [];
        $userDao = new UserDaoMysql($this->pdo);
        $likesDao = new PostLikeDaoMySql($this->pdo);
        $comentsDao = new PostComentsDaoMySql($this->pdo);

        foreach ($postList as $postItem) {
            $newPost = new Post();
            $newPost->id = $postItem['id'];
            $newPost->idUser = $postItem['id_user'];
            $newPost->type = $postItem['type'];
            $newPost->createdAt = $postItem['created_at'];
            $newPost->body = $postItem['body'];
            $newPost->mine = false;

            if ($postItem['id_user'] == $idUser) {
                $newPost->mine = true;
            }

            //Complementa com as informações do usuário do post
            $newPost->user = $userDao->findById($postItem['id_user']);

            //Pega informações de LIKES
            $newPost->likeCount = $likesDao->getLikeCount($newPost->id);
            $newPost->liked = $likesDao->isLiked($newPost->id, $idUser);
            
            //Pega informações de COMENTÁRIOS
            $newPost->comments = $comentsDao->getComents($newPost->id);

            $posts[] = $newPost;

        }

        return $posts;
    }
}


?>