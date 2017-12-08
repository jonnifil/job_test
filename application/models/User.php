<?php
/**
 * Created by PhpStorm.
 * User: jonni
 * Date: 01.12.17
 * Time: 20:30
 */

namespace application\models;


use core\Model;

class User extends Model
{
    public $table_name = 'user';
    protected $id;
    protected $role_id;
    protected $login;
    protected $password;

    const ROLE_ADMIN = 1;
    const ROLE_GUEST = 2;
    const ROLE_USER  = 3;

    /**
     * @param $args
     * @return mixed
     * @throws \Exception
     */
    public function insert($args){
        $login = trim($args['login']);
        $password = trim($args['password']);
        if($login == '' || $password == '')
            throw new \Exception('Поля логин и пароль должны быть заполнены!');
        $check = $this->check_login($login);
        if($check[0] == 0){
            $db = $this->db;
            $query = $db->prepare('INSERT INTO user(role_id, login, password) VALUES (:role_id, :login, :password)');
            $query->execute([
                ':role_id' => '3',
                ':login' => $login,
                ':password' => $password
            ]);
            $id = $db->lastInsertId();
            return $id;
        }else
            throw new \Exception("В системе уже есть пользователь с логином {$login} . Введите другое значение!");
    }

    /**
     * @param $login
     * @return mixed
     */
    protected function check_login($login){
        $db = $this->db;
        $query = $db->prepare('SELECT COUNT(id) FROM user WHERE login = :login');
        $query->execute([
            ':login' => $login
        ]);
        $result = $query->fetch(\PDO::FETCH_NUM);
        return $result;
    }

    /**
     * @param $args
     * @return mixed
     * @throws \Exception
     */
    public function login($args){
        $login = trim($args['login']);
        $password = trim($args['password']);
        if($login == '' || $password == '')
            throw new \Exception('Поля логин и пароль должны быть заполнены!');
        $db = $this->db;
        $query = $db->prepare('SELECT * FROM user WHERE login = :login AND password = :password');
        $query->execute([
            ':login' => $login,
            ':password' => $password
        ]);
        return $query->fetch(\PDO::FETCH_ASSOC);
    }

    public function get_login(){
        return $this->login;
    }
}