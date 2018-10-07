<?php
/**
 * Created by PhpStorm.
 * User: jonni
 * Date: 07.12.17
 * Time: 14:01
 */

namespace core;


class DB
{

    /**
     * @return \PDO
     * @throws \Exception
     * Подключение к базе данных через PDO
     */
    public final static function connect(){
        $config = include (ROOT.'/config/config_db.php');
        $dsn = "mysql:dbname={$config['db_name']};host={$config['host']}";
        $user = $config['db_user'];
        $password = $config['db_pass'];

        try {
            $dbh = new \PDO($dsn, $user, $password);
        } catch (\PDOException $e) {
            throw new \Exception('Подключение не удалось: ' . $e->getMessage());
        }
        return $dbh;
    }
}