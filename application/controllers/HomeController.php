<?php
/**
 * Created by PhpStorm.
 * User: jonni
 * Date: 01.12.17
 * Time: 20:27
 */

namespace application\controllers;


use application\models\News;
use application\models\User;
use core\Controller;

class HomeController extends Controller
{

    function __construct()
    {
        parent::__construct();
    }

    public function start(){
        $this->check_auth();
        $model = new News();
        $news_list = $model->get_all();
        $this->view->render('home', ['news_list' => $news_list]);
    }

    /**
     * @return mixed
     * Проверка прав пользователя
     */
    protected function check_auth(){
        $user = $this->auth_user;
        if($user['role_id'] == User::ROLE_GUEST){
            $this->redirect('auth');
        }else{
            return $user;
        }
    }

    public function logout()
    {
        parent::logout();
    }

}