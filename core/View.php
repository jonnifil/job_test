<?php
/**
 * Created by PhpStorm.
 * User: jonni
 * Date: 02.12.17
 * Time: 8:41
 */

namespace core;


class View
{

    protected $layout;
    public $user;
    public $host_name;

    function __construct($current_layout){
        $this->layout = $current_layout;
        $this->user = $_SESSION['user'];
        $this->host_name = $_SERVER['HTTP_HOST'];
    }

    public function fetchPartial($template, $params = array()){
        extract($params);
        ob_start();
        include VIEWS_BASEDIR.$template.'.php';
        return ob_get_clean();
    }

    // вывести отрендеренный шаблон с параметрами $params
    public function renderPartial($template, $params = array()){
        echo $this->fetchPartial($template, $params);
    }

    // получить отрендеренный в переменную $content layout-а
    // шаблон с параметрами $params
    public function fetch($template, $params = array()){
        $content = $this->fetchPartial($template, $params);
        return $this->fetchPartial('layouts/'.$this->layout, array('content' => $content));
    }

    // вывести отрендеренный в переменную $content layout-а
    // шаблон с параметрами $params
    public function render($template, $params = array()){
        echo $this->fetch($template, $params);
    }

}