<?php

class User{
    //Declaração dos atributos do Usuário.
    public $id;
    public $email;
    public $password;
    public $name;
    public $birthdate;
    public $city;
    public $work;
    public $avatar;
    public $cover;
    public $token;


}

interface UserDAO{
    public function findByToken($token);
}