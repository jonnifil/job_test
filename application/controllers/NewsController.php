<?php
/**
 * Created by PhpStorm.
 * User: jonni
 * Date: 01.12.17
 * Time: 20:28
 */

namespace application\controllers;


use application\models\News;
use application\models\User;
use core\Controller;

class NewsController extends Controller
{
    /**
     * @param $id
     */
    public function start($id){
        $user = $this->auth_user;
        if($user['role_id'] == User::ROLE_GUEST){
            $this->redirect('auth');
        }
        $model = new News();
        $news = $model->get_by_id($id);
        //Если новость не нашли, даём заставку
        if(!$news){
             $news = [
                 'title' => 'Новость не найдена',
                 'body' => 'Возможно она была удалена автором или Администратором',
             ];
             $can_edit = false;
        }else{
            $can_edit = $user['role_id'] == User::ROLE_ADMIN || $news['user_id'] == $user['id'];
        }

        $this->view->render('show_news', ['news' => $news, 'can_edit' => $can_edit]);
    }

    /**
     * @param $id
     */
    public function edit($id){
        $user = $this->auth_user;
        if($user['role_id'] == User::ROLE_GUEST){
            $this->redirect('auth');
        }
        $news = [
                 'id' => 0,
                 'user_id' => $user['id'],
                 'title' => '',
                 'annotate' => '',
                 'body' => ''
                ];
        if($id > 0){
             $model = new News();
             $news = $model->get_by_id($id);
             //Даём править существующие новости только админу и владельцу
             if($news['user_id'] != $user['id'] && $user['role_id'] != User::ROLE_ADMIN){
                 $this->redirect('home');
             }
        }
        $this->view->render('edit_news', ['news' => $news]);
    }

    /**
     *Всавка и редактирование новостей
     */
    public function save(){
        if(!isset($_POST['news_data']))
            return;
        $news_data = $_POST['news_data'];
        $model = new News();
        if($news_data['id'] == 0){
            $id = $model->save($news_data);
        }else{
            $id = $model->update($news_data);
        }
        return $id;
    }

    /**
     * @param $id
     */
    public function delete_news($id){
        $user = $this->auth_user;
        if($user['role_id'] == User::ROLE_GUEST){
            $this->redirect('auth');
        }
        $model = new News();
        $news = $model->get_by_id($id);
        //Даём удалять существующие новости только админу и владельцу
        if($news['user_id'] == $user['id'] || $user['role_id'] == User::ROLE_ADMIN){
            $model->delete($id);
            $this->redirect('home');
        }
    }

    public function logout()
    {
        parent::logout();
    }
}