<?php
/**
 * Created by PhpStorm.
 * User: jonni
 * Date: 01.12.17
 * Time: 20:21
 */

namespace core;



class Router
{
    // Хранит конфигурацию маршрутов.
    private $routes;

    function __construct($routes_data){
        // Получаем конфигурацию из файла.
        $this->routes = $routes_data;
    }

    private function getURI(){
        if(!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }

        if(!empty($_SERVER['PATH_INFO'])) {
            return trim($_SERVER['PATH_INFO'], '/');
        }

        if(!empty($_SERVER['QUERY_STRING'])) {
            return trim($_SERVER['QUERY_STRING'], '/');
        }
    }

    public function run(){
        $uri = $this->getURI();
        //по умолчанию путь controller/action
        $internalRoute = $uri;
        // Пытаемся применить к нему правила из конфигуации для кастомной обработки.
        foreach($this->routes as $pattern => $route){
            // Если правило совпало.
            if(preg_match("~$pattern~", $uri)){
                // Получаем внутренний путь из внешнего согласно правилу.
                $internalRoute = preg_replace("~$pattern~", $route, $uri);

            }
        } // Разбиваем внутренний путь на сегменты.
        $segments = explode('/', $internalRoute);
        // Первый сегмент — контроллер.
        $controller = '\application\controllers\\'.ucfirst(array_shift($segments)).'Controller';
        // Второй — действие.
        $action = array_shift($segments);
        $action = is_null($action) ? 'start' : $action;
        // Остальные сегменты — параметры.
        $parameters = count($segments) == 1 ? $segments[0] : $segments;


        // Если не загружен нужный класс контроллера или в нём нет
        // нужного метода — 404
        if(!is_callable(array($controller, $action))){
            header("HTTP/1.0 404 Not Found");
            return;
        }

        // Вызываем действие контроллера с параметрами
        $class = new $controller();
        $class->$action($parameters);


    }
}