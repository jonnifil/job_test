<?php
/**
 * Created by PhpStorm.
 * User: jonni
 * Date: 01.12.17
 * Time: 20:25
 */

namespace application\controllers;


use application\models\News;
use application\models\User;
use core\Controller;

class AuthController extends Controller
{

    function __construct()
    {
        $this->layout = 'auth';
        parent::__construct();
    }

    /**
     * Стартовая страница
     */
    public function start(){
        $user = $this->auth_user;
        if($user['role_id'] == User::ROLE_ADMIN || $user['role_id'] == User::ROLE_USER){
            $this->redirect('home');
        }
        $this->view->render('auth', []);
    }

    /**
     * @throws \Exception
     * Обработка попытки регистрации нового пользователя
     */
    public function register(){
        if(!isset($_POST['register']))
            return;
        $register = $_POST['register'];
        if(!isset($register['login']) || trim($register['login']) == '')
            throw new \Exception('Поля логин и пароль должны быть заполнены!');
        $model = new User();
        $id = $model->insert($register);
        if($id > 0){
            $user = ['id'=>$id, 'role_id'=>3, 'login'=>$register['login'] ];
            $_SESSION['user'] = $user;
            $_SESSION['user'] = $user;
            echo json_encode(['saved' => true]);
        }else{
            echo json_encode(['saved' => false]);
        }
        return;
    }

    /**
     * @throws \Exception
     * Обработка попытки входа
     */
    public function come_in(){
        if(!isset($_POST['come_in']))
            return;
        $login_data = $_POST['come_in'];
        if(!isset($login_data['login']) || trim($login_data['login']) == '')
            throw new \Exception('Поля логин и пароль должны быть заполнены!');
        $model = new User();
        $result = $model->login($login_data);
        if($result){
            $user = ['id'=>$result['id'], 'role_id'=>$result['role_id'], 'login'=>$result['login'] ];
            $_SESSION['user'] = $user;
            echo json_encode(['saved' => true]);
        }else{
            echo json_encode(['saved' => false]);
        }
        return;
    }
}