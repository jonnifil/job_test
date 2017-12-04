<?php
/**
 * Created by PhpStorm.
 * User: jonni
 * Date: 01.12.17
 * Time: 20:23
 */

namespace core;


class Model
{
    private $config;
    protected $db;

    public function __construct()
    {
        $this->config = include (ROOT.'/config/config_db.php');
        $this->connect();
    }

    protected final function connect(){
        $config = $this->config;
        $dsn = "mysql:dbname={$config['db_name']};host={$config['host']}";
        $user = $config['db_user'];
        $password = $config['db_pass'];

        try {
            $dbh = new \PDO($dsn, $user, $password);
        } catch (\PDOException $e) {
            throw new \Exception('Подключение не удалось: ' . $e->getMessage());
        }
        $this->db = $dbh;
    }

}