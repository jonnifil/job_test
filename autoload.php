<?php
/**
 * Created by PhpStorm.
 * User: jonni
 * Date: 02.12.17
 * Time: 19:02
 */

function __autoload ($class) {
    include ROOT .'/'. str_replace('\\', '/',$class) . '.php';
};
?>