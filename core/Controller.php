<?php
/**
 * Created by PhpStorm.
 * User: jonni
 * Date: 01.12.17
 * Time: 20:23
 */

namespace core;



class Controller
{
    protected $view;
    protected $layout = 'index';
    public $auth_user;

    public function __construct(){
        session_start();
        $this->auth();
        $this->view = new View($this->layout);
    }

    protected function auth(){
        if(!isset($_SESSION['user'])){
            $user = ['id'=>2, 'role_id'=>2, 'login'=>'guest'];
            $_SESSION['user'] = $user;
            $this->auth_user = $user;
        }else{
            $this->auth_user = $_SESSION['user'];
        }
    }

    protected function redirect($controller_name){
        header('HTTP/1.1 200 OK');
        header('Location: http://'.$_SERVER['HTTP_HOST'].'/'.$controller_name);
        exit();
    }

    protected function logout(){
        $user = ['id'=>2, 'role_id'=>2, 'login'=>'guest'];
        $_SESSION['user'] = $user;
        $this->auth_user = $user;
        $this->redirect('auth');
    }
}