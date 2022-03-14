<?php

require_once 'models/User.php';
require_once 'dao/UserRelationsDaoMysql.php';
require_once 'dao/PostDaoMysql.php';



class UserDaoMysql implements UserDAO {
    private $pdo;

    public function __construct(PDO $driver) {
        $this->pdo = $driver;
    }


    private function generateUser($array, $full = false){
        $u = new User();
        $u->id = $array['id'] ?? 0;
        $u->email = $array['email'] ?? '';
        $u->password = $array['password'] ?? '';
        $u->name = $array['name'] ?? '';
        $u->birthdate = $array['birthdate'] ?? '';
        $u->city = $array['city'] ?? '';
        $u->work = $array['work'] ?? '';
        $u->avatar = $array['avatar'] ?? '';
        $u->cover = $array['cover'] ?? '';
        $u->token = $array['token'] ?? '';


        if ($full) {
            // quem o usuário segue
            $userRel = new UserRelationsDaoMysql($this->pdo);
            $userPhotos = new PostDaoMysql($this->pdo);
            
            $u->following = $userRel->getFollowing($u->id);

            //Transforma o id do seguidor nos dados completos
            foreach ($u->following as $key => $following_id) {
                $newUser = $this->findById($following_id);
                $u->following[$key] = $newUser;
            }
       
            // quem segue o usuário
            $u->follower = $userRel->getFollower($u->id);
            foreach ($u->follower as $key => $follower_id) {
                $newUser = $this->findById($follower_id);
                $u->follower_id[$key] = $newUser;
            }
            $u->photos = $userPhotos->getPhotosFrom($u->id);
        }
        return $u;
    }

    public function findByToken($token) {
        if (!empty($token)) {
            $sql = $this->pdo->prepare("SELECT * FROM users WHERE token = :token;");
            $sql->bindValue(':token', $token);
            $sql->execute();

            if ($sql->rowCount() > 0) {
                $data = $sql->fetch(PDO::FETCH_ASSOC);

                $user = $this->generateUser($data);

                return $user;
            }
        }

        return false;

    }

    public function findByEmail($email) {
        if (!empty($email)) {
            $sql = $this->pdo->prepare("SELECT * FROM users WHERE email = :email;");
            $sql->bindValue(':email', $email);
            $sql->execute();

            if ($sql->rowCount() > 0) {
                $data = $sql->fetch(PDO::FETCH_ASSOC);

                $user = $this->generateUser($data);

                return $user;
            }
        }
        return false;

    }

    public function update(User $u){
        $sql = $this->pdo->prepare("UPDATE users SET
        email = :email,
        password = :password,
        name = :name,
        birthdate = :birthdate,
        city = :city,
        work = :work,
        avatar = :avatar,
        cover = :cover,
        token = :token
        WHERE id =:id"
        );

        $sql->bindValue(':email', $u->email);
        $sql->bindValue(':password', $u->password);
        $sql->bindValue(':name', $u->name);
        $sql->bindValue(':birthdate', $u->birthdate);
        $sql->bindValue(':city', $u->city);
        $sql->bindValue(':work', $u->work);
        $sql->bindValue(':avatar', $u->avatar);
        $sql->bindValue(':cover', $u->cover);
        $sql->bindValue(':token', $u->token);
        $sql->bindValue(':id', $u->id);

        if($sql->execute()){
            return true;
        }else{
            echo("Erro ao atualizar o registro: ");
            print_r($sql->errorInfo());
            exit;
        }
    }

    public function insert(User $u){
        $sql = $this->pdo->prepare("INSERT INTO users (name, email, password, birthdate, token) 
                                                VALUES (:name, :email, :password, :birthdate, :token)"
        );

        $sql->bindValue(':name', $u->name);
        $sql->bindValue(':email', $u->email);
        $sql->bindValue(':password', $u->password);
        $sql->bindValue(':birthdate', $u->birthdate);
        $sql->bindValue(':token', $u->token);

        if($sql->execute()){
            return true;
        }else{
            echo("Erro ao adicionar novo registro: ");
            print_r($sql->errorInfo());
            exit;
        }

        return true;
    }

    /*
    * Função pra localizar o usuário pelo id.
    * ---> Inserido o parâmetro $full pra que outras informações não contidas na 
    *      tb_users possam ser retornadas.
    */
    public function findById($id, $full = false){
        if (!empty($id)) {
            $sql = $this->pdo->prepare("SELECT * FROM users WHERE id = :id;");
            $sql->bindValue(':id', $id);
            $sql->execute();

            if ($sql->rowCount() > 0) {
                $data = $sql->fetch(PDO::FETCH_ASSOC);

                $user = $this->generateUser($data, $full);

                return $user;
            }
        }
        return false;
    }
    
}