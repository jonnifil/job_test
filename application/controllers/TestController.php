<?php
/**
 * Created by PhpStorm.
 * User: jonni
 * Date: 11.12.17
 * Time: 9:59
 */

namespace application\controllers;


use application\models\News;
use core\Controller;

class TestController extends Controller
{

    function __construct()
    {
        parent::__construct();
    }

    public function start(){
        $model = new News();
        $news = $model->get_annotate_list();
        $news_list = $news->as_array();
        $list = $model->get_all()->as_collection();
        $news_1 = $model->get(1);
        $title = $news_1->get_title();
        $this->view->render('home', ['news_list' => $news_list]);
    }
}