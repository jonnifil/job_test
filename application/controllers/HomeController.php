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
        $user = $this->auth_user;
        if($user['role_id'] == User::ROLE_GUEST){
            $this->redirect('auth');
        }
        $model = new News();
        $news_list = $model->get_all();
        $this->view->render('home', ['news_list' => $news_list]);
    }

    public function logout()
    {
        parent::logout();
    }

}