<?php
$w=0;
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Новости</title>
        <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    </head>
    <body>
    <nav class="navbar navbar-default " role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="/home">Новости от наших подписчиков</a>
            </div>
        </div>
        <div class="collapse navbar-collapse navbar-right">
            <ul class="nav navbar-nav">
                <li><a href="http://<?=$this->host_name ?>/news/edit/0">Добавить новость</a></li>
                <li><a href="http://<?=$this->host_name ?>/home/logout">(<?=$this->user['login'] ?>) Выход</a></li>
            </ul>
        </div>
    </nav>
        <?=$content?>
    </body>
</html>